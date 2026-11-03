@extends('layouts.admin')

@section('title', 'Ichki muloqot va hujjat almashish')

@section('content')
<div class="nxl-content p-0">
    <div class="chat-wrapper">
        <!-- ==================== 1. SIDEBAR (CHATLAR VA FOYDALANUVCHILAR RO'YXATI) ==================== -->
        <aside class="chat-sidebar">
            <!-- Sidebar Header -->
            <div class="chat-sidebar-header">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="chat-logo-icon">
                            <i class="feather-message-square"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 fs-16 text-dark">Muloqot & Hujjatlar</h5>
                            <small class="text-muted fs-11">Institut ichki tarmog'i</small>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-soft-primary rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#newDirectModal" title="Yangi shaxsiy yozishma">
                            <i class="feather-user-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#newGroupModal" title="Yangi guruh yaratish">
                            <i class="feather-users"></i>
                        </button>
                    </div>
                </div>

                <!-- Search box -->
                <div class="chat-search-box">
                    <i class="feather-search search-icon"></i>
                    <input type="text" id="chatSearchInput" class="form-control" placeholder="Yozishma yoki xodimni qidirish..." autocomplete="off">
                </div>

                <!-- Filter Tabs -->
                <div class="chat-filter-tabs">
                    <button type="button" class="chat-tab-btn active" data-filter="all">Barchasi</button>
                    <button type="button" class="chat-tab-btn" data-filter="direct">Shaxsiy</button>
                    <button type="button" class="chat-tab-btn" data-filter="group">Guruhlar</button>
                </div>
            </div>

            <!-- Chat List -->
            <div class="chat-list" id="chatListContainer">
                @forelse($chats as $chat)
                    @php
                        $isActive = $activeChat && $activeChat->id === $chat->id;
                        $unread = $chat->unreadCountFor();
                        $latest = $chat->latestMessage;
                    @endphp
                    <a href="{{ route('chat.index', ['chat_id' => $chat->id]) }}" 
                       class="chat-item {{ $isActive ? 'active' : '' }}" 
                       data-chat-id="{{ $chat->id }}"
                       data-type="{{ $chat->type }}"
                       data-name="{{ mb_strtolower($chat->getDisplayName()) }}">
                        <div class="chat-item-avatar-wrapper">
                            @if($chat->isGroup())
                                <div class="chat-group-avatar">
                                    <i class="feather-users"></i>
                                </div>
                            @else
                                <img src="{{ $chat->getDisplayAvatar() }}" alt="" class="chat-item-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                            @endif
                        </div>
                        <div class="chat-item-info">
                            <div class="chat-item-header">
                                <h6 class="chat-item-name text-truncate">{{ $chat->getDisplayName() }}</h6>
                                <span class="chat-item-time">
                                    @if($chat->last_message_at)
                                        {{ $chat->last_message_at->isToday() ? $chat->last_message_at->format('H:i') : ($chat->last_message_at->isYesterday() ? 'Kecha' : $chat->last_message_at->format('d.m')) }}
                                    @endif
                                </span>
                            </div>
                            <div class="chat-item-snippet">
                                <p class="chat-item-last-msg text-truncate mb-0">
                                    @if($latest)
                                        @if($latest->user_id === Auth::id())
                                            <span class="text-primary fw-semibold">Siz: </span>
                                        @elseif($chat->isGroup() && $latest->user)
                                            <span class="text-dark fw-semibold">{{ explode(' ', $latest->user->name)[0] }}: </span>
                                        @endif

                                        @if($latest->type === 'file')
                                            <span>📎 {{ $latest->file_name ?: 'Hujjat' }}</span>
                                        @elseif($latest->type === 'image')
                                            <span>📷 Rasm</span>
                                        @elseif($latest->type === 'system')
                                            <span class="fst-italic">{{ $latest->body }}</span>
                                        @else
                                            <span>{{ $latest->body }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">Hali xabarlar yo'q</span>
                                    @endif
                                </p>
                                <span class="badge bg-primary rounded-pill chat-unread-badge {{ $unread > 0 ? '' : 'd-none' }}" id="unreadBadge_{{ $chat->id }}">
                                    {{ $unread }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 px-3 text-muted">
                        <div class="fs-40 mb-2">💬</div>
                        <h6 class="fs-14 fw-bold text-dark">Hozircha yozishmalar yo'q</h6>
                        <p class="fs-12 mb-3">Xodimlar bilan shaxsiy muloqot yoki umumiy guruh boshlang!</p>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newDirectModal">
                            <i class="feather-plus me-1"></i> Suhbat boshlash
                        </button>
                    </div>
                @endforelse
            </div>
        </aside>

        <!-- ==================== 2. MAIN CHAT AREA (YOZISHMA VA HUJJATLAR) ==================== -->
        <main class="chat-main">
            @if($activeChat)
                <!-- Chat Top Header -->
                <div class="chat-main-header">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        @if($activeChat->isGroup())
                            <div class="chat-group-avatar-lg">
                                <i class="feather-users"></i>
                            </div>
                        @else
                            <img src="{{ $activeChat->getDisplayAvatar() }}" alt="" class="chat-main-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                        @endif
                        <div class="min-w-0">
                            <h5 class="fw-bold mb-0 text-truncate text-dark fs-16">{{ $activeChat->getDisplayName() }}</h5>
                            <small class="text-muted fs-12 d-block text-truncate">
                                @if($activeChat->isGroup())
                                    <span class="text-primary fw-semibold"><i class="feather-users fs-11 me-1"></i>{{ $activeChat->participants->count() }} ta a'zo</span>
                                    @if($activeChat->created_by === Auth::id())
                                        <span class="badge bg-soft-info text-info ms-1">Administrator</span>
                                    @endif
                                @else
                                    @php $otherUser = $activeChat->getOtherUser(); @endphp
                                    <span>{{ $otherUser->position ?? 'Xodim' }}</span>
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if($activeChat->isGroup())
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#groupInfoModal">
                                <i class="feather-info"></i>
                                <span class="d-none d-sm-inline">Guruh a'zolari</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="chat-messages-scroll" id="messagesContainer">
                    <div class="messages-inner" id="messagesInner">
                        @php $lastDate = null; @endphp
                        @foreach($messages as $msg)
                            @php
                                $msgDate = $msg->created_at->format('Y-m-d');
                                $isMe = $msg->user_id === Auth::id();
                            @endphp

                            @if($lastDate !== $msgDate)
                                @php $lastDate = $msgDate; @endphp
                                <div class="chat-day-divider">
                                    <span>
                                        @if($msg->created_at->isToday())
                                            Bugun
                                        @elseif($msg->created_at->isYesterday())
                                            Kecha
                                        @else
                                            {{ $msg->created_at->format('d.m.Y') }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if($msg->type === 'system')
                                <div class="chat-system-msg">
                                    <span>{{ $msg->body }}</span>
                                </div>
                            @else
                                <div class="message-row {{ $isMe ? 'message-out' : 'message-in' }}" data-message-id="{{ $msg->id }}">
                                    @if(!$isMe)
                                        <img src="{{ $msg->user ? $msg->user->avatar_url : asset('assets/images/8214212.png') }}" alt="" class="message-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                    @endif
                                    <div class="message-bubble">
                                        @if(!$isMe && $activeChat->isGroup() && $msg->user)
                                            <div class="message-sender-name">{{ $msg->user->name }}</div>
                                        @endif

                                        {{-- 1. Image preview --}}
                                        @if($msg->isImage() && $msg->file_url)
                                            <div class="message-image-box">
                                                <a href="{{ $msg->file_url }}" target="_blank">
                                                    <img src="{{ $msg->file_url }}" alt="{{ $msg->file_name }}" class="message-img-preview">
                                                </a>
                                            </div>
                                        @endif

                                        {{-- 2. Document card --}}
                                        @if($msg->isFile() && !$msg->isImage() && $msg->file_url)
                                            <div class="message-doc-card">
                                                <div class="doc-icon">
                                                    @php
                                                        $ext = strtolower(pathinfo($msg->file_name, PATHINFO_EXTENSION));
                                                    @endphp
                                                    @if(in_array($ext, ['pdf']))
                                                        📄
                                                    @elseif(in_array($ext, ['doc', 'docx']))
                                                        📝
                                                    @elseif(in_array($ext, ['xls', 'xlsx']))
                                                        📊
                                                    @elseif(in_array($ext, ['zip', 'rar']))
                                                        📦
                                                    @else
                                                        📎
                                                    @endif
                                                </div>
                                                <div class="doc-info min-w-0">
                                                    <div class="doc-name text-truncate" title="{{ $msg->file_name }}">{{ $msg->file_name }}</div>
                                                    <div class="doc-meta">{{ $msg->formatted_file_size }}</div>
                                                </div>
                                                <a href="{{ route('chat.download', $msg->id) }}" class="btn-doc-download" title="Yuklab olish">
                                                    <i class="feather-download"></i>
                                                </a>
                                            </div>
                                        @endif

                                        {{-- 3. Text body --}}
                                        @if(!empty($msg->body))
                                            <div class="message-text">{!! nl2br(e($msg->body)) !!}</div>
                                        @endif

                                        {{-- Message metadata --}}
                                        <div class="message-meta">
                                            <span class="message-time">{{ $msg->created_at->format('H:i') }}</span>
                                            @if($isMe)
                                                <span class="message-status text-primary">✓✓</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Chat Input Bar -->
                <div class="chat-input-area">
                    <!-- Selected file pill preview -->
                    <div id="fileAttachmentPreview" class="file-attachment-preview d-none">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-18" id="previewFileIcon">📎</span>
                            <div class="min-w-0">
                                <div class="fw-bold text-dark fs-12 text-truncate" id="previewFileName">hujjat.pdf</div>
                                <div class="text-muted fs-11" id="previewFileSize">1.2 MB</div>
                            </div>
                        </div>
                        <button type="button" class="btn-remove-attachment" onclick="clearSelectedFile()" title="Bekor qilish">
                            <i class="feather-x"></i>
                        </button>
                    </div>

                    <form id="chatSendForm" class="chat-form" onsubmit="handleSendMessage(event)">
                        @csrf
                        <input type="file" id="chatFileInput" class="d-none" onchange="handleFileSelected(event)">

                        <button type="button" class="btn-chat-action" onclick="document.getElementById('chatFileInput').click()" title="Hujjat yoki rasm biriktirish (PDF, Word, Excel, ZIP)">
                            <i class="feather-paperclip"></i>
                        </button>

                        <div class="chat-textarea-box">
                            <textarea id="chatMessageInput" rows="1" placeholder="Xabar yozing yoki fayl biriktiring... (Enter — yuborish)" autocomplete="off"></textarea>
                        </div>

                        <button type="submit" class="btn-chat-send" id="btnSubmitMessage" title="Yuborish">
                            <i class="feather-send"></i>
                        </button>
                    </form>
                </div>

            @else
                <!-- No active chat selected -->
                <div class="chat-empty-state">
                    <div class="empty-icon-cloud">
                        <i class="feather-send"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Muloqot va Hujjat almashish</h4>
                    <p class="text-muted max-w-400 mb-4">
                        Kriminologiya tadqiqot instituti xodimlari bilan shaxsiy muloqot qiling yoki bo'limlar bo'yicha umumiy guruhlar yarating.
                    </p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newDirectModal">
                            <i class="feather-user-plus me-1"></i> Shaxsiy yozishma
                        </button>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                            <i class="feather-users me-1"></i> Yangi guruh
                        </button>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>

<!-- ==================== MODAL 1: YANGI SHAXSIY YOZISHMA ==================== -->
<div class="modal fade" id="newDirectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold fs-16 d-flex align-items-center gap-2">
                    <i class="feather-user-plus text-primary"></i> Yangi shaxsiy yozishma
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <input type="text" id="directUserSearch" class="form-control rounded-pill" placeholder="Xodim ismi yoki lavozimi bo'yicha qidirish..." oninput="filterDirectUsers(this.value)">
                </div>
                <div class="direct-user-list" style="max-height: 340px; overflow-y: auto;">
                    @foreach($allUsers as $u)
                        <form action="{{ route('chat.start_direct') }}" method="POST" class="direct-user-item p-2 mb-1 rounded-3 d-flex align-items-center justify-content-between hover-bg">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                            <div class="d-flex align-items-center gap-2.5 min-w-0">
                                <img src="{{ $u->avatar_url }}" alt="" class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark fs-13 text-truncate">{{ $u->full_name ?: $u->name }}</div>
                                    <small class="text-muted fs-11 text-truncate d-block">{{ $u->position ?: 'Xodim' }}</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                                Yozish
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL 2: YANGI GURUH YARATISH ==================== -->
<div class="modal fade" id="newGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('chat.create_group') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom pb-3">
                    <h5 class="modal-title fw-bold fs-16 d-flex align-items-center gap-2">
                        <i class="feather-users text-primary"></i> Yangi guruh yaratish
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-13">Guruh nomi <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Masalan: Kriminologiya ilmiy bo'limi" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold fs-13">A'zolarni tanlang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill mb-2" placeholder="Xodimlarni qidirish..." oninput="filterGroupUsers(this.value)">
                        <div class="group-user-list border rounded-3 p-2" style="max-height: 220px; overflow-y: auto;">
                            @foreach($allUsers as $u)
                                <label class="d-flex align-items-center justify-content-between p-2 rounded hover-bg cursor-pointer group-user-row">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <img src="{{ $u->avatar_url }}" alt="" class="rounded-circle object-fit-cover shadow-sm" style="width: 34px; height: 34px;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark fs-13 text-truncate">{{ $u->full_name ?: $u->name }}</div>
                                            <small class="text-muted fs-11 text-truncate d-block">{{ $u->position ?: 'Xodim' }}</small>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" class="form-check-input ms-2">
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="feather-check me-1"></i> Guruh yaratish
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($activeChat && $activeChat->isGroup())
<!-- ==================== MODAL 3: GURUH A'ZOLARI VA MA'LUMOTLARI ==================== -->
<div class="modal fade" id="groupInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold fs-16 d-flex align-items-center gap-2">
                    <i class="feather-users text-primary"></i> «{{ $activeChat->name }}» a'zolari
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <h6 class="fs-12 text-uppercase text-muted fw-bold mb-2">Guruh ishtirokchilari ({{ $activeChat->participants->count() }} nafar):</h6>
                    <div class="list-group list-group-flush border rounded-3 p-1" style="max-height: 240px; overflow-y: auto;">
                        @foreach($activeChat->participants as $p)
                            <div class="list-group-item d-flex align-items-center justify-content-between p-2 border-0">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <img src="{{ $p->user ? $p->user->avatar_url : asset('assets/images/8214212.png') }}" alt="" class="rounded-circle object-fit-cover shadow-sm" style="width: 36px; height: 36px;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark fs-13 text-truncate">{{ $p->user ? ($p->user->full_name ?: $p->user->name) : 'Xodim' }}</div>
                                        <small class="text-muted fs-11">{{ $p->user ? $p->user->position : '' }}</small>
                                    </div>
                                </div>
                                @if($p->role === 'admin')
                                    <span class="badge bg-soft-primary text-primary fs-11">Admin</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Yangi a'zo qo'shish -->
                <form action="{{ route('chat.add_members', $activeChat->id) }}" method="POST" class="mt-3 pt-3 border-top">
                    @csrf
                    <label class="form-label fw-bold fs-12 text-uppercase text-muted">Yangi xodimlarni guruhga qo'shish:</label>
                    <select name="user_ids[]" class="form-select mb-2" multiple style="height: 110px;">
                        @php
                            $existingUserIds = $activeChat->participants->pluck('user_id')->toArray();
                        @endphp
                        @foreach($allUsers as $u)
                            @if(!in_array($u->id, $existingUserIds))
                                <option value="{{ $u->id }}">{{ $u->full_name ?: $u->name }} ({{ $u->position ?: 'Xodim' }})</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="feather-plus me-1"></i> A'zo qo'shish
                    </button>
                </form>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between">
                <form action="{{ route('chat.leave', $activeChat->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham ushbu guruhdan chiqmoqchimisiz?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="feather-log-out me-1"></i> Guruhdan chiqish
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Yopish</button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
/* ==================== TELEGRAM-LIKE CHAT STYLES ==================== */
.chat-wrapper {
    display: flex;
    height: calc(100vh - 110px);
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin: 15px;
    border: 1px solid #e2e8f0;
}

/* SIDEBAR */
.chat-sidebar {
    width: 360px;
    min-width: 320px;
    border-end: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    background: #f8fafc;
}

.chat-sidebar-header {
    padding: 18px 16px 12px;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
}

.chat-logo-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
}

.chat-search-box {
    position: relative;
    margin-bottom: 12px;
}

.chat-search-box .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}

.chat-search-box .form-control {
    padding-left: 38px;
    border-radius: 50px;
    background: #f1f5f9;
    border: 1px solid transparent;
    font-size: 13px;
    transition: all 0.2s ease;
}

.chat-search-box .form-control:focus {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.chat-filter-tabs {
    display: flex;
    gap: 6px;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 50px;
}

.chat-tab-btn {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    padding: 5px 0;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.chat-tab-btn.active {
    background: #ffffff;
    color: #1e293b;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

/* Chat List Items */
.chat-list {
    flex: 1;
    overflow-y: auto;
    padding: 8px;
}

.chat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    color: inherit;
    text-decoration: none;
    margin-bottom: 4px;
    transition: background 0.18s ease;
}

.chat-item:hover {
    background: #e2e8f0;
}

.chat-item.active {
    background: #e0f2fe;
}

.chat-item-avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}

.chat-item-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.chat-group-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.chat-group-avatar-lg {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.chat-item-info {
    flex: 1;
    min-width: 0;
}

.chat-item-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 3px;
}

.chat-item-name {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.chat-item-time {
    font-size: 11px;
    color: #94a3b8;
    white-space: nowrap;
}

.chat-item-snippet {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-item-last-msg {
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.3;
}

.chat-unread-badge {
    font-size: 10px;
    padding: 3px 7px;
    background: #3b82f6 !important;
}

/* MAIN CHAT AREA */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f1f5f9;
    position: relative;
}

.chat-main-header {
    padding: 12px 20px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 2;
}

.chat-main-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

/* Messages Scroll Area */
.chat-messages-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background-color: #e5edf5;
    background-image: radial-gradient(rgba(148, 163, 184, 0.25) 1px, transparent 0);
    background-size: 20px 20px;
}

.messages-inner {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 900px;
    margin: 0 auto;
}

/* Day Divider */
.chat-day-divider {
    text-align: center;
    margin: 12px 0;
}
.chat-day-divider span {
    background: rgba(15, 23, 42, 0.55);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 50px;
    backdrop-filter: blur(4px);
}

/* System Message */
.chat-system-msg {
    text-align: center;
    margin: 8px 0;
}
.chat-system-msg span {
    background: rgba(226, 232, 240, 0.85);
    color: #475569;
    font-size: 11.5px;
    padding: 4px 14px;
    border-radius: 50px;
}

/* Message Rows */
.message-row {
    display: flex;
    gap: 8px;
    max-width: 75%;
}

.message-in {
    align-self: flex-start;
}

.message-out {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    align-self: flex-end;
}

.message-bubble {
    position: relative;
    padding: 8px 12px 6px;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    word-break: break-word;
}

.message-in .message-bubble {
    background: #ffffff;
    color: #0f172a;
    border-bottom-left-radius: 4px;
}

.message-out .message-bubble {
    background: #dbeafe;
    color: #0f172a;
    border-bottom-right-radius: 4px;
}

.message-sender-name {
    font-size: 11px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 2px;
}

.message-text {
    font-size: 14px;
    line-height: 1.45;
}

.message-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    margin-top: 2px;
    font-size: 10.5px;
    color: #64748b;
}

/* Document Card */
.message-doc-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(0, 0, 0, 0.04);
    padding: 8px 12px;
    border-radius: 10px;
    margin-bottom: 4px;
    min-width: 220px;
    max-width: 320px;
}

.doc-icon {
    font-size: 24px;
}

.doc-info {
    flex: 1;
}

.doc-name {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
}

.doc-meta {
    font-size: 11px;
    color: #64748b;
}

.btn-doc-download {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #3b82f6;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    transition: transform 0.15s ease;
}

.btn-doc-download:hover {
    transform: scale(1.1);
    color: #ffffff;
}

/* Image Preview */
.message-image-box {
    margin-bottom: 4px;
    border-radius: 10px;
    overflow: hidden;
}

.message-img-preview {
    max-width: 280px;
    max-height: 240px;
    border-radius: 10px;
    object-fit: cover;
    display: block;
    cursor: pointer;
    transition: opacity 0.2s;
}

.message-img-preview:hover {
    opacity: 0.95;
}

/* Input Area */
.chat-input-area {
    background: #ffffff;
    padding: 12px 18px;
    border-top: 1px solid #e2e8f0;
}

.file-attachment-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 8px 14px;
    margin-bottom: 8px;
}

