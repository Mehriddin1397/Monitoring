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
                    @endif
                @endauth
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{route('tasks.index')}}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-layout"></i></span>
                        <span class="nxl-mtext">Топшириқлар</span><span class="nxl-arrow"></span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</nav>
