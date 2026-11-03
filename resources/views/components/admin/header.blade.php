<header class="nxl-header">
    <div class="header-wrapper">
        <!--! [Start] Header Left !-->
        <div class="header-left d-flex align-items-center gap-4">
            <!--! [Start] nxl-head-mobile-toggler !-->
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <!--! [Start] nxl-head-mobile-toggler !-->
            <!--! [Start] nxl-navigation-toggle !-->
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
            <!--! [End] nxl-navigation-toggle !-->
            <!--! [Start] nxl-lavel-mega-menu-toggle !-->
            <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                <a href="javascript:void(0);" id="nxl-lavel-mega-menu-open">
                    <i class="feather-align-left"></i>
                </a>
            </div>
            <!--! [End] nxl-lavel-mega-menu-toggle !-->
            <!--! [Start] nxl-lavel-mega-menu !-->
            <div class="nxl-drp-link nxl-lavel-mega-menu">
                <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                    <a href="javascript:void(0)" id="nxl-lavel-mega-menu-hide">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Back</span>
                    </a>
                </div>
                <!--! [Start] nxl-lavel-mega-menu-wrapper !-->

                <div class="logo-center text-center">
                    <div class="logo-block d-flex align-items-center gap-3">
                        <img src="{{ asset('assets/images/logoo.jfif') }}" alt="logo" class="site-logo" />

                        <div class="logo-text d-flex flex-column">
                            <div class="logo-subtitle">Ўзбекистон Республикаси Криминология тадқиқот институти</div>
                            <h1 class="site-title">БОШҚАРУВ, СМАРТ ИЖРО-НАЗОРАТ</h1>
                        </div>
                    </div>
                </div>


                <!--! [End] nxl-lavel-mega-menu-wrapper !-->
            </div>
            <!--! [End] nxl-lavel-mega-menu !-->
        </div>
        <!--! [End] Header Left !-->
        <!--! [Start] Header Right !-->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                <div class="dropdown nxl-h-item nxl-header-search">
                    <form id="searchForm" action="{{ route('search') }}" method="get">
                        <input
                            type="text"
                            class="search-box"
                            name="query"
                            placeholder="Ходимлар исми .."
                            required
                        >
                    </form>
                    <style>
                        .logo-block {
                            display: flex;
                            align-items: center;
                            gap: 12px; /* logodan matngacha bo'lgan masofa */
                        }

                        .site-logo {
                            width: 55px;
                            height: 55px;
                            border-radius: 50%;
                            object-fit: cover;
                            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
                        }

                        .logo-text {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                        }

                        .logo-subtitle {
                            font-size: 12px;
                            font-weight: 500;
                            color: #0072ff;
                            font-family: 'Orbitron', sans-serif;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            opacity: 0.9;
                            margin-bottom: 2px;
                        }

                        .site-title {
                            font-family: 'Orbitron', sans-serif;
                            font-size: 20px;
                            font-weight: bold;
                            color: #0072ff;
                            margin: 0;
                            letter-spacing: 1.2px;
                            text-shadow: 1px 1px 2px rgba(0, 114, 255, 0.3);
                        }



                        .search-box {
                            outline: none;
                            padding: 7px;
                            padding-left: 10px;
                            padding-right: 40px;
                            height: 35px;
                            width: 185px;
                            border-radius: 30px;
                            font-size: 15px;
                            border: 1px solid #444; /* Chegara qo'shildi */
                            background-color: #f0f0f0; /* Biroz qoraytirilgan fon */
                            color: #333; /* Matn rangi */
                            font-weight: bold; /* Matnni sal qalin qilish */
                            box-shadow: 0px 0px 5px ;
                        }
                    </style>
                </div>
                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </a>
                    </div>
                </div>

                @if(Auth::check() && Auth::user()->role === 'admin')
                    @php
                        $tomorrowDate = \Carbon\Carbon::now('Asia/Tashkent')->addDay();
                        $tomorrowEmployees = \App\Models\Employee::whereMonth('birth_date', $tomorrowDate->month)
                            ->whereDay('birth_date', $tomorrowDate->day)
                            ->get();

                        $todayDate = \Carbon\Carbon::now('Asia/Tashkent');
                        $todayCelebrants = \App\Models\Employee::whereMonth('birth_date', $todayDate->month)
                            ->whereDay('birth_date', $todayDate->day)
                            ->get();

                        $unreadCount = $tomorrowEmployees->count();
                    @endphp
                    <div class="dropdown nxl-h-item">
                        <a href="javascript:void(0);" class="nxl-head-link position-relative d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside" aria-expanded="false" title="Туғилган кун эслатмалари">
                            <i class="feather-bell fs-18 {{ $unreadCount > 0 ? 'text-warning bday-bell-shake' : 'text-dark' }}"></i>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger rounded-pill position-absolute" style="top: 2px; right: -2px; font-size: 10px; padding: 2px 5px; line-height: 1; border: 1.5px solid #fff;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown p-0 shadow-lg border-0 rounded-4 overflow-hidden" style="width: 360px; max-width: 90vw;">
                            <!-- Header -->
                            <div class="p-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-18">🎂</span>
                                    <div>
                                        <h6 class="text-white fw-bold mb-0 fs-14">Туғилган кун эслатмаси</h6>
                                        <small class="text-warning fs-11">
                                            @if($unreadCount > 0)
                                                Эртага {{ $unreadCount }} нафар ходимнинг таваллуд куни!
                                            @else
                                                Эртага таваллуд куни бўлганлар йўқ
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger px-2 py-1 fs-11 fw-bold">1 кун қолди</span>
                                @endif
                            </div>

                            <!-- List content -->
                            <div class="p-2" style="max-height: 380px; overflow-y: auto;">
                                @if($tomorrowEmployees->isNotEmpty())
                                    <div class="px-2 py-1 text-uppercase fs-11 fw-bold text-muted">
                                        ⏳ Эртага нишонлайдиганлар (1 кун қолди):
                                    </div>
                                    @foreach($tomorrowEmployees as $emp)
                                        <div class="p-2.5 mb-2 rounded-3 bg-light border border-warning-subtle d-flex align-items-center gap-2.5">
                                            @if($emp->photo)
                                                <img src="{{ asset('storage/' . $emp->photo) }}" alt="{{ $emp->full_name }}" class="rounded-circle object-fit-cover shadow-sm flex-shrink-0" style="width: 44px; height: 44px; border: 2px solid #f59e0b;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                            @else
                                                <div class="avatar-text avatar-sm bg-warning text-dark rounded-circle fw-bold flex-shrink-0" style="width: 44px; height: 44px; line-height: 44px; text-align: center;">
                                                    {{ mb_substr($emp->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold text-dark fs-13 text-truncate">{{ $emp->full_name }}</div>
                                                <div class="text-muted fs-11 text-truncate">{{ $emp->position ?: 'Институт ходими' }}</div>
                                                <div class="mt-1 d-flex align-items-center gap-2">
                                                    <span class="badge bg-warning text-dark py-0.5 px-2 fs-10 fw-bold">
                                                        <i class="feather-clock me-1"></i>1 кун қолди
                                                    </span>
                                                    @if($emp->phone)
                                                        <span class="text-muted fs-11">
                                                            <i class="feather-phone fs-10 me-0.5"></i>{{ $emp->phone }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($emp->phone)
                                                <form action="{{ route('employees.send_sms', $emp->id) }}" method="POST" class="ms-auto flex-shrink-0" onsubmit="return confirm('{{ $emp->full_name }} ga tabrik SMS xabari yuborilsinmi?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 rounded-pill px-2 py-0.5 fs-11" title="Tabrik SMS yuborish">
                                                        <i class="feather-send" style="font-size: 10px;"></i> SMS
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                                @if($todayCelebrants->isNotEmpty())
                                    <div class="px-2 py-1 mt-1 text-uppercase fs-11 fw-bold text-success">
                                        🎉 Бугун таваллуд айёми бўлганlar:
                                    </div>
                                    @foreach($todayCelebrants as $emp)
                                        <div class="p-2.5 mb-2 rounded-3 bg-soft-success border border-success-subtle d-flex align-items-center gap-2.5">
                                            @if($emp->photo)
                                                <img src="{{ asset('storage/' . $emp->photo) }}" alt="{{ $emp->full_name }}" class="rounded-circle object-fit-cover shadow-sm flex-shrink-0" style="width: 40px; height: 40px; border: 2px solid #10b981;" onerror="this.src='{{ asset('assets/images/8214212.png') }}';">
                                            @else
                                                <div class="avatar-text avatar-sm bg-success text-white rounded-circle fw-bold flex-shrink-0" style="width: 40px; height: 40px; line-height: 40px; text-align: center;">
                                                    {{ mb_substr($emp->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold text-dark fs-13 text-truncate">{{ $emp->full_name }}</div>
                                                <div class="text-muted fs-11 text-truncate">{{ $emp->position ?: 'Институт ходими' }}</div>
                                                <span class="badge bg-success text-white py-0.5 px-2 fs-10 fw-bold mt-1">
                                                    <i class="feather-gift me-1"></i>Бугун! 🎉
                                                </span>
                                            </div>
                                            @if($emp->phone)
                                                <form action="{{ route('employees.send_sms', $emp->id) }}" method="POST" class="ms-auto flex-shrink-0" onsubmit="return confirm('{{ $emp->full_name }} ga tabrik SMS xabari yuborilsinmi?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 rounded-pill px-2 py-0.5 fs-11 text-white" title="Tabrik SMS yuborish">
                                                        <i class="feather-send" style="font-size: 10px;"></i> SMS
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                                @if($tomorrowEmployees->isEmpty() && $todayCelebrants->isEmpty())
                                    <div class="text-center py-4 px-3">
                                        <div class="fs-32 text-muted mb-2">🔔</div>
                                        <h6 class="fs-13 fw-semibold text-dark mb-1">Яқин кунларда туғилган кунлар йўқ</h6>
                                        <p class="fs-11 text-muted mb-0">Эртага ёки бугун таваллуд айёми бўлган ходимлар мавжуд эмас.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="p-2 border-top bg-light text-center">
                                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill fs-12 fw-semibold">
                                    <i class="feather-calendar me-1"></i>Барча туғилган кунлар рўйхати →
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <style>
                    @keyframes bdayBellShake {
                        0%, 100% { transform: rotate(0); }
                        10%, 30% { transform: rotate(-15deg); }
                        20%, 40% { transform: rotate(15deg); }
                        50% { transform: rotate(0); }
                    }
                    .bday-bell-shake {
                        display: inline-block;
                        animation: bdayBellShake 2.5s infinite ease-in-out;
                        transform-origin: top center;
                    }
                </style>
{{--                <div class="nxl-h-item dark-light-theme">--}}
{{--                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button" style="display: none" >--}}
{{--                        <i class="feather-moon"></i>--}}
{{--                    </a>--}}
{{--                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">--}}
{{--                        <i class="feather-sun"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside" class="d-flex align-items-center" title="Шахсий профиль">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="img-fluid user-avtar me-0 rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px; border: 2px solid #3b82f6;" onerror="this.onerror=null; this.src='{{ asset('assets/images/8214212.png') }}';" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown p-0 shadow-lg border-0 rounded-4 overflow-hidden" style="min-width: 320px;">
                        <!-- Header banner / profile hero -->
                        <div class="p-3 text-white text-center position-relative" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
                            <div class="position-relative d-inline-block mb-2">
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle object-fit-cover shadow border border-3 border-white" style="width: 76px; height: 76px;" onerror="this.onerror=null; this.src='{{ asset('assets/images/8214212.png') }}';" />
                                <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-1 border border-2 border-white" title="Тизимда фаол">
                                    <span class="visually-hidden">Online</span>
                                </span>
                            </div>
                            <h6 class="text-white fw-bold mb-0 fs-15 text-break">{{ Auth::user()->name }}</h6>
                            <small class="text-white-50 fs-12">{{ Auth::user()->email }}</small>
                            <div class="mt-2">
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 fs-11 text-uppercase fw-semibold">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>

                        <!-- Profile Info: Tug'ilgan kun & I-index -->
                        <div class="p-3 bg-light border-bottom">
                            <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-muted fs-12 d-flex align-items-center gap-1.5">
                                    <i class="feather-gift text-warning fs-14"></i> Туғилган куни:
                                </span>
                                <span class="fw-bold text-dark fs-12">
                                    {{ Auth::user()->formatted_birth_date }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-2">
                                <span class="text-muted fs-12 d-flex align-items-center gap-1.5">
                                    <i class="feather-trending-up text-success fs-14"></i> И-индекс баҳоси:
                                </span>
                                <span class="badge bg-soft-success text-success fw-bold fs-12 px-2.5 py-1">
                                    {{ Auth::user()->i_index_score }} балл
                                </span>
                            </div>
                        </div>

                        <!-- Action links -->
                        <div class="p-2.5 d-flex flex-column gap-1.5 bg-white">
                            <a href="{{ route('profile') }}" class="btn btn-sm btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-1 rounded-pill fw-semibold">
                                <i class="feather-user"></i> Шахсий профилга кириш
                            </a>
                            <a href="#" class="btn btn-sm btn-danger w-100 d-flex align-items-center justify-content-center gap-1 text-white rounded-pill fw-semibold" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="feather-log-out"></i> Тизимдан чиқиш
                            </a>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
            <!--! [End] Header Right !-->
        </div>

    </div>
</header>
