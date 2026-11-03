@extends('layouts.admin')

@section('title', 'Ichki muloqot va hujjat almashish')

@section('content')
<div class="nxl-content p-0 m-0">
    <div class="chat-wrapper">
        <!-- ==================== 1. SIDEBAR (CHATLAR VA REAL-TIME XODIMLAR QIDIRUVI) ==================== -->
        <aside class="chat-sidebar">
            <!-- Sidebar Header -->
            <div class="chat-sidebar-header">
                <div class="d-flex align-items-center justify-content-between mb-2.5">
                    <div class="d-flex align-items-center gap-2">
                        <div class="chat-logo-icon">
                            <i class="feather-message-square"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 fs-16 text-dark">Muloqot & Hujjatlar</h5>
                            <small class="text-muted fs-11">Institut ichki tarmog'i</small>
                        </div>
                    </div>
                    <div class="d-flex gap-1.5 align-items-center">
                        <button type="button" class="btn btn-sm btn-soft-secondary rounded-circle p-2" onclick="testNotificationSound()" title="Ovozli signalni sinab ko'rish">
                            <i class="feather-volume-2 text-primary" id="btnSoundIcon"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-soft-primary rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#newDirectModal" title="Yangi shaxsiy yozishma">
                            <i class="feather-user-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#newGroupModal" title="Yangi guruh yaratish">
                            <i class="feather-users"></i>
                        </button>
                    </div>
                </div>

                <!-- Real-time Search Box -->
                <div class="chat-search-box">
                    <i class="feather-search search-icon"></i>
                    <input type="text" id="chatSearchInput" class="form-control" placeholder="Xodimlarni qidirish (ism, familiya, lavozim)..." autocomplete="off">
                    <button type="button" id="btnClearChatSearch" class="btn-clear-search d-none" onclick="clearSidebarSearch()" title="Tozalash">
                        <i class="feather-x"></i>
                    </button>
                </div>

                <!-- Filter Tabs -->
                <div class="chat-filter-tabs" id="chatFilterTabs">
                    <button type="button" class="chat-tab-btn active" data-filter="all">Barchasi</button>
                    <button type="button" class="chat-tab-btn" data-filter="direct">Shaxsiy</button>
                    <button type="button" class="chat-tab-btn" data-filter="group">Guruhlar</button>
                </div>
            </div>

            <!-- Normal Chat List -->
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
                                @php $chatUser = $chat->getOtherUser(); @endphp
                                <img src="{{ $chat->getDisplayAvatar() }}" alt="" class="chat-item-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                @if($chatUser)
                                    <span class="user-status-indicator {{ $chatUser->isOnline() ? 'online' : '' }}" data-user-status-id="{{ $chatUser->id }}" title="{{ $chatUser->isOnline() ? 'Onlayn' : $chatUser->getLastSeenText() }}"></span>
                                @endif
                            @endif
                        </div>
                        <div class="chat-item-info">
                            <div class="chat-item-header">
                                <h6 class="chat-item-name text-truncate">{{ $chat->getDisplayName() }}</h6>
                                <span class="chat-item-time">
                                    @if($chat->last_message_at)
                                        @php $chatTime = $chat->last_message_at->timezone('Asia/Tashkent'); @endphp
                                        {{ $chatTime->isToday() ? $chatTime->format('H:i') : ($chatTime->isYesterday() ? 'Kecha' : $chatTime->format('d.m')) }}
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
                    <div class="text-center py-5 px-3 text-muted" id="chatEmptyPlaceholder">
                        <div class="fs-40 mb-2">💬</div>
                        <h6 class="fs-14 fw-bold text-dark">Hozircha yozishmalar yo'q</h6>
                        <p class="fs-12 mb-3">Xodimlar bilan shaxsiy muloqot yoki umumiy guruh boshlang!</p>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newDirectModal">
                            <i class="feather-plus me-1"></i> Suhbat boshlash
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Real-Time Search Results Panel (Qidiruv paytida chiqadi) -->
            <div class="chat-search-results d-none" id="sidebarSearchResults">
                <!-- Section 1: Matching existing chats -->
                <div id="searchExistingSection" class="d-none">
                    <div class="search-category-title">Mavjud suhbatlar</div>
                    <div id="searchExistingList"></div>
                </div>

                <!-- Section 2: All Institute Employees (Global xodimlar qidiruvi) -->
                <div id="searchGlobalSection">
                    <div class="search-category-title">Institut xodimlari</div>
                    <div id="searchGlobalList">
                        @foreach($allUsers as $u)
                            <form action="{{ route('chat.start_direct') }}" method="POST" class="search-user-item" 
                                  data-search-text="{{ mb_strtolower($u->full_name . ' ' . $u->name . ' ' . $u->position . ' ' . $u->email) }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $u->id }}">
                                <div class="position-relative flex-shrink-0">
                                    <img src="{{ $u->avatar_url }}" alt="" class="chat-item-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                    <span class="user-status-indicator {{ $u->isOnline() ? 'online' : '' }}" data-user-status-id="{{ $u->id }}"></span>
                                </div>
                                <div class="chat-item-info min-w-0">
                                    <div class="chat-item-name text-truncate">{{ $u->full_name ?: $u->name }}</div>
                                    <small class="text-muted fs-11 text-truncate d-block">
                                        <span class="user-status-text" data-user-text-id="{{ $u->id }}">
                                            @if($u->isOnline())
                                                <span class="text-success fw-semibold">onlayn</span>
                                            @else
                                                <span>{{ $u->getLastSeenText() }}</span>
                                            @endif
                                        </span>
                                        @if($u->position)
                                            · {{ $u->position }}
                                        @endif
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 flex-shrink-0">
                                    Yozish
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

                <!-- No results -->
                <div id="searchNoResults" class="text-center py-4 text-muted d-none">
                    <i class="feather-search fs-24 mb-2 d-block"></i>
                    <span class="fs-13">Mos keluvchi xodim topilmadi</span>
                </div>
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
                            @php $otherUser = $activeChat->getOtherUser(); @endphp
                            <div class="position-relative flex-shrink-0">
                                <img src="{{ $activeChat->getDisplayAvatar() }}" alt="" class="chat-main-avatar" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                @if($otherUser)
                                    <span class="user-status-indicator {{ $otherUser->isOnline() ? 'online' : '' }}" data-user-status-id="{{ $otherUser->id }}"></span>
                                @endif
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h5 class="fw-bold mb-0 text-truncate text-dark fs-15">{{ $activeChat->getDisplayName() }}</h5>
                            <div class="fs-12 d-block text-truncate" id="activeChatStatusContainer">
                                @if($activeChat->isGroup())
                                    @php
                                        $groupUsers = $activeChat->participants->map->user->filter();
                                        $totalCount = $groupUsers->count();
                                        $onlineCount = $groupUsers->filter->isOnline()->count();
                                    @endphp
                                    <span class="text-primary fw-semibold" id="activeChatGroupText">
                                        <i class="feather-users fs-11 me-1"></i>{{ $totalCount }} ta a'zo{{ $onlineCount > 0 ? ", {$onlineCount} ta onlayn" : '' }}
                                    </span>
                                    @if($activeChat->created_by === Auth::id())
                                        <span class="badge bg-soft-info text-info ms-1">Administrator</span>
                                    @endif
                                @else
                                    @if($otherUser)
                                        <span id="activeChatUserStatus">
                                            @if($otherUser->isOnline())
                                                <span class="text-success fw-semibold d-inline-flex align-items-center gap-1.5">
                                                    <span class="online-status-dot pulse"></span> onlayn
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    {{ $otherUser->getLastSeenText() }}
                                                </span>
                                            @endif
                                        </span>
                                        @if($otherUser->position)
                                            <span class="text-muted ms-1">· {{ $otherUser->position }}</span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <!-- PWA Install button -->
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 d-none align-items-center gap-1.5" id="btnPwaInstall" title="Ilovani o'rnatish">
                            <i class="feather-download"></i>
                            <span class="d-none d-md-inline fs-12">Ilovani o'rnatish</span>
                        </button>

                        <!-- Notification Permission toggle button -->
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 d-flex align-items-center gap-1.5" id="btnToggleNotifications" title="Bildirishnomalar holati" onclick="toggleDesktopNotifications()">
                            <i class="feather-bell" id="btnNotifIcon"></i>
                            <span class="d-none d-md-inline fs-12" id="btnNotifText">Bildirishnoma</span>
                        </button>

                        @if($activeChat->isGroup())
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#groupInfoModal">
                                <i class="feather-info"></i>
                                <span class="d-none d-sm-inline">Guruh a'zolari</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Messages Container (Telegram Desktop uslubida) -->
                <div class="chat-messages-scroll" id="messagesContainer">
                    <div class="messages-inner" id="messagesInner">
                        @php $lastDate = null; @endphp
                        @foreach($messages as $msg)
                            @php
                                $msgTime = $msg->created_at->timezone('Asia/Tashkent');
                                $msgDate = $msgTime->format('Y-m-d');
                                $isMe = $msg->user_id === Auth::id();
                            @endphp

                            @if($lastDate !== $msgDate)
                                @php $lastDate = $msgDate; @endphp
                                <div class="chat-day-divider">
                                    <span>
                                        @if($msgTime->isToday())
                                            Bugun
                                        @elseif($msgTime->isYesterday())
                                            Kecha
                                        @else
                                            {{ $msgTime->format('d.m.Y') }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if($msg->type === 'system')
                                <div class="chat-system-msg">
                                    <span>{{ $msg->body }}</span>
                                </div>
                            @else
                                <div class="message-row {{ $isMe ? 'message-out' : 'message-in' }}" 
                                     data-message-id="{{ $msg->id }}"
                                     data-timestamp="{{ $msg->created_at->timestamp }}">
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

                                        {{-- Message metadata with genuine read status --}}
                                        <div class="message-meta">
                                            <span class="message-time">{{ $msgTime->format('H:i') }}</span>
                                            @if($isMe)
                                                <span class="message-status {{ $msg->is_read ? 'text-primary' : 'text-muted' }}" 
                                                      id="msgStatus_{{ $msg->id }}" 
                                                      title="{{ $msg->is_read ? 'O\'qildi' : 'Yetkazildi' }}">
                                                    {{ $msg->is_read ? '✓✓' : '✓' }}
                                                </span>
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
                            <textarea id="chatMessageInput" rows="1" placeholder="Xabar yozing yoki fayl biriktiring... (Enter — yuborish, Shift+Enter — yangi qator)" autocomplete="off"></textarea>
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
                                <div class="position-relative flex-shrink-0">
                                    <img src="{{ $u->avatar_url }}" alt="" class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                    <span class="user-status-indicator {{ $u->isOnline() ? 'online' : '' }}" data-user-status-id="{{ $u->id }}"></span>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark fs-13 text-truncate">{{ $u->full_name ?: $u->name }}</div>
                                    <small class="text-muted fs-11 text-truncate d-block">
                                        <span class="user-status-text" data-user-text-id="{{ $u->id }}">
                                            @if($u->isOnline())
                                                <span class="text-success fw-semibold">onlayn</span>
                                            @else
                                                <span>{{ $u->getLastSeenText() }}</span>
                                            @endif
                                        </span>
                                        @if($u->position)
                                            · {{ $u->position }}
                                        @endif
                                    </small>
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
                                    <div class="position-relative flex-shrink-0">
                                        <img src="{{ $p->user ? $p->user->avatar_url : asset('assets/images/8214212.png') }}" alt="" class="rounded-circle object-fit-cover shadow-sm" style="width: 36px; height: 36px;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                        @if($p->user)
                                            <span class="user-status-indicator {{ $p->user->isOnline() ? 'online' : '' }}" data-user-status-id="{{ $p->user->id }}"></span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark fs-13 text-truncate">{{ $p->user ? ($p->user->full_name ?: $p->user->name) : 'Xodim' }}</div>
                                        <small class="text-muted fs-11 text-truncate d-block">
                                            @if($p->user)
                                                <span class="user-status-text" data-user-text-id="{{ $p->user->id }}">
                                                    @if($p->user->isOnline())
                                                        <span class="text-success fw-semibold">onlayn</span>
                                                    @else
                                                        <span>{{ $p->user->getLastSeenText() }}</span>
                                                    @endif
                                                </span>
                                                @if($p->user->position)
                                                    · {{ $p->user->position }}
                                                @endif
                                            @endif
                                        </small>
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
/* ==================== TELEGRAM-LIKE CHAT REFINED STYLES ==================== */
.chat-wrapper {
    display: flex;
    height: calc(100vh - 78px);
    background: #ffffff;
    overflow: hidden;
    margin: 0;
    border-top: 1px solid #e2e8f0;
}

/* SIDEBAR */
.chat-sidebar {
    width: 360px;
    min-width: 320px;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.chat-sidebar-header {
    padding: 14px 16px 10px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}

.chat-logo-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.28);
}

