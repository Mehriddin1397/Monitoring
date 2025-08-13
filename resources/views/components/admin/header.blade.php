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
{{--                <div class="nxl-h-item dark-light-theme">--}}
{{--                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button" style="display: none" >--}}
{{--                        <i class="feather-moon"></i>--}}
{{--                    </a>--}}
{{--                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">--}}
{{--                        <i class="feather-sun"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <img src="{{ asset('assets/images/8214212.png') }}" alt="user-image" class="img-fluid user-avtar me-0" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/logoo.jfif') }}" alt="user-image" class="img-fluid user-avtar" />
                                <div class="ms-2" style="max-width: 180px;">
                                    <h6 class="text-dark mb-0 text-break" style="white-space: normal; word-break: break-word;">
                                        {{ Auth::user()->name }}
                                        <span class="badge bg-soft-success text-success ms-1">PRO</span>
                                    </h6>
                                    <span class="fs-12 fw-medium text-muted text-break" style="white-space: normal; word-break: break-word;">
                        {{ Auth::user()->email }}
                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="feather-log-out"></i>
                            <span>Logout</span>
                        </a>

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
