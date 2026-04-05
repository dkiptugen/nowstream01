<!Doctype html>
<html lang="en">

<head>
    <meta name="google-site-verification" content="_ZDQhYrLmRoWYQaAuG5Wi8jKbP0h9M-vkAgRuomujYM" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZYTX2YPFH4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
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
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

        .navbar-wrap>ul>li>a {
            display: inline-flex;
        }

        .theme-switcher-menu .submenu {
            min-width: 180px;
            padding: 10px;
            border-radius: 16px;
            background: rgba(10, 17, 26, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        .theme-switcher-trigger,
        .theme-switcher-option {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .theme-switcher-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .theme-switcher-option {
            width: 100%;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 12px;
            color: #f5f7fb;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .theme-switcher-option:hover,
        .theme-switcher-option.is-active {
            background: rgba(255, 210, 79, 0.16);
            color: #ffd24f;
        }

        .theme-switcher-option-check {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .theme-switcher-option.is-active .theme-switcher-option-check {
            opacity: 1;
        }

        .header-cart-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .header-cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #ffd24f;
            color: #09131d;
            font-size: 11px;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            box-shadow: 0 6px 18px rgba(255, 210, 79, 0.3);
        }

        .header-cart-count.d-none {
            display: none !important;
        }
    </style>

    @yield('header')
    <script>
        (function() {
            const storageKey = 'theme-preference';
            const legacyStorageKey = 'theme';
            const darkThemeClass = 'dark-theme';
            const lightThemeClass = 'light-theme';
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const legacyValue = localStorage.getItem(legacyStorageKey);
            const storedTheme = localStorage.getItem(storageKey) || legacyValue || 'system';

            const applyTheme = (theme) => {
                const resolvedTheme = theme === 'system'
                    ? (mediaQuery.matches ? 'dark' : 'light')
                    : theme;

                document.documentElement.classList.toggle(darkThemeClass, resolvedTheme === 'dark');
                document.documentElement.classList.toggle(lightThemeClass, resolvedTheme === 'light');
                document.documentElement.setAttribute('data-theme-preference', theme);
                document.documentElement.setAttribute('data-theme-resolved', resolvedTheme);
            };

            applyTheme(storedTheme);
            localStorage.setItem(storageKey, storedTheme);
            localStorage.removeItem(legacyStorageKey);
        })();
    </script>

    <title>
        {{ $title ?? 'Streamer'}}
    </title>

    <style>
        /* ===============================
           Wrapper (full width)
        =================================*/
        .pcar-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        /* ===============================
           Carousel viewport (container width)
        =================================*/
        .pcar {
            position: relative;
            overflow: hidden;
        }

        /* ===============================
           Track
        =================================*/
        .pcar-track {
            display: flex;
            gap: 16px;
            transition: transform 0.5s ease;
            will-change: transform;
        }

        /* ===============================
           Items
        =================================*/
        .pcar-item {
            flex: 0 0 auto;
        }

        /* ===============================
           Overlay (outside container)
        =================================*/
        .pcar-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            background: rgb(17 16 24 / 89%);
            pointer-events: none;
            z-index: 95;
            display: none;
        }

        /* Left / Right positioning */
        .pcar-overlay-left {
            left: 0;
        }

        .pcar-overlay-right {
            right: 0;
        }

        /* Desktop only */
        @media (min-width: 992px) {

            .pcar-overlay {
                display: block;
                width: calc((100% - var(--pcar-container-width, 1320px)) / 2);
            }
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: #4f46e5;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-initials {
            letter-spacing: 1px;
        }

        .play-icon, .play {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 30px;
            display: none;
        }
       .movie-poster.radio-poster img {
    max-width: 100%;
    border-radius: 5px;
    /* aspect-ratio: auto !important; */
    background: #2b2f38;
    object-fit: contain !important;
}
    </style>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FT13EMDEPD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FT13EMDEPD');
</script>

</head>

<body>
    <script>
        (function() {
            const storageKey = 'theme-preference';
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const optionSelector = '[data-theme-value]';

            const getThemePreference = () => localStorage.getItem(storageKey) || 'system';

            const applyTheme = (theme) => {
                const resolvedTheme = theme === 'system'
                    ? (mediaQuery.matches ? 'dark' : 'light')
                    : theme;

                document.documentElement.classList.toggle('dark-theme', resolvedTheme === 'dark');
                document.documentElement.classList.toggle('light-theme', resolvedTheme === 'light');
                document.documentElement.setAttribute('data-theme-preference', theme);
                document.documentElement.setAttribute('data-theme-resolved', resolvedTheme);
            };

            const updateThemeSwitcherUI = () => {
                const currentTheme = getThemePreference();
                document.querySelectorAll(optionSelector).forEach((option) => {
                    const isActive = option.getAttribute('data-theme-value') === currentTheme;
                    option.classList.toggle('is-active', isActive);
                    option.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
            };

            const setThemePreference = (theme) => {
                localStorage.setItem(storageKey, theme);
                applyTheme(theme);
                updateThemeSwitcherUI();
            };

            document.addEventListener('DOMContentLoaded', () => {
                updateThemeSwitcherUI();
            });

            document.addEventListener('click', (event) => {
                const option = event.target.closest(optionSelector);

                if (!option) {
                    return;
                }

                event.preventDefault();
                setThemePreference(option.getAttribute('data-theme-value'));
            });

            const syncSystemTheme = () => {
                if (getThemePreference() === 'system') {
                    applyTheme('system');
                    updateThemeSwitcherUI();
                }
            };

            if (typeof mediaQuery.addEventListener === 'function') {
                mediaQuery.addEventListener('change', syncSystemTheme);
            } else if (typeof mediaQuery.addListener === 'function') {
                mediaQuery.addListener(syncSystemTheme);
            }
        })();
    </script>


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
                                        <img src="{{ asset('assets/img/logo/logo.png') }}" class="logo-icon" alt="Streamer Logo" height="40">
                                    </a>
                                </div>
                                @include('Frontend.includes.nav')

                                <div class="header-action d-none d-md-block">
                                    <ul>
                                        <li class="header-search"><a href="#" data-bs-toggle="modal"
                                                data-bs-target="#search-modal"><i class="fas fa-search"></i></a></li>
                                        <li class="header-search"><a href="{{ route('video.myfavorite') }}"><i class="fas fa-heart"></i></a></li>
                                        <li class="header-search"><a href="{{ route('watch.content') }}"><i class="fas fa-history"></i></a></li>
                                        <li class="header-search">
                                            <a href="{{ route('cart.index') }}" class="header-cart-link">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span id="header-cart-count" class="header-cart-count {{ ($headerCartCount ?? 0) > 0 ? '' : 'd-none' }}">
                                                    {{ $headerCartCount ?? 0 }}
                                                </span>
                                            </a>
                                        </li>

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
                                        <img src="{{ asset('logo1.png') }}" class="logo-icon" alt="Streamer Logo">
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
                        <div class="modal fade" id="search-modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('search') }}" method="GET">
                                        <input type="text" name="query" placeholder="Search here..." value="{{ request('query') }}">
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