.chat-search-box {
    position: relative;
    margin-bottom: 10px;
}

.chat-search-box .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}

.chat-search-box .btn-clear-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0;
    font-size: 14px;
}

.chat-search-box .btn-clear-search:hover {
    color: #ef4444;
}

.chat-search-box .form-control {
    padding-left: 38px;
    padding-right: 32px;
    border-radius: 50px;
    background: #f1f5f9;
    border: 1px solid transparent;
    font-size: 13px;
    height: 38px;
    transition: all 0.2s ease;
}

.chat-search-box .form-control:focus {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.chat-filter-tabs {
    display: flex;
    gap: 4px;
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
    color: #0f172a;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06);
}

/* Chat List Items */
.chat-list, .chat-search-results {
    flex: 1;
    overflow-y: auto;
    padding: 6px;
}

.chat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 9px 12px;
    border-radius: 12px;
    color: inherit;
    text-decoration: none;
    margin-bottom: 2px;
    transition: background 0.15s ease;
}

.chat-item:hover {
    background: #f1f5f9;
}

.chat-item.active {
    background: #e0f2fe;
}

.chat-item-avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}

.chat-item-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06);
}

.user-status-indicator {
    position: absolute;
    bottom: 0px;
    right: 0px;
    width: 12px;
    height: 12px;
    background-color: #94a3b8;
    border: 2px solid #ffffff;
    border-radius: 50%;
    display: none;
    z-index: 2;
    transition: transform 0.2s ease;
}

