<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                ->with('user')
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
            'file' => 'nullable|file|max:51200', // max 50 MB
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

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'body' => $body ?: null,
            'type' => $type,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_mime' => $fileMime,
        ]);

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
                'created_at' => $message->created_at->format('H:i'),
                'created_at_timestamp' => $message->created_at->timestamp,
                'is_read' => false,
                'is_me' => true,
            ],
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
        $user->updateQuietly(['last_activity' => now()]);

        $activeChatId = (int) $request->active_chat_id;
        $lastMessageId = (int) $request->last_message_id;

        $newMessages = [];
        $otherLastReadTimestamp = 0;
        $chatStatus = null;

        if ($activeChatId) {
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
            $activeChat = Chat::with(['participants.user'])->find($activeChatId);
            if ($activeChat) {
                if ($activeChat->isDirect()) {
                    $otherUser = $activeChat->getOtherUser($user);
                    if ($otherUser) {
                        $isOnline = $otherUser->isOnline();
                        $chatStatus = [
                            'type' => 'direct',
                            'user_id' => $otherUser->id,
                            'is_online' => $isOnline,
                            'status_text' => $isOnline ? 'onlayn' : $otherUser->getLastSeenText(),
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

            $messagesQuery = Message::where('chat_id', $activeChatId)
                ->with('user')
                ->where('user_id', '!=', $user->id);

            if ($lastMessageId > 0) {
                $messagesQuery->where('id', '>', $lastMessageId);
            }

            $rawMessages = $messagesQuery->oldest()->get();

            foreach ($rawMessages as $msg) {
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
                    'created_at' => $msg->created_at->format('H:i'),
                    'created_at_timestamp' => $msg->created_at->timestamp,
                    'is_me' => false,
                ];
            }
        }

        // Har bir chat uchun o'qilmaganlar sonini hisoblash
        $unreadCounts = [];
        $totalUnread = 0;

        $chats = Chat::whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->with(['participants', 'latestMessage'])
            ->get();

        foreach ($chats as $c) {
            $unread = $c->unreadCountFor($user);
            $unreadCounts[$c->id] = $unread;
            $totalUnread += $unread;
        }

        // Hozirda onlayn bo'lgan barcha xodimlar ID ro'yxati (oxirgi 2 daqiqada faol)
        $threshold = now()->subSeconds(120);
        $onlineUserIds = User::where('last_activity', '>=', $threshold)->pluck('id')->toArray();

        return response()->json([
            'new_messages' => $newMessages,
            'unread_counts' => $unreadCounts,
            'total_unread' => $totalUnread,
            'other_last_read_timestamp' => $otherLastReadTimestamp,
            'chat_status' => $chatStatus,
            'online_user_ids' => $onlineUserIds,
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
