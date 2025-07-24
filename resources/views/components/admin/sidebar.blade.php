<nav class="nxl-navigation">
    <div class="navbar-wrapper" style="background-color: #dcddea">
        <div class="m-header">
            <a href="#" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{asset('/img/logo/3.png')}}" alt="" class="logo logo-lg"/>
                <img src="{{asset('/img/logo/3.png')}}" alt="" class="logo logo-sm"/>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                {{--                <li class="nxl-item nxl-hasmenu">--}}
                {{--                    <a href="{{route('dashboard')}}" class="nxl-link">--}}
                {{--                        <span class="nxl-micon"><i class="feather-airplay"></i></span>--}}
                {{--                        <span class="nxl-mtext">Monitoring</span><span class="nxl-arrow"></span>--}}
                {{--                    </a>--}}
                {{--                </li>--}}
                @auth
                    @if (auth()->user()->role === 'admin')
                        <li class="nxl-item nxl-hasmenu">
                            <a href="{{route('users.index')}}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-airplay"></i></span>
                                <span class="nxl-mtext">Xodimlar</span><span class="nxl-arrow"></span>
                            </a>
                        </li>
                        <li class="nxl-item nxl-hasmenu">
                            <a href="{{route('categories.index')}}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-layout"></i></span>
                                <span class="nxl-mtext"> <strong>Kategoriyalar</strong></span><span class="nxl-arrow"></span>
                            </a>
                        </li>
                    @endif
                        <li class="nxl-item nxl-hasmenu">
                            <a href="{{route('tasks.index')}}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-layout"></i></span>
                                <span class="nxl-mtext">Топшириқлар</span><span class="nxl-arrow"></span>
                            </a>
                        </li>
                    @if (in_array(auth()->user()->role, ['admin', 'boshliq']))

                            <li class="nxl-item nxl-hasmenu">
                            <a href="{{route('monitoring.umumiy')}}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-airplay"></i></span>
                                <span class="nxl-mtext">Ходимлар статистикаси</span><span class="nxl-arrow"></span>
                            </a>
                        </li>
                        @endif

                @endauth
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-briefcase"></i></span>
                        <span class="nxl-mtext">Электрон кутибхона</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('documents.byCategory', 4) }}">Президент фармон ва қарорлари</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('documents.byCategory', 5) }}">Вазир.Махкамасининг фармон,қарор топшириқлари</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('documents.byCategory', 6) }}">Институт ҳақидаги барча маълумотлар</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('documents.byCategory', 7) }}">Лойиха ва илмий,амалий тадқиқотлар</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('documents.byCategory', 8) }}">Ҳалқаро хорижий сафарлар ва ҳамкорлик </a></li>
                    </ul>
                </li>


            </ul>
        </div>
    </div>
</nav>