.btn-remove-attachment {
    background: transparent;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 16px;
}

.chat-form {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.btn-chat-action {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #f1f5f9;
    border: none;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.btn-chat-action:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.chat-textarea-box {
    flex: 1;
    background: #f1f5f9;
    border-radius: 24px;
    padding: 6px 16px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.chat-textarea-box:focus-within {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

#chatMessageInput {
    width: 100%;
    border: none;
    background: transparent;
    outline: none;
    resize: none;
    max-height: 120px;
    font-size: 14px;
    line-height: 1.4;
    padding-top: 4px;
    color: #1e293b;
}

.btn-chat-send {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    transition: transform 0.15s ease;
    flex-shrink: 0;
}

.btn-chat-send:hover {
    transform: scale(1.05);
}

.btn-chat-send:active {
    transform: scale(0.95);
}

/* Empty State */
.chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
}

.empty-icon-cloud {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #e0f2fe;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    margin-bottom: 18px;
}

.max-w-400 {
    max-width: 400px;
}

.hover-bg:hover {
    background: #f1f5f9;
}
.cursor-pointer {
    cursor: pointer;
}

@media (max-width: 768px) {
    .chat-wrapper {
        margin: 0;
        height: calc(100vh - 70px);
        border-radius: 0;
    }
    .chat-sidebar {
        width: 100%;
        min-width: 100%;
    }
    .chat-main {
        display: none;
    }
    .chat-wrapper.show-mobile-chat .chat-sidebar {
        display: none;
    }
    .chat-wrapper.show-mobile-chat .chat-main {
        display: flex;
    }
}
</style>

<script>
let activeChatId = {{ $activeChat ? $activeChat->id : 'null' }};
let lastMessageId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};
let selectedFile = null;
let pollInterval = null;

// Avtomatik pastga tushirish (Scroll to bottom)
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

// Fayl tanlanganda
function handleFileSelected(event) {
    const file = event.target.files[0];
    if (!file) return;

    selectedFile = file;
    const preview = document.getElementById('fileAttachmentPreview');
    const nameEl = document.getElementById('previewFileName');
    const sizeEl = document.getElementById('previewFileSize');
    const iconEl = document.getElementById('previewFileIcon');

    if (preview && nameEl && sizeEl) {
        nameEl.innerText = file.name;
        sizeEl.innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        
        if (file.type.startsWith('image/')) {
            iconEl.innerText = '📷';
        } else if (file.name.endsWith('.pdf')) {
            iconEl.innerText = '📄';
        } else if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
            iconEl.innerText = '📝';
        } else if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
            iconEl.innerText = '📊';
        } else {
            iconEl.innerText = '📎';
        }

        preview.classList.remove('d-none');
    }
}