.user-status-indicator.online {
    display: block;
    background-color: #10b981;
    box-shadow: 0 0 0 1px rgba(16, 185, 129, 0.3);
}

.online-status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
}

.online-status-dot.pulse {
    animation: pulseDotAnimation 2s infinite;
}

@keyframes pulseDotAnimation {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

.chat-group-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.chat-group-avatar-lg {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.chat-item-info {
    flex: 1;
    min-width: 0;
}

.chat-item-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 2px;
}

.chat-item-name {
    font-size: 13.5px;
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
    font-size: 12px;
    color: #64748b;
    line-height: 1.3;
}

.chat-unread-badge {
    font-size: 10px;
    padding: 2.5px 6px;
    background: #2563eb !important;
}

/* Search results styles */
.search-category-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    padding: 8px 12px 4px;
    letter-spacing: 0.5px;
}

.search-user-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 10px;
    transition: background 0.15s ease;
    margin-bottom: 2px;
}

.search-user-item:hover {
    background: #f1f5f9;
}

/* MAIN CHAT AREA */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f1f5f9;
    position: relative;
    min-width: 0;
}

.chat-main-header {
    padding: 10px 20px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 2;
    height: 62px;
}

.chat-main-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06);
}

/* Messages Scroll Area with Telegram wallpaper feel */
.chat-messages-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 16px 20px;
    background-color: #e6edf5;
    background-image: 
        radial-gradient(rgba(148, 163, 184, 0.22) 1px, transparent 0);
    background-size: 24px 24px;
}

