<style>
/* Sidebar "Muloqot va hujjatlar" bo'limini ajratib ko'rsatish (Highlight) */
.chat-menu-item-highlight {
    margin: 5px 12px 6px !important;
    border-radius: 12px !important;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.12) 0%, rgba(99, 102, 241, 0.18) 100%) !important;
    border: 1.5px solid rgba(37, 99, 235, 0.3) !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12) !important;
}
.chat-menu-item-highlight:hover {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.22) 0%, rgba(99, 102, 241, 0.28) 100%) !important;
    border-color: #2563eb !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.22) !important;
}
.chat-menu-item-highlight .nxl-link {
    color: #1d4ed8 !important;
    font-weight: 700 !important;
    padding: 10px 14px !important;
}
.chat-menu-item-highlight.active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    border-color: #1e40af !important;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35) !important;
}
.chat-menu-item-highlight.active .nxl-link {
    color: #ffffff !important;
}
.chat-menu-item-highlight .nxl-micon i {
    color: #2563eb !important;
    font-size: 19px !important;
}
.chat-menu-item-highlight.active .nxl-micon i {
    color: #ffffff !important;
}
.chat-sparkle-pill {
    background: #2563eb;
    color: #ffffff;
    font-size: 9.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-left: 6px;
}
.chat-menu-item-highlight.active .chat-sparkle-pill {
    background: #ffffff;
    color: #2563eb;
}
</style>