// Fayl tanlovini bekor qilish
function clearSelectedFile() {
    selectedFile = null;
    const fileInput = document.getElementById('chatFileInput');
    if (fileInput) fileInput.value = '';
    const preview = document.getElementById('fileAttachmentPreview');
    if (preview) preview.classList.add('d-none');
}

// Xabar yuborish
async function handleSendMessage(event) {
    event.preventDefault();
    if (!activeChatId) return;

    const input = document.getElementById('chatMessageInput');
    const body = input ? input.value.trim() : '';

    if (!body && !selectedFile) {
        return;
    }

    const formData = new FormData();
    if (body) formData.append('body', body);
    if (selectedFile) formData.append('file', selectedFile);

    // Disable input while sending
    const submitBtn = document.getElementById('btnSubmitMessage');
    if (submitBtn) submitBtn.disabled = true;

    try {
        const response = await fetch(`{{ route('chat.index') }}/${activeChatId}/send`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });

        const res = await response.json();

        if (res.success && res.message) {
            appendMessageToUI(res.message);
            if (input) {
                input.value = '';
                input.style.height = 'auto';
            }
            clearSelectedFile();
            lastMessageId = Math.max(lastMessageId, res.message.id);
            scrollToBottom();
        } else if (res.error) {
            alert(res.error);
        }
    } catch (e) {
        console.error('Xabar yuborishda xatolik:', e);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// UI ga yangi xabar qo'shish
function appendMessageToUI(msg) {
    const inner = document.getElementById('messagesInner');
    if (!inner) return;

    const row = document.createElement('div');
    row.className = `message-row ${msg.is_me ? 'message-out' : 'message-in'}`;
    row.setAttribute('data-message-id', msg.id);

    let avatarHTML = '';
    if (!msg.is_me) {
        avatarHTML = `<img src="${msg.user_avatar}" alt="" class="message-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">`;
    }

    let senderNameHTML = '';
    if (!msg.is_me && msg.user_name) {
        senderNameHTML = `<div class="message-sender-name">${escapeHtml(msg.user_name)}</div>`;
    }

    let mediaHTML = '';
    if (msg.file_url) {
        if (msg.is_image) {
            mediaHTML = `
                <div class="message-image-box">
                    <a href="${msg.file_url}" target="_blank">
                        <img src="${msg.file_url}" alt="" class="message-img-preview">
                    </a>
                </div>
            `;
        } else {
            mediaHTML = `
                <div class="message-doc-card">
                    <div class="doc-icon">📎</div>
                    <div class="doc-info min-w-0">
                        <div class="doc-name text-truncate" title="${escapeHtml(msg.file_name)}">${escapeHtml(msg.file_name)}</div>
                        <div class="doc-meta">${msg.file_size}</div>
                    </div>
                    <a href="{{ route('chat.index') }}/download/${msg.id}" class="btn-doc-download" title="Yuklab olish">
                        <i class="feather-download"></i>
                    </a>
                </div>
            `;
        }
    }

    let bodyHTML = '';
    if (msg.body) {
        bodyHTML = `<div class="message-text">${escapeHtml(msg.body).replace(/\n/g, '<br>')}</div>`;
    }

    const checkmarks = msg.is_me ? '<span class="message-status text-primary">✓✓</span>' : '';

    row.innerHTML = `
        ${avatarHTML}
        <div class="message-bubble">
            ${senderNameHTML}
            ${mediaHTML}
            ${bodyHTML}
            <div class="message-meta">
                <span class="message-time">${msg.created_at}</span>
                ${checkmarks}
            </div>
        </div>
    `;

    inner.appendChild(row);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
}

// Real-time yangilanishlarni so'rash (Polling har 3 soniyada)
async function pollChatUpdates() {
    try {
        const url = `{{ route('chat.poll') }}?active_chat_id=${activeChatId || ''}&last_message_id=${lastMessageId || 0}`;
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();

        // 1. Yangi xabarlar bo'lsa qo'shish
        if (data.new_messages && data.new_messages.length > 0) {
            let hasNew = false;
            data.new_messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    appendMessageToUI(msg);
                    lastMessageId = msg.id;
                    hasNew = true;
                }
            });
            if (hasNew) {
                scrollToBottom();
            }
        }

        // 2. O'qilmaganlar sonini yangilash
        if (data.unread_counts) {
            for (const [cId, count] of Object.entries(data.unread_counts)) {
                const badge = document.getElementById(`unreadBadge_${cId}`);
                if (badge) {
                    badge.innerText = count;
                    if (count > 0 && cId != activeChatId) {
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                }
            }
        }
    } catch (e) {
        // Silent catch for network hiccups
    }
}