.messages-inner {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-width: 820px;
    margin: 0 auto;
}

/* Day Divider */
.chat-day-divider {
    text-align: center;
    margin: 10px 0;
}
.chat-day-divider span {
    background: rgba(15, 23, 42, 0.52);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 12px;
    border-radius: 50px;
    backdrop-filter: blur(4px);
}

/* System Message */
.chat-system-msg {
    text-align: center;
    margin: 6px 0;
}
.chat-system-msg span {
    background: rgba(226, 232, 240, 0.9);
    color: #475569;
    font-size: 11.5px;
    padding: 3px 14px;
    border-radius: 50px;
}

/* Message Rows */
.message-row {
    display: flex;
    gap: 8px;
    max-width: 72%;
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
    padding: 7px 12px 5px;
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    word-break: break-word;
}

.message-in .message-bubble {
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 3px;
}

/* Outgoing message (Siz) - Telegram soft green/blue */
.message-out .message-bubble {
    background: #eff6ff;
    color: #0f172a;
    border: 1px solid #bfdbfe;
    border-bottom-right-radius: 3px;
}

.message-sender-name {
    font-size: 11px;
    font-weight: 700;
    color: #2563eb;
    margin-bottom: 2px;
}

.message-text {
    font-size: 13.5px;
    line-height: 1.42;
}