<nav class="nxl-navigation">
    <div class="navbar-wrapper" style="background-color: #dcddea">
        <div class="m-header" style="padding: 12px 18px; display: flex; align-items: center; justify-content: flex-start;">
            <a href="{{ route('dashboard') }}" class="b-brand d-flex align-items-center gap-2.5 text-decoration-none">
                <img src="{{ asset('assets/images/logoo.jfif') }}" alt="Smart-Ijro Logo" class="logo logo-lg" style="height: 42px; width: 42px; object-fit: contain; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.18); border: 1.5px solid #ffffff;"/>
                <div class="d-flex flex-column text-start">
                    <span class="fw-bolder text-dark" style="font-size: 14.5px; letter-spacing: 0.6px; line-height: 1.2;">SMART-IJRO</span>
                    <span class="text-muted fw-semibold" style="font-size: 10px; line-height: 1.1;">Monitoring & Muloqot</span>
                </div>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">

                {{-- ======================================================== --}}
                {{-- 1. АСОСИЙ НАВИГАЦИЯ (ХОДИМЛАР ВА БОШҚАРУВ)              --}}
                {{-- ======================================================== --}}
                <li class="nxl-item nxl-caption">
                    <label>Асосий бўлимлар</label>
                </li>

                @auth
                    {{-- Топшириқлар --}}
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('tasks.*') && !request()->routeIs('tasks.filter') && !request()->routeIs('tasks.search') ? 'active' : '' }}">
                        <a href="{{ route('tasks.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-check-square"></i></span>
                            <span class="nxl-mtext">Топшириқлар</span>
                        </a>
                    </li>

                    {{-- Топшириқларни саралаш (Админ ва Бошлиқ учун) --}}
                    @if (in_array(auth()->user()->role, ['admin', 'boshliq']))
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('tasks.filter') || request()->routeIs('tasks.search') ? 'active' : '' }}">
                            <a href="{{ route('tasks.filter') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-filter"></i></span>
                                <span class="nxl-mtext">Топшириқларни саралаш</span>
                            </a>
                        </li>
                    @endif

                    {{-- Лойиҳалар --}}
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        <a href="{{ route('projects.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-folder"></i></span>
                            <span class="nxl-mtext">Лойиҳалар</span>
                        </a>
                    </li>

                    {{-- И-индекс --}}
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                        <a href="{{ route('articles.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-trending-up"></i></span>
                            <span class="nxl-mtext">И-индекс</span>
                        </a>
                    </li>

                    {{-- Ички мулоқот ва ҳужжатлар (Chat - Ajralib turuvchi maxsus dizayn) --}}
                    <li class="nxl-item nxl-hasmenu chat-menu-item-highlight {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <a href="{{ route('chat.index') }}" class="nxl-link d-flex align-items-center">
                            <span class="nxl-micon"><i class="feather-message-circle"></i></span>
                            <span class="nxl-mtext">Мулоқот ва ҳужжатлар</span>
                            <span class="chat-sparkle-pill">Chat</span>
                            <span class="badge bg-danger rounded-pill ms-auto px-2 fs-10" id="sidebarChatBadge" style="display: none;">0</span>
                        </a>
                    </li>

                    {{-- Ҳисобот (Админ ва Бошлиқ учун) --}}
                    @if (in_array(auth()->user()->role, ['admin', 'boshliq']))
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('monitoring.hisobot') ? 'active' : '' }}">
                            <a href="{{ route('monitoring.hisobot') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-clipboard"></i></span>
                                <span class="nxl-mtext">Ҳисобот</span>
                            </a>
                        </li>
                    @endif

                    {{-- Ҳаёт ва ижтимоий бўлимлар (Админ ёки махсус ходим учун) --}}
                    @if (auth()->user()->role === 'admin' || auth()->user()->id == 63)
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <a href="{{ route('employees.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-gift"></i></span>
                                <span class="nxl-mtext">Ходимлар туғилган кунлари</span>
                            </a>
                        </li>
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('news.*') ? 'active' : '' }}">
                            <a href="{{ route('news.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-bell"></i></span>
                                <span class="nxl-mtext">Сўнгги янгиликлар</span>
                            </a>
                        </li>
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('employee-works.*') ? 'active' : '' }}">
                            <a href="{{ route('employee-works.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-feather"></i></span>
                                <span class="nxl-mtext">Ходимлар ижоди</span>
                            </a>
                        </li>
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('group-photos.*') ? 'active' : '' }}">
                            <a href="{{ route('group-photos.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-image"></i></span>
                                <span class="nxl-mtext">Гуруҳ расмлари</span>
                            </a>
                        </li>
                    @endif
                @endauth

                {{-- ======================================================== --}}
                {{-- 2. ҲУЖЖАТЛАР ВА ИЛМИЙ БЎЛИМЛАР                          --}}
                {{-- ======================================================== --}}
                <li class="nxl-item nxl-caption">
                    <label>Ҳужжатлар ва кутубхона</label>
                </li>

                {{-- Меъёрий-ҳуқуқий ҳужжатлар --}}
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('documents.*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Меъёрий ҳужжатлар</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('documents.index') }}">📋 Барча ҳужжатлар</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('documents.byCategory') && request()->route('id') == 3 ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('documents.byCategory', 3) }}">Институт буйруқлари</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('documents.byCategory') && request()->route('id') == 1 ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('documents.byCategory', 1) }}">Президент фармон ва қарорлари</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('documents.byCategory') && request()->route('id') == 2 ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('documents.byCategory', 2) }}">Ҳукумат қарорлари</a>
                        </li>
                    </ul>
                </li>

                {{-- Электрон кутубхона (Маълумотлар банки) --}}
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('library.*') || request()->routeIs('libraries.*') ? 'active' : '' }}">
                    <a href="{{ route('library.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-book-open"></i></span>
                        <span class="nxl-mtext">Электрон кутубхона</span>
                    </a>
                </li>

                {{-- Лаборатория --}}
                @php
                    $isLabActive = request()->routeIs('ongoing-works.*') || request()->routeIs('planned-works.*') || request()->routeIs('completed-works.*') || request()->routeIs('suggestions.*');
                @endphp
                <li class="nxl-item nxl-hasmenu {{ $isLabActive ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-activity"></i></span>
                        <span class="nxl-mtext">Лаборатория</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ request()->routeIs('ongoing-works.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('ongoing-works.index') }}">Жараёндаги ишлар</a></li>
                        <li class="nxl-item {{ request()->routeIs('planned-works.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('planned-works.index') }}">Режалаштирилган ишлар</a></li>
                        <li class="nxl-item {{ request()->routeIs('completed-works.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('completed-works.index') }}">Бажарилган ишлар</a></li>
                        <li class="nxl-item {{ request()->routeIs('suggestions.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('suggestions.index') }}">Таклифлар</a></li>
                    </ul>
                </li>

                {{-- ======================================================== --}}
                {{-- МУСТАҚИЛ ИЗЛАНУВЧИЛАР (АДМИН ВА КОТИБ УЧУН)              --}}
                {{-- ======================================================== --}}
                @if (in_array(auth()->user()->role, ['admin', 'kotib']))
                    <li class="nxl-item nxl-caption">
                        <label>Илмий изланувчилар</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.izlanuvchilar.*') ? 'active nxl-trigger' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-award"></i></span>
                            <span class="nxl-mtext">Мустақил изланувчилар</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs('admin.izlanuvchilar.dashboard') ? 'active' : '' }}">
                                <a class="nxl-link" href="{{ route('admin.izlanuvchilar.dashboard') }}">📊 Кўрсаткичлар (Панел)</a>
                            </li>
                            <li class="nxl-item {{ request()->routeIs('admin.izlanuvchilar.researchers') ? 'active' : '' }}">
                                <a class="nxl-link" href="{{ route('admin.izlanuvchilar.researchers') }}">👨‍🎓 Изланувчилар рўйхати</a>
                            </li>
                            <li class="nxl-item {{ request()->routeIs('admin.izlanuvchilar.tasks') || request()->routeIs('admin.izlanuvchilar.tasks.detail') ? 'active' : '' }}">
                                <a class="nxl-link" href="{{ route('admin.izlanuvchilar.tasks') }}">📑 Кўриб чиқиш (Вазифалар)</a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- ======================================================== --}}
                {{-- 3. ХАВФСИЗЛИК ВА МОНИТОРИНГ (АДМИН ВА КАДР УЧУН)         --}}
                {{-- ======================================================== --}}
                @auth
                    @if (in_array(auth()->user()->role, ['admin', 'kadr', 'boshliq']))
                        <li class="nxl-item nxl-caption">
                            <label>Мониторинг</label>
                        </li>

                        {{-- Видеокузатув (CCTV) --}}
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('cctv.*') ? 'active nxl-trigger' : '' }}">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-video"></i></span>
                                <span class="nxl-mtext">Видеокузатув (CCTV)</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item {{ request()->routeIs('cctv.index') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('cctv.index') }}">Камералар (Жадвал/Рўйхат)</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('cctv.ptz') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('cctv.ptz') }}">PTZ Бошқарув</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('cctv.wall') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('cctv.wall') }}" target="_blank">Монитор девори (Wall)</a>
                                </li>
                            </ul>
                        </li>

                        {{-- Турникет ва давомат (Hikvision) --}}
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.turniket.*') ? 'active nxl-trigger' : '' }}">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-user-check"></i></span>
                                <span class="nxl-mtext">Турникет / Давомат</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item {{ request()->routeIs('admin.turniket.dashboard') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('admin.turniket.dashboard') }}">📊 Кўрсаткичлар (Панел)</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('admin.turniket.attendance') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('admin.turniket.attendance') }}">📋 Давомат журнали</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('admin.turniket.leaves') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('admin.turniket.leaves') }}">📝 Сабаблилар ва таътиллар</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('admin.turniket.reports') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('admin.turniket.reports') }}">📑 Ҳисоботлар</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('admin.turniket.devices') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('admin.turniket.devices') }}">🛡️ Турникетлар (Қурилмалар)</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    {{-- ======================================================== --}}
                    {{-- 4. СТАТИСТИКА ВА ТАҲЛИЛ (АДМИН ВА БОШЛИҚ УЧУН)           --}}
                    {{-- ======================================================== --}}
                    @if (in_array(auth()->user()->role, ['admin', 'boshliq']))
                        <li class="nxl-item nxl-caption">
                            <label>Статистика ва таҳлил</label>
                        </li>

                        {{-- Ходимлар статистикаси --}}
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('monitoring.umumiy') || request()->routeIs('monitoring.diagramma') ? 'active nxl-trigger' : '' }}">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-pie-chart"></i></span>
                                <span class="nxl-mtext">Ходимлар статистикаси</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item {{ request()->routeIs('monitoring.diagramma') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('monitoring.diagramma') }}">Диаграмма ва таҳлил</a>
                                </li>
                                <li class="nxl-item {{ request()->routeIs('monitoring.umumiy') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('monitoring.umumiy') }}">Умумий мониторинг</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    {{-- ======================================================== --}}
                    {{-- 5. АДМИНИСТРАЦИЯ (ФАҚАТ АДМИНИСТРАТОР УЧУН)              --}}
                    {{-- ======================================================== --}}
                    @if (auth()->user()->role === 'admin')
                        <li class="nxl-item nxl-caption">
                            <label>Администрация</label>
                        </li>

                        {{-- Ходимлар (Фойдаланувчилар) --}}
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-users"></i></span>
                                <span class="nxl-mtext">Ходимлар (Фойдаланувчилар)</span>
                            </a>
                        </li>

                        {{-- Категориялар --}}
                        <li class="nxl-item nxl-hasmenu {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <a href="{{ route('categories.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-grid"></i></span>
                                <span class="nxl-mtext">Категориялар</span>
                            </a>
                        </li>

                        {{-- ======================================================== --}}
                        {{-- БУНДАН КЕЙИНГИ ҚЎШИЛАДИГАН ЯНГИ АДМИН ФУНКЦИЯЛАРИ ШУ ЕРГА --}}
                        {{-- ======================================================== --}}
                    @endif
                @endauth

            </ul>
        </div>
    </div>
</nav>
