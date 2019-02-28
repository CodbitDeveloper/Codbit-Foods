<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Codbit Foods</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="font/iconsmind/style.css" />
  <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css" />

  <link rel="stylesheet" href="css/vendor/bootstrap-stars.css" />
  <link rel="stylesheet" href="css/vendor/bootstrap.min.css" />
  <link rel="stylesheet" href="css/vendor/owl.carousel.min.css" />
  <link rel="stylesheet" href="css/vendor/bootstrap-stars.css" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/dore.light.red.css" />
</head>

<body class="show-spinner">
  <div class="landing-page">
    <div class="mobile-menu">
      <a href="#home" class="logo-mobile scrollTo">
        <span></span>
      </a>
      <ul class="navbar-nav">
        <li class="nav-item"><a href="#features" class="scrollTo">FEATURES</a></li>
        <li class="nav-item"><a href="#pricing" class="scrollTo">PRICING</a></li>
        <li class="nav-item">
          <div class="separator"></div>
        </li>
        <li class="nav-item mt-2"><a href="/login">SIGN IN</a></li>
        <li class="nav-item"><a href="#purchase" class="scrollTo">BUY</a></li>
      </ul>
    </div>

    <div class="main-container">
      <nav class="landing-page-nav">
        <div class="container d-flex align-items-center justify-content-between">
          <a class="navbar-logo pull-left scrollTo" href="#home">
            <span class="white"></span>
            <span class="dark"></span>
          </a>
          <ul class="navbar-nav d-none d-lg-flex flex-row">
            <li class="nav-item"><a href="#features" class="scrollTo">FEATURES</a></li>
            <li class="nav-item"><a href="#pricing" class="scrollTo">PRICING</a></li>
                      
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                    <li class="nav-item mr-3"><a href="/home">DASHBOARD</a></li>
                    @else
                        <li class="nav-item mr-3"><a href="/login">SIGN IN</a></li>  
                    @endauth
                </div>
            @endif
            <li class="nav-item pl-2">
              <a class="btn btn-outline-semi-light btn-sm pr-4 pl-4 scrollTo" href="#purchase">BUY</a>
            </li>
          </ul>
          <a href="#" class="mobile-menu-button">
            <i class="simple-icon-menu"></i>
          </a>
        </div>
      </nav>

      <div class="content-container" id="home">
        <div class="section home">
          <div class="container">
            <div class="row home-row">
              <div class="col-12 d-block d-md-none">
                <a href="#">
                  <img alt="mobile hero" class="mobile-hero" src="img/landing-page/home-hero-mobile.png" />
                </a>
              </div>

              <div class="col-12 col-xl-4 col-lg-5 col-md-6">
                <div class="home-text">
                  <div class="display-1">MANAGING YOUR ORDERS <br />JUST GOT EASIER</div>
                  <p class="white mb-5">
                    Codbit Foods &reg; gives you an all in one platform to handle all your restaurant's processes.
                  </p>
                  <a class="btn btn-outline-semi-light btn-xl" href="#">BUY
                    NOW</a>
                </div>
              </div>
              <div class="col-12 col-xl-7 offset-xl-1 col-lg-7 col-md-6  d-none d-md-block">
                <a href="#">
                  <img alt="hero" src="img/landing-page/screenshot.PNG" />
                </a>
              </div>
            </div>

            <div class="row">
              <div class="col-12 p-0">
                <div class="owl-container">
                  <div class="owl-carousel home-carousel">
                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <i class="iconsmind-Full-Cart large-icon"></i>
                          <h5 class="mb-0 font-weight-semibold">
                            Receive and manage orders
                          </h5>
                        </div>
                        <div>
                          <p class="detail-text">
                            Codbit Foods &reg; makes handling all your orders easy.
                            Receive orders through the client app or at the counter. Your choice.
                          </p>
                        </div>
                        <a class="btn btn-link font-weight-semibold" href="#"></a>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <i class="iconsmind-Consulting large-icon"></i>
                          <h5 class="mb-0 font-weight-semibold">
                            Engage with customers
                          </h5>
                        </div>
                        <div>
                          <p class="detail-text">
                            Your customers are your money. Increase customer loyalty through the client app.
                          </p>
                        </div>
                        <a class="btn btn-link font-weight-semibold" href="#"></a>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <i class="iconsmind-French-Fries large-icon"></i>
                          <h5 class="mb-0 font-weight-semibold">
                            Manage menu
                          </h5>
                        </div>
                        <div>
                          <p class="detail-text">
                            What's your restaurant without it's yummy menu?
                            Codbit Foods gives you tools to easily manage your meals and meal categories.
                          </p>
                        </div>
                        <a class="btn btn-link font-weight-semibold" href="#"></a>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <i class="iconsmind-Chef large-icon"></i>
                          <h5 class="mb-0 font-weight-semibold">
                            Manage staff
                          </h5>
                        </div>
                        <div>
                          <p class="detail-text">
                            Codbit Foods &reg; provides you with a management area for all your staff.
                            Add and manage all your employee information.
                          </p>
                        </div>
                        <a class="btn btn-link font-weight-semibold" href="#"></a>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <i class="iconsmind-Handshake large-icon"></i>
                          <h5 class="mb-0 font-weight-semibold">
                            Loyalty programs
                          </h5>
                        </div>
                        <div>
                          <p class="detail-text">
                            Everybody loves discounts and free stuff.
                            Codbit Foods &reg; allows you to manage your discounts and promotions
                          </p>
                        </div>
                        <a class="btn btn-link font-weight-semibold" href="#"></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="section">
          <div class="container" id="features">
            <div class="row">
              <div class="col-12 offset-0 col-lg-8 offset-lg-2 text-center">
                <h1>What Codbit Food&reg; Offers</h1>
                <p>
                  Our creative team tried to come up with a solution that is
                  very intuitive and easy to use. We listed a bunch of features
                  that would be needed in a food ordering environment and narrowed
                  it down to a simple yet very efficient system that will do a terrific
                  job for both small and large multi chain restaurants.
                </p>
              </div>
            </div>

            <div class="row feature-row">
              <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center">
                <div class="d-flex">
                  <div class="feature-icon-container">
                    <div class="icon-background">
                      <i class="fas fa-fw fa-ban"></i>
                    </div>
                  </div>
                  <div class="feature-text-container">
                    <h2>Administrative dashboard</h2>
                    <p>
                      The administrative dashboard provides you with all the
                      tools you need to manage your restaurant. <br />
                    </p>
                    <p>Add and manage them orders directly
                      from the backend or receive orders made via
                      the client app.
                    </p>
                    <p>
                      Add menu items and item categorizations.
                    </p>
                    <p>
                      Manage your employees and delivery workers.
                    </p>
                    <p>
                      Embark on loyalty programs with deals and promotions.
                    </p>
                    <br />
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6 offset-lg-1 offset-md-0 position-relative">
                <div class="background-item-1"></div>
                <img alt="feature" class="feature-image-right feature-image-charts position-relative" src="img/landing-page/dashboard.svg" />
              </div>
            </div>

            <div class="row feature-row">
              <div class="col-12 col-md-6 col-lg-6 order-2 order-md-1">
                <img alt="feature" class="feature-image-left feature-image-charts" src="img/landing-page/mobile.svg" />
              </div>

              <div class="col-12 col-md-6 offset-md-0 col-lg-5 offset-lg-1 d-flex align-items-center order-1 order-md-2">
                <div class="d-flex">
                  <div class="feature-icon-container">
                    <div class="icon-background">
                      <i class="fas fa-fw fa-ban"></i>
                    </div>
                  </div>
                  <div class="feature-text-container">
                    <h2>Client Mobile App</h2>
                    <p>
                      Your clients are your business, they need to feel satisfied
                      with the services you provide. Maintaining customer satisfaction
                      has never been easier with our client mobile application.
                      <br />
                      <br />
                      We created an app that is both beautiful and simple to use.
                      <br />
                      <br />
                      Customers are able to search for and order for meals at
                      their own comfort. They receive notification updates on
                      the status of their orders. What's more, you can push loyalty
                      campaigns to all your customer or that customer who's been loyal to
                      you for a really long time. Finally, an easy way to boost your
                      customer loyalty.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row feature-row">
              <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center">
                <div class="d-flex">
                  <div class="feature-icon-container">
                    <div class="icon-background">
                      <i class="fas fa-fw fa-ban"></i>
                    </div>
                  </div>
                  <div class="feature-text-container">
                    <h2>Simplified payments</h2>
                    <p>
                      Receiving payments has been simplified with our payment
                      gateway integrations. <br />
                      <br />
                      You can receive payments via a number of payment channels including
                      card and mobile money. <br /><br />
                      No need to hassle for a mobile money account. Receive payments via mobile money
                      and get it in your bank account.
                      <br />
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6 offset-lg-1 offset-md-0 ">
                <img alt="feature" class="feature-image-right feature-image-charts" src="img/landing-page/payment.svg" />
              </div>
            </div>
          </div>
        </div>

        <div class="section mb-0 background">
          <div class="container" id="pricing">

            <div class="row">
              <div class="col-12 offset-0 col-lg-8 offset-lg-2 text-center">
                <h1>Pricing</h1>
                <p>
                  We know it's difficult to hop onto a new system for your entire business processes that's why
                  we beat down our prices to something everyone can afford.
                </p>
              </div>
            </div>


            <div class="row row-eq-height price-container mt-5">

              <div class="col-md-12 col-lg-4 mb-4 price-item">
                <div class="card">
                  <div class="card-body pt-5 pb-5 d-flex flex-lg-column flex-md-row flex-sm-row flex-column">
                    <div class="price-top-part">
                      <i class="iconsmind-Male large-icon"></i>
                      <h5 class="mb-0 font-weight-semibold color-theme-1 mb-4">LITE</h5>
                      <p class="text-large mb-2 text-default">GHS 399</p>
                      <p class="text-muted text-small">Per Year</p>
                    </div>
                    <div class="pl-3 pr-3 pt-3 pb-0 d-flex price-feature-list flex-column">
                      <ul class="list-unstyled">
                        <li>
                          <p class="mb-0 ">
                            Only one branch
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Client Application
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Unlimited Updates
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Free Support
                          </p>
                        </li>
                      </ul>
                      <div>
                        <a href="#" class="btn btn-link btn-empty btn-lg">BUY NOW <i class="simple-icon-arrow-right"></i></a>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="col-md-12 col-lg-4 mb-4 price-item">
                <div class="card">
                  <div class="card-body pt-5 pb-5 d-flex flex-lg-column flex-md-row flex-sm-row flex-column">
                    <div class="price-top-part">
                      <i class="iconsmind-MaleFemale large-icon"></i>
                      <h5 class="mb-0 font-weight-semibold color-theme-1 mb-4">ENTERPRISE</h5>
                      <p class="text-large mb-2 text-default">GHS 699</p>
                      <p class="text-muted text-small">Per Year</p>
                    </div>
                    <div class="pl-3 pr-3 pt-3 pb-0 d-flex price-feature-list flex-column">
                      <ul class="list-unstyled">
                        <li>
                          <p class="mb-0 ">
                            Up to 10 branches
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Client Application
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Unlimited Updates
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Free Support
                          </p>
                        </li>
                      </ul>
                      <div>
                        <a href="#" class="btn btn-link btn-empty btn-lg">BUY NOW <i class="simple-icon-arrow-right"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 col-lg-4 mb-4 price-item">
                <div class="card">
                  <div class="card-body pt-5 pb-5 d-flex flex-lg-column flex-md-row flex-sm-row flex-column">
                    <div class="price-top-part">
                      <i class="iconsmind-Mens large-icon"></i>
                      <h5 class="mb-0 font-weight-semibold color-theme-1 mb-4">DELUXE </h5>
                      <p class="text-large mb-2 text-default">1599</p>
                      <p class="text-muted text-small">User/Month 10+ Users</p>
                    </div>
                    <div class="pl-3 pr-3 pt-3 pb-0 flex-grow-1 d-flex price-feature-list flex-column">
                      <ul class="list-unstyled">
                        <li>
                          <p class="mb-0 ">
                            Unlimited branches
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Client Application
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Unlimited Updates
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Free Support
                          </p>
                        </li>
                        <li>
                          <p class="mb-0 ">
                            Client app customizations
                          </p>
                        </li>
                      </ul>

                      <div>
                        <a href="#" class="btn btn-link btn-empty btn-lg">BUY NOW <i class="simple-icon-arrow-right"></i></a>
                      </div>
                    </div>



                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>

        <div class="section footer mb-0" id="purchase">
          <div class="container">
            <div class="row footer-row">
              <div class="col-12 text-right">
                <a class="btn btn-circle btn-outline-semi-light footer-circle-button scrollTo" href="#home" id="footerCircleButton"><i
                    class="simple-icon-arrow-up"></i></a>
              </div>
              <div class="col-12 text-center footer-content">
                <div class="home-text">
                  <div class="display-1">
                    Are you interested?
                  </div>
                  <p class="white mb-5">
                    Leave us a message. An agent will contact you to get started.
                  </p>
                  <form class="dark-background">
                    <div class="form-row">
                      <div class="form-group has-top-label col-md-6">
                        <input class="form-control" />
                        <span>NAME</span>
                      </div>
                      <label class="form-group has-top-label col-md-6">
                        <input class="form-control" />
                        <span>E-MAIL</span>
                      </label>
                    </div>

                    <label class="form-group has-top-label col-md-12">
                      <textarea class="form-control" id="message" placeholder=""></textarea>
                      <span>DETAILS</span>
                    </label>
                    <button class="btn btn-outline-semi-light btn-xl mt-4">LOGIN</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="separator mt-5"></div>
          <div class="container copyright pt-5 pb-5">
            <div class="row">
              <div class="col-12"></div>
              <div class="col-6">
                <p class="mb-0">&copy; <a class="text-white" href="http://www.codbitgh.com" target="blank">Codbit Ghana
                    Limited</a></p>
              </div>
              <div class="col-6 text-right social-icons">
                <ul class="list-unstyled list-inline">
                  <li class="list-inline-item">
                    <a href="#"><i class="simple-icon-social-twitter"></i></a>
                  </li>
                </ul>
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