.message-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    margin-top: 2px;
    font-size: 10px;
    color: #64748b;
    user-select: none;
}

.message-status {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: -1.5px;
}

/* Document Card */
.message-doc-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(0, 0, 0, 0.04);
    padding: 7px 12px;
    border-radius: 10px;
    margin-bottom: 3px;
    min-width: 220px;
    max-width: 320px;
}

.doc-icon {
    font-size: 22px;
}

.doc-info {
    flex: 1;
}

.doc-name {
    font-size: 12.5px;
    font-weight: 600;
    color: #1e293b;
}

.doc-meta {
    font-size: 10.5px;
    color: #64748b;
}

.btn-doc-download {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    text-decoration: none;
    transition: transform 0.15s ease;
}

.btn-doc-download:hover {
    transform: scale(1.1);
    color: #ffffff;
}

/* Image Preview */
.message-image-box {
    margin-bottom: 3px;
    border-radius: 8px;
    overflow: hidden;
}

.message-img-preview {
    max-width: 280px;
    max-height: 240px;
    border-radius: 8px;
    object-fit: cover;
    display: block;
    cursor: pointer;
    transition: opacity 0.15s;
}

.message-img-preview:hover {
    opacity: 0.95;
}

/* Input Area (Telegram Docked Capsule) */
.chat-input-area {
    background: #ffffff;
    padding: 10px 18px;
    border-top: 1px solid #e2e8f0;
}

.file-attachment-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    padding: 6px 12px;
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
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f1f5f9;
    border: none;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.btn-chat-action:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.chat-textarea-box {
    flex: 1;
    background: #f1f5f9;
    border-radius: 22px;
    padding: 6px 14px;
    border: 1px solid transparent;
    transition: all 0.15s ease;
}

.chat-textarea-box:focus-within {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
}

#chatMessageInput {
    width: 100%;
    border: none;
    background: transparent;
    outline: none;
    resize: none;
    max-height: 110px;
    font-size: 13.5px;
    line-height: 1.4;
    padding-top: 3px;
    color: #0f172a;
}

.btn-chat-send {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 3px 8px rgba(37, 99, 235, 0.35);
    transition: transform 0.15s ease;
    flex-shrink: 0;
}

.btn-chat-send:hover {
    transform: scale(1.06);
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
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: #e0f2fe;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin-bottom: 16px;
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

/* Modal backdrop fix */
.modal {
    z-index: 10550 !important;
}
.modal-backdrop {
    z-index: 10540 !important;
}
.modal-dialog {
    z-index: 10560 !important;
}

@media (max-width: 768px) {
    .chat-wrapper {
        height: calc(100vh - 65px);
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
    if (msg.created_at_timestamp) {
        row.setAttribute('data-timestamp', msg.created_at_timestamp);
    }

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

    let checkmarks = '';
    if (msg.is_me) {
        const isRead = msg.is_read || false;
        const icon = isRead ? '✓✓' : '✓';
        const colorClass = isRead ? 'text-primary' : 'text-muted';
        const titleText = isRead ? "O'qildi" : "Yetkazildi";
        checkmarks = `<span class="message-status ${colorClass}" id="msgStatus_${msg.id}" title="${titleText}">${icon}</span>`;
    }

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

// ==================== NOTIFICATION CHIME AUDIO ====================
function playNotificationSound() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;

        if (!window.__chatAudioCtx) {
            window.__chatAudioCtx = new AudioCtx();
        }
        const ctx = window.__chatAudioCtx;
        if (ctx.state === 'suspended') {
            ctx.resume();
        }

        const now = ctx.currentTime;

        // Tone 1: gentle low chime (587.33Hz)
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, now);
        gain1.gain.setValueAtTime(0.18, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.22);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.22);

        // Tone 2: high bright bell (880.00Hz)
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(880.00, now + 0.08);
        gain2.gain.setValueAtTime(0.24, now + 0.08);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.48);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.08);
        osc2.stop(now + 0.48);
    } catch (e) {
        console.warn('Audio play error:', e);
    }
}