// Enter bosilganda yuborish, Shift+Enter bosilganda yangi qator
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();

    const input = document.getElementById('chatMessageInput');
    if (input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chatSendForm').dispatchEvent(new Event('submit'));
            }
        });

        // Auto-expand textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Polling start
    pollInterval = setInterval(pollChatUpdates, 3000);

    // Filter tabs
    const tabBtns = document.querySelectorAll('.chat-tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            const items = document.querySelectorAll('.chat-item');
            items.forEach(item => {
                const type = item.getAttribute('data-type');
                if (filter === 'all' || type === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Chat search
    const searchInput = document.getElementById('chatSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.chat-item');
            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                if (!query || name.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});

// Modal ichida xodimlarni qidirish
function filterDirectUsers(val) {
    val = val.toLowerCase().trim();
    document.querySelectorAll('.direct-user-item').forEach(el => {
        const text = el.innerText.toLowerCase();
        el.style.display = (!val || text.includes(val)) ? 'flex' : 'none';
    });
}

function filterGroupUsers(val) {
    val = val.toLowerCase().trim();
    document.querySelectorAll('.group-user-row').forEach(el => {
        const text = el.innerText.toLowerCase();
        el.style.display = (!val || text.includes(val)) ? 'flex' : 'none';
    });
}
</script>
@endsection
