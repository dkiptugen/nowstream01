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
    <link rel="icon" href="{{ asset('assets/images/logo-icon.png') }}" type="image/png" />
    @include('Frontend.includes.meta')
    <!--plugins-->
    <link href="{{ asset('frontend-assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('frontend-assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('frontend-assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('frontend-assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    <!-- loader-->
    <link href="{{ asset('frontend-assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('frontend-assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('frontend-assets/css/bootstrap.min.cs') }}s" rel="stylesheet">
    <link href="{{ asset('frontend-assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('frontend-assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend-assets/css/icons.css') }}" rel="stylesheet">

    <style>
        .dark-mode-icon.sun {
            display: none;
        }
    </style>
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/header-colors.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/frontend.css') }}??v46" />
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}" />

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
        {{ $title ?? 'Nowstream'}}
    </title>



</head>

<body>

    <!--wrapper-->
    <div class="wrapper  app-container">

        <div class="header-wrapper">
            <!--start header -->
            <header>
                <div class="topbar d-flex align-items-center">
                    <nav class="navbar navbar-expand gap-3 px-2">

                        <div class="topbar-logo-header d-flex align-content-center text-left">
                            <a href="{{url('/')}}">
                                <img src="{{ asset('nowstream-light.png') }}" class="logo-icon" alt="Baze Live Logo">
                            </a>
                        </div>
                        <!-- <div class="mobile-toggle-menu d-block d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><i class='bx bx-menu'></i></div> -->
                        <div class="search-bar d-lg-block d-none">
                            <a href="{{route('search')}}" class="btn d-flex align-items-center"><i
                                    class='bx bx-search'></i>Search</a>
                        </div>
                        <div class="top-menu ms-auto">
                            <ul class="navbar-nav align-items-center gap-1">
                                <li class="nav-item dropdown dropdown-app d-none">
                                    <div class="dropdown-menu dropdown-menu-end p-0">
                                        <div class="app-container p-2 my-2">

                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item dark-mode  d-sm-flex">
                                    <a class="nav-link dark-mode-icon moon" href="javascript:">
                                        <i class='bx bx-moon'></i>
                                    </a>
                                </li>
                                <li class="nav-item dark-mode d-sm-flex">
                                    <a class="nav-link dark-mode-icon sun" href="javascript:">
                                        <i class='bx bx-sun'></i>
                                    </a>
                                </li>
                                <li class="nav-item mobile-search-icon d-flex d-lg-none">
                                    <!-- <a class="nav-link" href="{{route('search')}}"><i class='bx bx-search'></i>
                                    </a> -->
                                        @php
                                        use App\Models\Event;
                                        $current_event = Event::orderBy('created_at', 'asc')->first();
                                        @endphp
                                    <a href="{{ url("/event/{$current_event->id}/{$current_event->slug}") }}" class="btn btn-danger px-2 d-inline-flex align-items-center gap-2"
                                aria-label="buttons">Buy Ticket</a>
                                </li>

                            </ul>
                        </div>
                        <div class="user-box dropdown px-3 d-none d-md-flex">
                            <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @guest

                                    <div class="user-info">
                                        <a class="dropdown-item d-flex align-items-center pe-3"
                                            href="{{ route('user.login') }}"><i
                                                class="bx bx-log-in-circle fs-5"></i><span>Login</span></a>
                                    </div>
                                @else
                                    <img src="{{ Auth::user()->image ??  asset('avatar.png')}} " class="user-img" alt="user avatar">
                                    <div class="user-info">
                                        <p class="user-name mb-0">
                                            {{ Auth::user()->name }}
                                        </p>
                                    </div>
                                @endguest
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @guest
                                    @if (Route::has('user.login'))
                                        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('user.login') }}"><i
                                                    class="bx bx-log-in-circle fs-5"></i><span>Login</span></a></li>
                                    @endif
                                    @if (Route::has('user.register'))
                                        <li><a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('user.register') }}"><i
                                                    class="bx bx-user-plus fs-5"></i><span>Register</span></a></li>
                                    @endif
                                @else
                                    <li><a class="dropdown-item d-flex align-items-center"
                                            href="{{ route('profile.show') }}"><i
                                                class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                                    <li>
                                        <div class="dropdown-divider mb-0"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('user.logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-log-out-circle"></i><span>Logout</span>
                                        </a>
                                    </li>
                                @endguest
                            </ul>
                        </div>

                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>


                    </nav>
                </div>
            </header>
            <!--end header -->
            @include('Frontend.includes.nav')
        </div>