// Ovozni sinab ko'rish tugmasi
function testNotificationSound() {
    playNotificationSound();
    const icon = document.getElementById('btnSoundIcon');
    if (icon) {
        icon.classList.remove('feather-volume-2');
        icon.classList.add('feather-bell');
        setTimeout(() => {
            icon.classList.remove('feather-bell');
            icon.classList.add('feather-volume-2');
        }, 1200);
    }
}

// Real-time yangilanishlarni so'rash (Polling har 3 soniyada)
async function pollChatUpdates() {
    try {
        const url = `{{ route('chat.poll') }}?active_chat_id=${activeChatId || ''}&last_message_id=${lastMessageId || 0}`;
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();

        let previousTotalUnread = window.lastTotalUnread || 0;
        let newTotalUnread = data.total_unread || 0;

        // 1. Yangi kelgan xabarlar bo'lsa qo'shish
        if (data.new_messages && data.new_messages.length > 0) {
            let hasNew = false;
            data.new_messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    appendMessageToUI(msg);
                    lastMessageId = msg.id;
                    hasNew = true;

                    // Tab orqa fonda bo'lsa yoki oyna nofaol bo'lsa, desktop bildirishnoma chiqarish
                    if (document.hidden && typeof window.showDesktopNotification === 'function') {
                        const notifBody = msg.body || (msg.type === 'image' ? '📷 Rasm yubordi' : '📎 Fayl yubordi');
                        window.showDesktopNotification(
                            msg.user_name || 'Smart-Ijro — Muloqot',
                            notifBody,
                            `{{ route('chat.index') }}?chat_id=${msg.chat_id}`
                        );
                    }
                }
            });
            if (hasNew) {
                scrollToBottom();
                playNotificationSound(); // <-- Yangi xabar kelganda ovozli signal!
            }
        } else if (newTotalUnread > previousTotalUnread) {
            // Boshqa chatga yangi xabar kelgan bo'lsa ham signal berish
            playNotificationSound();
            if (document.hidden && typeof window.showDesktopNotification === 'function') {
                window.showDesktopNotification(
                    'Smart-Ijro — Muloqot',
                    'Sizda yangi xabarlar mavjud!',
                    '{{ route("chat.index") }}'
                );
            }
        }
        window.lastTotalUnread = newTotalUnread;

        // 2. Onlayn / Oflayn holat va so'nggi faollikni yangilash (Online status & last seen)
        if (data.chat_status) {
            if (data.chat_status.type === 'direct') {
                const statusEl = document.getElementById('activeChatUserStatus');
                if (statusEl) {
                    if (data.chat_status.is_online) {
                        statusEl.innerHTML = `<span class="text-success fw-semibold d-inline-flex align-items-center gap-1.5"><span class="online-status-dot pulse"></span> onlayn</span>`;
                    } else {
                        statusEl.innerHTML = `<span class="text-muted">${data.chat_status.status_text}</span>`;
                    }
                }
            } else if (data.chat_status.type === 'group') {
                const groupEl = document.getElementById('activeChatGroupText');
                if (groupEl) {
                    groupEl.innerHTML = `<i class="feather-users fs-11 me-1"></i>${data.chat_status.status_text}`;
                }
            }
        }

        // 3. Barcha foydalanuvchilarning onlayn / oflayn yashil indikatorlari va matnlarini yangilash
        if (data.users_status && typeof data.users_status === 'object') {
            for (const [uid, uInfo] of Object.entries(data.users_status)) {
                // Nuqta (indicator)
                document.querySelectorAll(`[data-user-status-id="${uid}"]`).forEach(el => {
                    if (uInfo.is_online) {
                        el.classList.add('online');
                    } else {
                        el.classList.remove('online');
                    }
                });
                // Matn (status text)
                document.querySelectorAll(`[data-user-text-id="${uid}"]`).forEach(el => {
                    if (uInfo.is_online) {
                        el.innerHTML = '<span class="text-success fw-semibold">onlayn</span>';
                    } else {
                        el.innerHTML = `<span>${escapeHtml(uInfo.text)}</span>`;
                    }
                });
            }
        } else if (data.online_user_ids && Array.isArray(data.online_user_ids)) {
            const onlineSet = new Set(data.online_user_ids.map(id => String(id)));
            document.querySelectorAll('[data-user-status-id]').forEach(indicator => {
                const uid = indicator.getAttribute('data-user-status-id');
                if (onlineSet.has(uid)) {
                    indicator.classList.add('online');
                } else {
                    indicator.classList.remove('online');
                }
            });
        }

        // 4. O'qilganlik holatini (Checkmarks ✓ -> ✓✓) yangilash!
        if (data.other_last_read_timestamp && data.other_last_read_timestamp > 0) {
            const outgoingMessages = document.querySelectorAll('.message-out[data-timestamp]');
            outgoingMessages.forEach(el => {
                const ts = parseInt(el.getAttribute('data-timestamp') || '0');
                const statusEl = el.querySelector('.message-status');
                if (statusEl) {
                    if (ts <= data.other_last_read_timestamp) {
                        statusEl.className = 'message-status text-primary';
                        statusEl.innerText = '✓✓';
                        statusEl.title = "O'qildi";
                    }
                }
            });
        }

        // 5. O'qilmaganlar sonini yangilash
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

