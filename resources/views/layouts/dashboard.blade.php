<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Codbit Foods</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('font/simple-line-icons/css/simple-line-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('css/main.css')}}" />
    <link rel="stylesheet" href="{{asset('font/dashboard-icons/flaticon.css')}}"/>
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
                        <span class="count">{{Auth::user()->unreadNotifications->count()}}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute" id="notificationDropdown">
                        @foreach(Auth::user()->unreadNotifications as $notification)
                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="{{$notification->data['action']}}">
                                    <h6><b>{{$notification->data['title']}}</b></h6>
                                    <p class="mb-1 text-muted">{{$notification->data['message']}}</p>
                                    <p class="text-muted mb-0 text-small text-right"><i>{{Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</i></p>
                                </a>
                            </div>
                        </div>
                        @endforeach
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
                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
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
                        <a href="/home">
                            <i class="flaticon-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if($page=='orders'){echo 'active'; } ?>" >
                        <a href="/orders">
                            <i class="flaticon-order"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="<?php if($page=='items'){echo 'active'; } ?>" >
                        <a href="/menu">
                            <i class="flaticon-food"></i>
                            <span>Menu</span>
                        </a>
                    </li>
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='categories'){echo 'active'; } ?>" >
                        <a href="/categories">
                            <i class="flaticon-list"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    @endif
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='employees'){echo 'active'; } ?>" >
                        <a href="/employees">
                            <i class="flaticon-avatar"></i>
                            <span>Employees</span>
                        </a>
                    </li>
                    @endif
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='branches'){echo 'active'; } ?>" >
                        <a href="/branches">
                            <i class="flaticon-shop"></i>
                            <span>Branches</span>
                        </a>
                    </li>
                    @endif
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='reports'){echo 'active'; } ?>" >
                        <a href="/reports">
                            <i class="flaticon-graphic"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    @endif
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='feedback'){echo 'active'; } ?>" >
                        <a href="/feedback">
                            <i class="flaticon-heart"></i>
                            <span>Customer Feedback</span>
                        </a>
                    </li>
                    @endif
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='deals'){echo 'active'; } ?>" >
                        <a href="/deals">
                            <i class="flaticon-handshake"></i>
                            <span>Deals and Promotions</span>
                        </a>
                    </li>
                    @endif
                    @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                    <li class="<?php if($page=='dispatch'){echo 'active'; } ?>" >
                        <a href="/dispatch">
                            <i class="flaticon-delivery"></i>
                            <span>Dispatch</span>
                        </a>
                    </li>
                    @endif
                    <li class="<?php if($page==''){echo 'active'; } ?>" style="display:none">
                        <a href="#">
                            
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/vendor/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/vendor/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/mousetrap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.barrating.min.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('js/dore.script.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
    (function($) {
        if ($().dropzone) {
            Dropzone.autoDiscover = false;
        }
        
        var themeColorsDom =
        '<div class="theme-colors"> <div class="p-4"> <p class="text-muted mb-2">Light Theme</p> <div class="d-flex flex-row justify-content-between mb-4"> <a href="#" data-theme="dore.light.red.min.css" class="theme-color theme-color-red"></a> </div> <p class="text-muted mb-2">Dark Theme</p> <div class="d-flex flex-row justify-content-between"><a href="#" data-theme="dore.dark.red.min.css" class="theme-color theme-color-red"></a> </div> </div> <a href="#" class="theme-button"> <i class="simple-icon-settings"></i> </a> </div>';
        $("body").append(themeColorsDom);
        var theme = "dore.light.red.min.css";

        if (typeof Storage !== "undefined") {
            if (localStorage.getItem("theme")) {
            theme = localStorage.getItem("theme");
            }
        }

        $(".theme-color[data-theme='" + theme + "']").addClass("active");
        var baseUrl = "{{URL::asset('css/')}}";
        
        loadStyle(baseUrl +'/'+ theme , onStyleComplete);
        function onStyleComplete() {
            setTimeout(onStyleCompleteDelayed, 300);
        }

        function onStyleCompleteDelayed() {
            var $dore = $("body").dore();
        }

        $("body").on("click", ".theme-color", function(event) {
            event.preventDefault();
            var dataTheme = $(this).data("theme");
            if (typeof Storage !== "undefined") {
            localStorage.setItem("theme", dataTheme);
            window.location.reload();
            }
        });


        $(".theme-button").on("click", function(event) {
            event.preventDefault();
            $(this)
            .parents(".theme-colors")
            .toggleClass("shown");
        });
        $(document).on("click", function(event) {
            if (
            !(
                $(event.target)
                .parents()
                .hasClass("theme-colors") ||
                $(event.target)
                .parents()
                .hasClass("theme-button") ||
                $(event.target).hasClass("theme-button") ||
                $(event.target).hasClass("theme-colors")
            )
            ) {
            if ($(".theme-colors").hasClass("shown")) {
                $(".theme-colors").removeClass("shown");
            }
            }
        });
        })(jQuery);
    </script>
    @yield('customjs')
</body>

</html>