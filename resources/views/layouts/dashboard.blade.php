<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Codbit Foods</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="font/iconsmind/style.css" />
    <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css" />

    <link rel="stylesheet" href="css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/dore.light.red.min.css" />
    <style>
        .round{
            border-radius: 50%;
        }
    </style>
     @yield('styles')
</head>

<body id="app-container" class="menu-default show-spinner">
    <nav class="navbar fixed-top">
        <div class="d-flex align-items-center navbar-left">
            <a href="#" class="menu-button d-none d-md-block">
                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                    <rect x="0.48" y="0.5" width="7" height="1" />
                    <rect x="0.48" y="7.5" width="7" height="1" />
                    <rect x="0.48" y="15.5" width="7" height="1" />
                </svg>
                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                    <rect x="1.56" y="0.5" width="16" height="1" />
                    <rect x="1.56" y="7.5" width="16" height="1" />
                    <rect x="1.56" y="15.5" width="16" height="1" />
                </svg>
            </a>

            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1" />
                    <rect x="0.5" y="7.5" width="25" height="1" />
                    <rect x="0.5" y="15.5" width="25" height="1" />
                </svg>
            </a>

            <div class="search" data-search-path="/search?q=">
                <input placeholder="Search...">
                <span class="search-icon">
                    <i class="simple-icon-magnifier"></i>
                </span>
            </div>
        </div>

        <div class="navbar-right">
            <div class="header-icons d-inline-block align-middle">
                <div class="position-relative d-inline-block">
                    <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="simple-icon-bell"></i>
                        <span class="count">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute" id="notificationDropdown">

                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">General Mosquito just placed a request for to
                                        register his restaurant</p>
                                    <p class="text-muted mb-0 text-small">09.04.2019 - 12:45</p>
                                </a>
                            </div>
                        </div>

                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">K Mole has requested for a password reset</p>
                                    <p class="text-muted mb-0 text-small">09.04.2019 - 12:45</p>
                                </a>
                            </div>
                        </div>


                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">New client email received</p>
                                    <p class="text-muted mb-0 text-small">09.04.2019 - 12:45</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                    <i class="simple-icon-size-fullscreen"></i>
                    <i class="simple-icon-size-actual"></i>
                </button>

            </div>

            <div class="user d-inline-block">
                <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="name">{{ucfirst(Auth::user()->firstname)}} {{ucfirst(Auth::user()->lastname)}}</span>
                    <span>
                        <img class="round" height="30" style="width: 30px" avatar="{{Auth::user()->firstname}} {{Auth::user()->lastname}}" />
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-3">
                    <a class="dropdown-item" href="#">My account &nbsp;<i class="simple-icon-user"></i></a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}  &nbsp;<i class="simple-icon-logout"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div class="sidebar">
        <div class="main-menu">
            <div class="scroll">
                <ul class="list-unstyled">
                    <li class="<?php if($page=='dashboard'){echo 'active'; } ?>" >
                        <a href="#">
                            <i class="iconsmind-Home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if($page=='orders'){echo 'active'; } ?>" >
                        <a href="#">
                            <i class="iconsmind-Cash-register2"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="<?php if($page=='menu'){echo 'active'; } ?>" >
                        <a href="#">
                            <i class="iconsmind-Hamburger"></i>
                            <span>Menu Items</span>
                        </a>
                    </li>
                    <li class="<?php if($page=='categories'){echo 'active'; } ?>" >
                        <a href="/categories">
                            <i class="iconsmind-Check"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="<?php if($page=='reports'){echo 'active'; } ?>" >
                        <a href="#">
                            <i class="iconsmind-Pie-Chart3"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <main>
        @yield('content')
    </main>

    <script src="js/vendor/jquery-3.3.1.min.js"></script>
    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/perfect-scrollbar.min.js"></script>
    <script src="js/vendor/mousetrap.min.js"></script>
    <script src="js/vendor/vue.js"></script>
    @yield('scripts')
    <script src="js/dore.script.js"></script>
    <script src="js/scripts.js"></script>
    @yield('vueApp')
</body>

</html>