// ==================== PWA VA DESKTOP BILDIRISHNOMALAR ====================
let deferredInstallPrompt = null;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredInstallPrompt = e;
    const btn = document.getElementById('btnPwaInstall');
    if (btn) {
        btn.classList.remove('d-none');
        btn.classList.add('d-flex');
    }
});

function initPwaAndNotifications() {
    const btnPwa = document.getElementById('btnPwaInstall');
    if (btnPwa) {
        btnPwa.addEventListener('click', async () => {
            if (!deferredInstallPrompt) return;
            deferredInstallPrompt.prompt();
            const choice = await deferredInstallPrompt.userChoice;
            if (choice.outcome === 'accepted') {
                btnPwa.classList.remove('d-flex');
                btnPwa.classList.add('d-none');
            }
            deferredInstallPrompt = null;
        });
    }

    updateNotificationButtonUI();
}

function updateNotificationButtonUI() {
    const btn = document.getElementById('btnToggleNotifications');
    const icon = document.getElementById('btnNotifIcon');
    const text = document.getElementById('btnNotifText');
    if (!btn || !icon || !text) return;

    if (!("Notification" in window)) {
        btn.style.display = 'none';
        return;
    }

    if (Notification.permission === 'granted') {
        icon.className = 'feather-bell text-success';
        text.innerText = 'Yoqilgan';
        btn.classList.remove('btn-outline-secondary', 'btn-outline-danger');
        btn.classList.add('btn-outline-success');
    } else if (Notification.permission === 'denied') {
        icon.className = 'feather-bell-off text-danger';
        text.innerText = 'Taqiqlangan';
        btn.classList.remove('btn-outline-secondary', 'btn-outline-success');
        btn.classList.add('btn-outline-danger');
    } else {
        icon.className = 'feather-bell text-secondary';
        text.innerText = 'Bildirishnoma';
        btn.classList.remove('btn-outline-success', 'btn-outline-danger');
        btn.classList.add('btn-outline-secondary');
    }
}

