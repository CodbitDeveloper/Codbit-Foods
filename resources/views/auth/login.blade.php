<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Codbit Foods</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="font/iconsmind/style.css" />
    <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css" />

    <link rel="stylesheet" href="css/vendor/bootstrap-stars.css" />
    <link rel="stylesheet" href="css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="css/vendor/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/vendor/bootstrap-stars.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/dore.light.red.css" />
    <link rel="stylesheet" href="css/vendor/bootstrap-float-label.min.css" />
</head>


<body class="show-spinner">
    <div class="landing-page">
        <div class="mobile-menu">
            <a href="LandingPage.Home.html" class="logo-mobile">
                <span></span>
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/">HOME</a>
                </li>
            </ul>
        </div>
        <div class="main-container">
            <nav class="landing-page-nav">
                <div class="container d-flex align-items-center justify-content-between">
                    <ul class="navbar-nav d-none d-lg-flex flex-row">
                        <li class="nav-item">
                            <a href="/">HOME</a>
                        </li>
                    </ul>
                    <a href="#" class="mobile-menu-button">
                        <i class="simple-icon-menu"></i>
                    </a>
                </div>
            </nav>
            <div class="content-container">
                <div class="section home subpage-long">
                    <div class="container">
                        <div class="row home-row mb-0">
                            <div class="col-12 col-lg-6 col-xl-4 col-md-12">
                                <div class="home-text">
                                    <div class="display-1">
                                        Login
                                    </div>
                                    <p class="white mb-5">
                                       Enter your user id and password to login.<br>
                                       If you have forgotten any of these contact your administrator.
                                    </p>


                                    <form class="dark-background" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <label class="form-group has-top-label">
                                            <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" />
                                            <span>USER ID</span>
                                            @if ($errors->has('username'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </label>

                                        <label class="form-group has-top-label">
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required />
                                            <span>PASSWORD</span>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </label>

                                        <!--label class="form-group">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span>Remember Me</span>
                                        </label-->

                                        <button class="btn btn-outline-semi-light btn-xl mt-4">LOGIN</button>

                                    </form>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="js/vendor/jquery-3.3.1.min.js"></script>
    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/owl.carousel.min.js"></script>
    <script src="js/vendor/jquery.barrating.min.js"></script>
    <script src="js/vendor/jquery.barrating.min.js"></script>
    <script src="js/vendor/landing-page/headroom.min.js"></script>
    <script src="js/vendor/landing-page/jQuery.headroom.js"></script>
    <script src="js/vendor/landing-page/jquery.scrollTo.min.js"></script>
    <script src="js/vendor/landing-page/jquery.autoellipsis.js"></script>
    <script src="js/dore.scripts.landingpage.js"></script>
    <script src="js/scripts.single.theme.js"></script>
</body>

</html>