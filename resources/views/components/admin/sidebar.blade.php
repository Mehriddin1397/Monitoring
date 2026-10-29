<nav class="nxl-navigation">
    <div class="navbar-wrapper" style="background-color: #dcddea">
        <div class="m-header">
            <a href="#" class="b-brand">
                <img src="{{ asset('/img/logo/3.png') }}" alt="Logo" class="logo logo-lg"/>
                <img src="{{ asset('/img/logo/3.png') }}" alt="Logo" class="logo logo-sm"/>
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
                {{-- 3. ХАВФСИЗЛИК ВА МОНИТОРИНГ (АДМИН ВА КАДР УЧУН)         --}}
                {{-- ======================================================== --}}
                @auth
                    @if (in_array(auth()->user()->role, ['admin', 'kadr']))
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
                    @endif

                    {{-- ======================================================== --}}
                    {{-- 4. АДМИНИСТРАЦИЯ (ФАҚАТ АДМИНИСТРАТОР УЧУН)              --}}
                    {{-- ======================================================== --}}
                    @if (auth()->user()->role === 'admin')
                        <li class="nxl-item nxl-caption">
                            <label>Администрация</label>
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
                                    <a class="nxl-link" href="{{ route('monitoring.umumiy') }}">Жадвал кўриниши</a>
                                </li>
                            </ul>
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