async function toggleDesktopNotifications() {
    if (!("Notification" in window)) {
        alert("Brouzeringiz bildirishnomalarni qo'llab-quvvatlamaydi!");
        return;
    }
    if (Notification.permission === 'granted') {
        if (typeof window.showDesktopNotification === 'function') {
            window.showDesktopNotification('Smart-Ijro — Muloqot', 'Bildirishnomalar yoqilgan! Yangi xabarlar ekranga chiqadi.', '{{ route("chat.index") }}');
        }
        return;
    }
    const permission = await Notification.requestPermission();
    updateNotificationButtonUI();
    if (permission === 'granted') {
        if (typeof window.showDesktopNotification === 'function') {
            window.showDesktopNotification('Smart-Ijro — Muloqot', 'Bildirishnomalar muvaffaqiyatli yoqildi!', '{{ route("chat.index") }}');
        }
    }
}

// ==================== REAL-TIME SIDEBAR SEARCH ====================
function handleSidebarSearch(query) {
    const clearBtn = document.getElementById('btnClearChatSearch');
    const chatList = document.getElementById('chatListContainer');
    const searchResults = document.getElementById('sidebarSearchResults');
    const filterTabs = document.getElementById('chatFilterTabs');

    query = query.toLowerCase().trim();

    if (!query) {
        if (clearBtn) clearBtn.classList.add('d-none');
        if (chatList) chatList.classList.remove('d-none');
        if (searchResults) searchResults.classList.add('d-none');
        if (filterTabs) filterTabs.classList.remove('d-none');
        return;
    }

    if (clearBtn) clearBtn.classList.remove('d-none');
    if (chatList) chatList.classList.add('d-none');
    if (searchResults) searchResults.classList.remove('d-none');
    if (filterTabs) filterTabs.classList.add('d-none');

    // 1. Mavjud chatlar ichidan qidirish
    let matchingChats = 0;
    const existingList = document.getElementById('searchExistingList');
    const existingSection = document.getElementById('searchExistingSection');
    if (existingList) existingList.innerHTML = '';

    const allChatItems = document.querySelectorAll('#chatListContainer .chat-item');
    allChatItems.forEach(item => {
        const name = (item.getAttribute('data-name') || '').toLowerCase();
        if (name.includes(query)) {
            matchingChats++;
            if (existingList) {
                const clone = item.cloneNode(true);
                clone.style.display = 'flex';
                existingList.appendChild(clone);
            }
        }
    });

    if (existingSection) {
        existingSection.classList.toggle('d-none', matchingChats === 0);
    }

    // 2. Barcha xodimlar ichidan qidirish (Global Employee search)
    let matchingUsers = 0;
    const allUserItems = document.querySelectorAll('.search-user-item');
    allUserItems.forEach(item => {
        const text = (item.getAttribute('data-search-text') || '').toLowerCase();
        if (text.includes(query)) {
            item.style.display = 'flex';
            matchingUsers++;
        } else {
            item.style.display = 'none';
        }
    });

    const globalSection = document.getElementById('searchGlobalSection');
    if (globalSection) {
        globalSection.classList.toggle('d-none', matchingUsers === 0);
    }

    // 3. Agar hech narsa topilmasa
    const noResults = document.getElementById('searchNoResults');
    if (noResults) {
        noResults.classList.toggle('d-none', (matchingChats + matchingUsers) > 0);
    }
}

function clearSidebarSearch() {
    const input = document.getElementById('chatSearchInput');
    if (input) {
        input.value = '';
        handleSidebarSearch('');
        input.focus();
    }
}

// Enter bosilganda yuborish, Shift+Enter bosilganda yangi qator
document.addEventListener('DOMContentLoaded', function() {
    // Modal oynalarni to'g'ridan-to'g'ri body ga ko'chirish
    ['newDirectModal', 'newGroupModal', 'groupInfoModal'].forEach(function(modalId) {
        const modalEl = document.getElementById(modalId);
        if (modalEl && modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
    });

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

    // Real-time Sidebar Search
    const searchInput = document.getElementById('chatSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            handleSidebarSearch(this.value);
        });
    }

    // Polling start (har 3 soniyada)
    pollInterval = setInterval(pollChatUpdates, 3000);

    // PWA va Bildirishnomalarni ishga tushirish
    initPwaAndNotifications();

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
