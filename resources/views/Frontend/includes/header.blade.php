<!Doctype html>
<html lang="en">

<head>
    <meta name="google-site-verification" content="_ZDQhYrLmRoWYQaAuG5Wi8jKbP0h9M-vkAgRuomujYM" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZYTX2YPFH4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-ZYTX2YPFH4');
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />
    @include('Frontend.includes.meta')
    <!--plugins-->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/animate.min.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/flaticon.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/odometer.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/aos.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/slick.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/default.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/css/responsive.css">
    <style>
        .page-wrapper {
            overflow-y: scroll !important;
        }
    </style>

    @yield('header')
    <script>
        (function () {
            const storageKey = 'theme';
            const darkThemeClass = 'dark-theme';
            const storedTheme = localStorage.getItem(storageKey);
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (storedTheme === 'dark' || (!storedTheme && systemPrefersDark)) {
                document.documentElement.classList.add(darkThemeClass);
            }
        })();
    </script>

    <title>
        {{ $title ?? 'Baze Live'}}
    </title>



</head>

<body>

    <!-- preloader -->
    <div id="preloader">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <img src="img/preloader.svg" alt="">
            </div>
        </div>
    </div>
    <!-- preloader-end -->

    <!-- Scroll-top -->
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="fas fa-angle-up"></i>
    </button>
    <!-- Scroll-top-end-->

    <!-- header-area -->
    <header>
        <div id="sticky-header" class="menu-area transparent-header">
            <div class="container custom-container">
                <div class="row">
                    <div class="col-12">
                        <div class="mobile-nav-toggler"><i class="fas fa-bars"></i></div>
                        <div class="menu-wrap">
                            <nav class="menu-nav show">
                                <div class="logo">
                                    <a href="{{url('/')}}">
                                        <img src="{{ asset('logo1.png') }}" class="logo-icon" alt="Baze Live Logo">
                                    </a>
                                </div>
                                @include('Frontend.includes.nav')

                                <div class="header-action d-none d-md-block">
                                    <ul>
                                        <li class="header-search"><a href="#" data-toggle="modal"
                                                data-target="#search-modal"><i class="fas fa-search"></i></a></li>

                                        <li class="menu-item-has-children header-lang d-none">
                                            <a class="d-flex align-items-center nav-link  gap-3 dropdown-toggle-nocaret"
                                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                @guest

                                                    <div class="user-info">
                                                        <a class="dropdown-item d-flex align-items-center pe-3"
                                                            href="{{ route('user.login') }}">
                                                            <div class="icon text-white"><i class="flaticon-globe"></i>
                                                                Login</div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <img src="{{ Auth::user()->image ?? asset('avatar.png')}} "
                                                        class="user-img" alt="user avatar">
                                                    <div class="user-info">
                                                        <p class="user-name mb-0">
                                                            {{ Auth::user()->name }}
                                                        </p>
                                                    </div>
                                                @endguest
                                            </a>
                                            <ul class="submenu d-none">
                                                @guest
                                                    @if (Route::has('login'))
                                                        <li><a class="dropdown-item d-flex align-items-center"
                                                                href="{{ route('user.login') }}"><i
                                                                    class="bx bx-log-in-circle fs-5"></i><span>Login</span></a>
                                                        </li>
                                                    @endif
                                                    @if (Route::has('register'))
                                                        <li><a class="dropdown-item d-flex align-items-center"
                                                                href="{{ route('user.register') }}"><i
                                                                    class="bx bx-user-plus fs-5"></i><span>Register</span></a>
                                                        </li>
                                                    @endif
                                                @else
                                                    <li><a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('profile.show') }}"><i
                                                                class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                                                    <li>
                                                        <div class="dropdown-divider mb-0"></div>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('user.logout') }}"
                                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                            <i class="bx bx-log-out-circle"></i><span>Logout</span>
                                                        </a>
                                                    </li>
                                                @endguest
                                            </ul>
                                        </li>

                                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                        <li class="header-btn"> 
                                            <a href="{{ route('events') }}"
                                                class="btn btn-danger btn-sm shadow-sm px-2 d-inline-flex align-items-center gap-2 border-0 rounded-0"
                                                aria-label="buttons">Buy Ticket</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
 
                        <!-- Mobile Menu  -->
                        <div class="mobile-menu">
                            <div class="close-btn"><i class="fas fa-times"></i></div>

                            <nav class="menu-box">
                                <div class="nav-logo"><a href="{{url('/')}}">
                                        <img src="{{ asset('logo1.png') }}" class="logo-icon" alt="Baze Live Logo">
                                    </a>
                                </div>
                                <div class="menu-outer">
                                    <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                                </div>
                                <div class="social-links">
                                    <ul class="clearfix">
                                        <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                                        <li><a href="#"><span class="fab fa-facebook-square"></span></a></li>
                                        <li><a href="#"><span class="fab fa-pinterest-p"></span></a></li>
                                        <li><a href="#"><span class="fab fa-instagram"></span></a></li>
                                        <li><a href="#"><span class="fab fa-youtube"></span></a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="menu-backdrop"></div>
                        <!-- End Mobile Menu -->

                        <!-- Modal Search -->
                        <div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form>
                                        <input type="text" placeholder="Search here...">
                                        <button><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Search-end -->

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-area-end -->


    <!-- main-area -->
    <main>