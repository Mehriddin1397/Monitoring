<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Asosiy chat sahifasi (Telegram uslubida)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Foydalanuvchi qatnashayotgan barcha chatlar
        $chats = Chat::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['users', 'participants', 'latestMessage.user'])
        ->orderByDesc('last_message_at')
        ->orderByDesc('updated_at')
        ->get();

        // 2. Yangi suhbat/guruh boshlash uchun barcha xodimlar
        $allUsers = User::where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        // 3. Faol chatni aniqlash
        $activeChatId = $request->query('chat_id');
        $activeChat = null;

        if ($activeChatId) {
            $activeChat = $chats->firstWhere('id', (int) $activeChatId);
        }

        // Agar tanlanmagan bo'lsa, birinchi chatni ochish
        if (!$activeChat && $chats->isNotEmpty()) {
            $activeChat = $chats->first();
        }

        $messages = collect();

        if ($activeChat) {
            // O'qilgan deb belgilash
            ChatParticipant::where('chat_id', $activeChat->id)
                ->where('user_id', $user->id)
                ->update(['last_read_at' => now()]);

            // So'nggi xabarlarni yuklash
            $messages = $activeChat->messages()
                ->where('is_deleted', false)
                ->with(['user', 'replyTo.user'])
                ->oldest()
                ->take(150)
                ->get();
        }

        return view('admin.chat.index', compact('chats', 'activeChat', 'messages', 'allUsers'));
    }

    /**
     * Yangi 1-ga-1 shaxsiy yozishma boshlash
     */
    public function startDirect(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $currentUserId = Auth::id();
        $targetUserId = (int) $request->user_id;

        if ($currentUserId === $targetUserId) {
            return back()->with('error', "O'zingiz bilan suhbat boshlay olmaysiz!");
        }

        // Mavjud 1-ga-1 chatni tekshirish
        $existingChat = Chat::where('type', 'direct')
            ->whereHas('participants', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            })
            ->whereHas('participants', function ($q) use ($targetUserId) {
                $q->where('user_id', $targetUserId);
            })
            ->first();

        if ($existingChat) {
            return redirect()->route('chat.index', ['chat_id' => $existingChat->id]);
        }

        // Yangi direct chat yaratish
        $chat = Chat::create([
            'type' => 'direct',
            'created_by' => $currentUserId,
            'last_message_at' => now(),
        ]);

        ChatParticipant::create([
            'chat_id' => $chat->id,
            'user_id' => $currentUserId,
            'role' => 'member',
            'last_read_at' => now(),
        ]);

        ChatParticipant::create([
            'chat_id' => $chat->id,
            'user_id' => $targetUserId,
            'role' => 'member',
        ]);

        return redirect()->route('chat.index', ['chat_id' => $chat->id]);
    }

    /**
     * Yangi guruh yaratish
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'avatar' => 'nullable|image|max:5120',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $currentUserId = Auth::id();
        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('chat_avatars', 'public');
        }

        $chat = Chat::create([
            'type' => 'group',
            'name' => $request->name,
            'avatar' => $avatarPath,
            'created_by' => $currentUserId,
            'last_message_at' => now(),
        ]);

        // Guruh yaratuvchisi (admin)
        ChatParticipant::create([
            'chat_id' => $chat->id,
            'user_id' => $currentUserId,
            'role' => 'admin',
            'last_read_at' => now(),
        ]);

        // Qo'shilgan a'zolar
        foreach ($request->user_ids as $userId) {
            if ((int) $userId !== $currentUserId) {
                ChatParticipant::create([
                    'chat_id' => $chat->id,
                    'user_id' => $userId,
                    'role' => 'member',
                ]);
            }
        }

        // Tizim xabari
        Message::create([
            'chat_id' => $chat->id,
            'user_id' => $currentUserId,
            'body' => Auth::user()->name . ' «' . $chat->name . '» guruhini yaratdi',
            'type' => 'system',
        ]);

        return redirect()->route('chat.index', ['chat_id' => $chat->id])->with('success', 'Guruh muvaffaqiyatli yaratildi!');
    }

    /**
     * Xabar yuborish (matn yoki hujjat/rasm)
     */
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'body' => 'nullable|string',
            'file' => 'nullable|file|max:10485760', // max 10 GB
        ]);

        $chat = Chat::whereHas('participants', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($chatId);

        $body = trim((string) $request->body);
        $file = $request->file('file');

        if (empty($body) && !$file) {
            return response()->json(['error' => 'Xabar yoki fayl kiritilishi shart!'], 422);
        }

        $type = 'text';
        $filePath = null;
        $fileName = null;
        $fileSize = null;
        $fileMime = null;

        if ($file) {
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileMime = $file->getMimeType();
            $type = str_starts_with($fileMime, 'image/') ? 'image' : 'file';

            $filePath = $file->store('chat_files/' . $chat->id, 'public');
        }

        $replyToId = (int) $request->reply_to_id;
        if ($replyToId > 0) {
            $validReply = Message::where('id', $replyToId)->where('chat_id', $chat->id)->where('is_deleted', false)->exists();
            if (!$validReply) {
                $replyToId = null;
            }
        } else {
            $replyToId = null;
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'reply_to_id' => $replyToId,
            'body' => $body ?: null,
            'type' => $type,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_mime' => $fileMime,
        ]);

        $message->load('replyTo.user');

        $replyData = null;
        if ($message->replyTo) {
            $replyData = [
                'id' => $message->replyTo->id,
                'user_name' => $message->replyTo->user ? ($message->replyTo->user->full_name ?: $message->replyTo->user->name) : 'Xodim',
                'body' => $message->replyTo->body,
                'snippet' => $message->replyTo->getSnippet(),
                'type' => $message->replyTo->type,
            ];
        }

        // Chat vaqtini yangilash
        $chat->update(['last_message_at' => now()]);

        // Yuboruvchining last_read_at ini yangilash
        ChatParticipant::where('chat_id', $chat->id)
            ->where('user_id', Auth::id())
            ->update(['last_read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'chat_id' => $message->chat_id,
                'user_id' => $message->user_id,
                'user_name' => Auth::user()->name,
                'user_avatar' => Auth::user()->avatar_url,
                'body' => $message->body,
                'type' => $message->type,
                'file_url' => $message->file_url,
                'file_name' => $message->file_name,
                'file_size' => $message->formatted_file_size,
                'is_image' => $message->isImage(),
                'reply_to' => $replyData,
                'can_delete' => true,
                'created_at' => $message->created_at->timezone('Asia/Tashkent')->format('H:i'),
                'created_at_timestamp' => $message->created_at->timestamp,
                'is_read' => false,
                'is_me' => true,
            ],
        ]);
    }

    /**
     * Katta hajmdagi fayllarni (5 GB gacha) bo'laklab (Chunked) yuklash
     */
    public function uploadChunk(Request $request, $chatId)
    {
        $request->validate([
            'chunk' => 'required|file',
            'file_id' => 'required|string',
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
            'file_name' => 'required|string',
            'file_size' => 'required|numeric',
            'body' => 'nullable|string',
        ]);

        $chat = Chat::whereHas('participants', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($chatId);

        $fileId = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->file_id);
        $chunkIndex = (int) $request->chunk_index;
        $totalChunks = (int) $request->total_chunks;
        $chunk = $request->file('chunk');

        $tempBase = storage_path('app/chunks_tmp');
        if (!file_exists($tempBase)) {
            @mkdir($tempBase, 0775, true);
        }

        $chunkDir = $tempBase . '/' . $fileId;
        if (!file_exists($chunkDir)) {
            @mkdir($chunkDir, 0775, true);
        }

        // Bo'lakni saqlash
        $chunk->move($chunkDir, "chunk_{$chunkIndex}");

        // Agar bu oxirgi bo'lak bo'lmasa, tasdiq qaytarish
        if ($chunkIndex < ($totalChunks - 1)) {
            return response()->json([
                'success' => true,
                'completed' => false,
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
            ]);
        }

        // Oxirgi bo'lak kelganda: barcha bo'laklarni birlashtirish
        $originalName = $request->file_name;
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeExtension = $extension ? '.' . preg_replace('/[^a-zA-Z0-9]/', '', $extension) : '';
        $cleanBase = pathinfo($originalName, PATHINFO_FILENAME);
        $cleanBase = Str::slug(mb_substr($cleanBase, 0, 50)) ?: 'fayl';
        $finalFileName = time() . '_' . Str::random(10) . '_' . $cleanBase . $safeExtension;

        $chatDir = storage_path('app/public/chat_files/' . $chat->id);
        if (!file_exists($chatDir)) {
            @mkdir($chatDir, 0775, true);
        }

        $finalFilePath = $chatDir . '/' . $finalFileName;
        $out = fopen($finalFilePath, 'wb');

        if (!$out) {
            return response()->json(['error' => 'Faylni yozish uchun ochib bo\'lmadi!'], 500);
        }

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $chunkDir . "/chunk_{$i}";
            if (!file_exists($chunkPath)) {
                fclose($out);
                @unlink($finalFilePath);
                return response()->json(['error' => "Bo'lak {$i} topilmadi!"], 400);
            }

            $in = fopen($chunkPath, 'rb');
            if ($in) {
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
            @unlink($chunkPath);
        }
        fclose($out);
        @rmdir($chunkDir);

        $realSize = file_exists($finalFilePath) ? filesize($finalFilePath) : (int) $request->file_size;
        $fileMime = 'application/octet-stream';
        if (function_exists('mime_content_type')) {
            $detected = @mime_content_type($finalFilePath);
            if ($detected) {
                $fileMime = $detected;
            }
        }
        $type = str_starts_with($fileMime, 'image/') ? 'image' : 'file';
        $relativeFilePath = 'chat_files/' . $chat->id . '/' . $finalFileName;
        $body = trim((string) $request->body);

        $replyToId = (int) $request->reply_to_id;
        if ($replyToId > 0) {
            $validReply = Message::where('id', $replyToId)->where('chat_id', $chat->id)->where('is_deleted', false)->exists();
            if (!$validReply) {
                $replyToId = null;
            }
        } else {
            $replyToId = null;
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'reply_to_id' => $replyToId,
            'body' => $body ?: null,
            'type' => $type,
            'file_path' => $relativeFilePath,
            'file_name' => $originalName,
            'file_size' => $realSize,
            'file_mime' => $fileMime,
        ]);

        $message->load('replyTo.user');

        $replyData = null;
        if ($message->replyTo) {
            $replyData = [
                'id' => $message->replyTo->id,
                'user_name' => $message->replyTo->user ? ($message->replyTo->user->full_name ?: $message->replyTo->user->name) : 'Xodim',
                'body' => $message->replyTo->body,
                'snippet' => $message->replyTo->getSnippet(),
                'type' => $message->replyTo->type,
            ];
        }

        $chat->update(['last_message_at' => now()]);

        ChatParticipant::where('chat_id', $chat->id)
            ->where('user_id', Auth::id())
            ->update(['last_read_at' => now()]);

        return response()->json([
            'success' => true,
            'completed' => true,
            'message' => [
                'id' => $message->id,
                'chat_id' => $message->chat_id,
                'user_id' => $message->user_id,
                'user_name' => Auth::user()->name,
                'user_avatar' => Auth::user()->avatar_url,
                'body' => $message->body,
                'type' => $message->type,
                'file_url' => $message->file_url,
                'file_name' => $message->file_name,
                'file_size' => $message->formatted_file_size,
                'is_image' => $message->isImage(),
                'reply_to' => $replyData,
                'can_delete' => true,
                'created_at' => $message->created_at->timezone('Asia/Tashkent')->format('H:i'),
                'created_at_timestamp' => $message->created_at->timestamp,
                'is_read' => false,
                'is_me' => true,
            ],
        ]);
    }

    /**
     * Xabarni o'chirish (Soft delete)
     */
    public function deleteMessage($messageId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $message = Message::with('chat.participants')->findOrFail($messageId);

        if (!$message->canDelete($user)) {
            return response()->json(['error' => "Ushbu xabarni faqat uni yozgan yoki faylni yuklagan foydalanuvchi o'chira oladi!"], 403);
        }

        // Agar fayl biriktirilgan bo'lsa uni o'chirish
        if ($message->file_path && Storage::disk('public')->exists($message->file_path)) {
            Storage::disk('public')->delete($message->file_path);
        }

        $message->update([
            'is_deleted' => true,
            'deleted_by' => $user->id,
            'body' => null,
            'file_path' => null,
            'file_name' => null,
        ]);

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
            'chat_id' => $message->chat_id,
        ]);
    }

    /**
     * Real-time polling orqali yangi xabarlarni tekshirish
     */
    public function poll(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Joriy foydalanuvchining so'nggi faolligini yangilash
        $user->updateLastActivity();

        $activeChatId = (int) $request->active_chat_id;
        $lastMessageId = (int) $request->last_message_id;
        $lastGlobalMessageId = (int) $request->last_global_message_id;

        $newMessages = [];
        $otherLastReadTimestamp = 0;
        $chatStatus = null;

        // Foydalanuvchi qatnashayotgan barcha chatlar
        $chats = Chat::whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->with(['participants.user', 'users', 'latestMessage.user'])
            ->orderByDesc('last_message_at')
            ->get();
        $userChatIds = $chats->pluck('id')->toArray();

        // 1. Agar faol chat tanlangan bo'lsa
        if ($activeChatId && in_array($activeChatId, $userChatIds)) {
            // O'qilgan vaqtni yangilash
            ChatParticipant::where('chat_id', $activeChatId)
                ->where('user_id', $user->id)
                ->update(['last_read_at' => now()]);

            // Boshqa ishtirokchining o'qigan vaqtini olish
            $maxOtherRead = ChatParticipant::where('chat_id', $activeChatId)
                ->where('user_id', '!=', $user->id)
                ->max('last_read_at');

            if ($maxOtherRead) {
                $otherLastReadTimestamp = \Carbon\Carbon::parse($maxOtherRead)->timestamp;
            }

            // Faol chatning onlayn / oflayn holati
            $activeChat = $chats->firstWhere('id', $activeChatId) ?? Chat::with(['participants.user', 'users'])->find($activeChatId);
            if ($activeChat) {
                if ($activeChat->isDirect()) {
                    $otherUser = $activeChat->getOtherUser($user);
                    if ($otherUser) {
                        $isOnline = $otherUser->isOnline();
                        $chatStatus = [
                            'type' => 'direct',
                            'user_id' => $otherUser->id,
                            'is_online' => $isOnline,
                            'status_text' => $otherUser->getLastSeenText(),
                        ];
                    }
                } else {
                    $groupUsers = $activeChat->participants->map->user->filter();
                    $totalCount = $groupUsers->count();
                    $onlineCount = $groupUsers->filter->isOnline()->count();
                    $chatStatus = [
                        'type' => 'group',
                        'total_count' => $totalCount,
                        'online_count' => $onlineCount,
                        'status_text' => "{$totalCount} ta a'zo" . ($onlineCount > 0 ? ", {$onlineCount} ta onlayn" : ''),
                    ];
                }
            }

            // O'chirilgan xabarlar ID sini olish (real-vaqtda boshqa qatnashuvchilar ekranidan ham o'chirish uchun)
            $deletedMessageIds = Message::where('chat_id', $activeChatId)
                ->where('is_deleted', true)
                ->pluck('id')
                ->toArray();

            $messagesQuery = Message::where('chat_id', $activeChatId)
                ->where('is_deleted', false)
                ->with(['user', 'replyTo.user'])
                ->where('user_id', '!=', $user->id);

            if ($lastMessageId > 0) {
                $messagesQuery->where('id', '>', $lastMessageId);
            }

            $rawMessages = $messagesQuery->oldest()->get();

            foreach ($rawMessages as $msg) {
                $replyData = null;
                if ($msg->replyTo) {
                    $replyData = [
                        'id' => $msg->replyTo->id,
                        'user_name' => $msg->replyTo->user ? ($msg->replyTo->user->full_name ?: $msg->replyTo->user->name) : 'Xodim',
                        'body' => $msg->replyTo->body,
                        'snippet' => $msg->replyTo->getSnippet(),
                        'type' => $msg->replyTo->type,
                    ];
                }

                $newMessages[] = [
                    'id' => $msg->id,
                    'chat_id' => $msg->chat_id,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user ? ($msg->user->full_name ?: $msg->user->name) : 'Xodim',
                    'user_avatar' => $msg->user ? $msg->user->avatar_url : asset('assets/images/8214212.png'),
                    'body' => $msg->body,
                    'type' => $msg->type,
                    'file_url' => $msg->file_url,
                    'file_name' => $msg->file_name,
                    'file_size' => $msg->formatted_file_size,
                    'is_image' => $msg->isImage(),
                    'reply_to' => $replyData,
                    'can_delete' => $msg->canDelete($user),
                    'created_at' => $msg->created_at->timezone('Asia/Tashkent')->format('H:i'),
                    'created_at_timestamp' => $msg->created_at->timestamp,
                    'is_me' => false,
                ];
            }
        }

        // 2. Global yangi xabarlar (tizim bo'ylab bildirishnomalar va audio signallar uchun)
        $currentMaxGlobalId = 0;
        if (!empty($userChatIds)) {
            $currentMaxGlobalId = (int) Message::whereIn('chat_id', $userChatIds)->max('id');
        }

        $incomingMessages = [];
        if ($lastGlobalMessageId > 0 && !empty($userChatIds)) {
            $incomingRaw = Message::whereIn('chat_id', $userChatIds)
                ->where('is_deleted', false)
                ->where('id', '>', $lastGlobalMessageId)
                ->where('user_id', '!=', $user->id)
                ->with(['user', 'chat.participants.user', 'chat.users'])
                ->oldest()
                ->get();

            foreach ($incomingRaw as $msg) {
                $msgChat = $msg->chat;
                $snippet = 'Xabar';
                if ($msg->type === 'image') {
                    $snippet = '📷 Rasm yubordi';
                } elseif ($msg->type === 'file') {
                    $snippet = '📎 ' . ($msg->file_name ?: 'Hujjat yubordi');
                } elseif ($msg->body) {
                    $snippet = $msg->body;
                }

                $incomingMessages[] = [
                    'id' => $msg->id,
                    'chat_id' => $msg->chat_id,
                    'chat_name' => $msgChat ? $msgChat->getDisplayName($user) : 'Muloqot',
                    'chat_avatar' => $msgChat ? $msgChat->getDisplayAvatar($user) : asset('assets/images/8214212.png'),
                    'chat_type' => $msgChat ? $msgChat->type : 'direct',
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user ? ($msg->user->full_name ?: $msg->user->name) : 'Xodim',
                    'user_avatar' => $msg->user ? $msg->user->avatar_url : asset('assets/images/8214212.png'),
                    'body' => $msg->body,
                    'type' => $msg->type,
                    'file_name' => $msg->file_name,
                    'snippet' => $snippet,
                    'created_at' => $msg->created_at->timezone('Asia/Tashkent')->format('H:i'),
                    'created_at_timestamp' => $msg->created_at->timestamp,
                ];
            }
        }

        // 3. Har bir chat uchun o'qilmaganlar soni va sidebar ro'yxati ma'lumotlari
        $unreadCounts = [];
        $totalUnread = 0;
        $sidebarChats = [];

        foreach ($chats as $c) {
            $unread = $c->unreadCountFor($user);
            $unreadCounts[$c->id] = $unread;
            $totalUnread += $unread;

            $latest = $c->latestMessage;
            $snippet = 'Hali xabarlar yo\'q';
            $snippetPrefix = '';
            if ($latest) {
                if ($latest->user_id === $user->id) {
                    $snippetPrefix = 'Siz: ';
                } elseif ($c->isGroup() && $latest->user) {
                    $snippetPrefix = explode(' ', $latest->user->name)[0] . ': ';
                }

                if ($latest->type === 'file') {
                    $snippet = '📎 ' . ($latest->file_name ?: 'Hujjat');
                } elseif ($latest->type === 'image') {
                    $snippet = '📷 Rasm';
                } elseif ($latest->type === 'system') {
                    $snippet = $latest->body;
                } else {
                    $snippet = $latest->body;
                }
            }

            $timeText = '';
            if ($c->last_message_at) {
                $ct = $c->last_message_at->timezone('Asia/Tashkent');
                $timeText = $ct->isToday() ? $ct->format('H:i') : ($ct->isYesterday() ? 'Kecha' : $ct->format('d.m'));
            }

            $otherUser = $c->isDirect() ? $c->getOtherUser($user) : null;

            $sidebarChats[] = [
                'id' => $c->id,
                'name' => $c->getDisplayName($user),
                'avatar' => $c->getDisplayAvatar($user),
                'type' => $c->type,
                'other_user_id' => $otherUser?->id,
                'other_user_online' => $otherUser ? $otherUser->isOnline() : false,
                'other_user_last_seen' => $otherUser ? $otherUser->getLastSeenText() : '',
                'snippet' => $snippet,
                'snippet_prefix' => $snippetPrefix,
                'time_text' => $timeText,
                'unread' => $unread,
                'last_message_timestamp' => $c->last_message_at ? $c->last_message_at->timestamp : 0,
            ];
        }

        // Hozirda onlayn bo'lgan barcha xodimlar ID ro'yxati (oxirgi 2 daqiqada faol)
        $threshold = now()->subSeconds(120);
        $onlineUserIds = User::where('last_activity', '>=', $threshold)->pluck('id')->toArray();

        // Barcha xodimlarning so'nggi ko'rilgan holati
        $userStatuses = [];
        $allUsersData = User::select('id', 'last_activity', 'updated_at')->get();
        foreach ($allUsersData as $u) {
            $userStatuses[$u->id] = [
                'is_online' => $u->isOnline(),
                'text' => $u->getLastSeenText(),
            ];
        }

        return response()->json([
            'new_messages' => $newMessages,
            'deleted_ids' => $deletedMessageIds ?? [],
            'incoming_messages' => $incomingMessages,
            'max_global_message_id' => $currentMaxGlobalId,
            'sidebar_chats' => $sidebarChats,
            'unread_counts' => $unreadCounts,
            'total_unread' => $totalUnread,
            'other_last_read_timestamp' => $otherLastReadTimestamp,
            'chat_status' => $chatStatus,
            'online_user_ids' => $onlineUserIds,
            'users_status' => $userStatuses,
        ]);
    }

    /**
     * Hujjatni xavfsiz yuklab olish
     */
    public function download($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Huquqni tekshirish
        $isParticipant = ChatParticipant::where('chat_id', $message->chat_id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isParticipant) {
            abort(403, 'Ushbu faylni yuklab olish huquqingiz yo\'q!');
        }

        if (!$message->file_path || !Storage::disk('public')->exists($message->file_path)) {
            abort(404, 'Fayl topilmadi!');
        }

        return Storage::disk('public')->download($message->file_path, $message->file_name ?: 'hujjat');
    }

    /**
     * Guruh a'zolarini ko'rish va yangi a'zo qo'shish
     */
    public function addMembers(Request $request, $chatId)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $chat = Chat::where('type', 'group')
            ->whereHas('participants', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($chatId);

        foreach ($request->user_ids as $userId) {
            $exists = ChatParticipant::where('chat_id', $chat->id)->where('user_id', $userId)->exists();
            if (!$exists) {
                ChatParticipant::create([
                    'chat_id' => $chat->id,
                    'user_id' => $userId,
                    'role' => 'member',
                ]);

                $u = User::find($userId);
                if ($u) {
                    Message::create([
                        'chat_id' => $chat->id,
                        'user_id' => Auth::id(),
                        'body' => Auth::user()->name . ' yangi a\'zo ' . $u->name . 'ni guruhga qo\'shdi',
                        'type' => 'system',
                    ]);
                }
            }
        }

        return back()->with('success', 'Yangi a\'zolar guruhga qo\'shildi!');
    }

    /**
     * Guruhdan chiqish
     */
    public function leaveGroup($chatId)
    {
        $chat = Chat::where('type', 'group')
            ->whereHas('participants', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($chatId);

        ChatParticipant::where('chat_id', $chat->id)
            ->where('user_id', Auth::id())
            ->delete();

        Message::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'body' => Auth::user()->name . ' guruhdan chiqdi',
            'type' => 'system',
        ]);

        return redirect()->route('chat.index')->with('success', 'Guruhdan chiqdingiz!');
    }
}
