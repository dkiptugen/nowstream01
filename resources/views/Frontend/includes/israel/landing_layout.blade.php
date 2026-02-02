<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="google-site-verification" content="_ZDQhYrLmRoWYQaAuG5Wi8jKbP0h9M-vkAgRuomujYM" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZYTX2YPFH4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-ZYTX2YPFH4');
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="@yield('description', config('site.description'))" />
    <meta name="keywords" content="@yield('keywords', config('site.keywords'))" />
    <meta name="copyright" content="{{ config('site.name') }}">
    <meta name="author" content="{{ config('site.name') }}" />
    <meta name="application-name" content="@yield('title', config('site.title'))">

    <!--Facebook Tags-->
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ request()->fullUrl() }}" />
    <meta property="og:title" content="@yield('title', config('site.title'))" />
    <meta property="og:description" content="@yield('description', config('site.description'))" />
    <meta property="og:image" content="{{ config('site.image') }}" />
    <meta property="article:author" content="https://www.facebook.com/TODO" />
    <meta property="og:locale" content="en_UK" />
    <!--Twitter Tags-->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="{{  config('site.twitter_handle') }}" />
    <meta name="twitter:title" content="@yield('title', config('site.name'))" />
    <meta name="twitter:description" content="@yield('description', config('site.description'))" />
    <meta name="twitter:image" content="{{ config('site.image') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,600;1,700;1,800&amp;family=Plus+Jakarta+Sans:wght@400;600&amp;family=Poppins:wght@600;800&amp;family=Roboto:wght@400;500;700;900&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('israel-assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('israel-assets/css/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('israel-assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('israel-assets/css/odometer.css') }}" />
    <link rel="stylesheet" href="{{ asset('israel-assets/css/leaflet.css') }}" />
    <link rel="stylesheet" href="{{ asset('israel-assets/css/leaflet-routing-machine.css') }}" />
    <link rel="stylesheet" href="{{ asset('israel-assets/css/style.css') }}" />


</head>



<body class="home-6">
    <div class="ball"></div>
    <div class="cursor-text"></div>

    <!-- <div class="preloader">
    <img src="{{ asset('logo1.png') }}" height="100" class="logo-icon" alt="Baze Live Logo">
        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
            type="module"></script>
    </div> -->


    <!-- Header ======================-->
    <header class="header-section header-3 transparent-header sticky-navbar">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg max-width">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img src="{{ asset('logo1.png') }}" height="70" class="logo-icon" alt="Baze Live Logo">
                    </a>
                    <div class="d-flex align-items-center gap-20">
                    <a style="display: none" href="" class="btn-logo btn p-2 btn-primary btn-custom-dark align-items-center gap-2"
                                aria-label="buttons"><span class="buttons-logo"><svg width="25" height="25">
                                        <use xlink:href="#buttons-logo"></use>
                                    </svg></span> Buy Ticket</a> 
                    </div>


                    <div class="d-none d-lg-block">
                        <div class="d-flex gap-40 gap-xxl-60 align-items-center">
                            <a href="" class="btn btn-primary btn-custom-dark align-items-center gap-2"
                                aria-label="buttons"><span class="buttons-logo"><svg width="25" height="25">
                                        <use xlink:href="#buttons-logo"></use>
                                    </svg></span> Buy Ticket</a> 
                            <!-- <a href="#merchandise" aria-label="buttons"><span class="shop-icon-xl"><svg width="42"
                                        height="46">
                                        <use xlink:href="#shop-icon-xl"></use>
                                    </svg></span></a> -->

                            <a href="#" class="offcanvas-menu-icon" data-bs-toggle="offcanvas"
                                data-bs-target="#navContentmenu" aria-controls="navContentmenu" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span><svg width="44" height="44">
                                        <use xlink:href="#offcanvas-menu-icon"></use>
                                    </svg></span>
                            </a>
                        </div>

                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- Header ======================-->

    <div class="offcanvas offcanvas-top" id="navContentmenu" data-bs-backdrop="static" tabindex="-1">
        <div class="offcanvas-header">
        <a class="navbar-brand" href="{{url('/')}}">
                        <img src="{{ asset('logo1.png') }}" height="70" class="logo-icon" alt="Baze Live Logo">
                    </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex justify-content-center">
            <ul class="menu-list navbar-nav mb-2 me-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href=" {{ url('/') }}">
                        <div class="parent-icon"><i class='bx bx-home-alt'></i>
                        </div>
                        <div class="menu-title">Home</div>
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href=" {{ url('events') }}">
                        <div class="parent-icon"><i class="bx bx-calendar"></i>
                        </div>
                        <div class="menu-title">Events</div>
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href=" {{ url('all-videos') }}">
                        <div class="parent-icon"><i class="bx bx-video"></i>
                        </div>
                        <div class="menu-title">Videos</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=" {{ url('channels') }}">
                        <div class="parent-icon"><i class='bx bx-tv'></i>
                        </div>
                        <div class="menu-title">Channels</div>
                    </a>

                </li>

                <li class="nav-item">
                    <a class="nav-link" href=" {{ url('streams') }}">
                        <div class="parent-icon"><i class="bx bx-video-recording"></i>
                        </div>
                        <div class="menu-title">Streams</div>
                    </a>

                </li>
            </ul>

        </div>
    </div>


    <div class="main" data-bs-spy="scroll" data-bs-target="#navContentmenu" data-bs-root-margin="0px 0px -50%"
        data-bs-smooth-scroll="true">



        @yield('content')

 
    </div>



    <!--Subscription Section ======================-->
    <section class="subscription-section subscription-2 position-relative py-50 py-lg-70 py-xxl-100">
        <div class="container">
            <div class="subscription-wrapper">
                <div class="row justify-content-between gy-40 gy-lg-0">
                    <div class="col-lg-5">
                        <div class="subscription-left-content">
                            <div class="section-title mb-4 mb-lg-5">
                                <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                                        class="straight-line"></span>Join Us At The Venue</span>
                                <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                                    <span class="mb-n2 text-opacity">Ulinzi </span>
                                    <span class="sub-title fw-extra-bold text-primary">Sports Complex</span>
                                </h2>
                            </div>
                            <!-- section-title -->
                            <p class="custom-jakarta custom-font-style-2 mb-30">
                                Become Part of Our Harmonious Community and Receive Exclusive Updates, Special Offers,
                                and Exciting News about the Festival Straight to Your Inbox.
                            </p> 
                        </div>
                        <!-- subscription-left-content -->
                    </div>
                    <!-- col-5 -->

                    <div class="col-lg-5">
                        <h3 class="straight-line-wrapper fw-semibold position-relative mb-20"> <span
                                class="straight-line"></span>Ulinzi Sports Complex</h3>
                        <div class="map-image parallax position-relative">
                            <span class="map-marker">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                </svg>
                            </span>

                            <div class="map-popup-content"> 
                                <p> Langata, 54871 Langata Rd, Nairobi</p><a id="mapDirectionBtn" href="#"
                                    class="btn btn-primary btn-custom-dark btn-map-direction btn-sm d-flex align-items-center justify-content-center custom-roboto gap-10"
                                    data-bs-toggle="modal" data-bs-target="#RoutingMapModal">Get Direction <svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-arrow-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                    </svg></a>
                            </div>
                        </div>

                        <!-- Modal-Map -->
                        <div class="modal modal-fullscreen routing-map-modal fade" id="RoutingMapModal" tabindex="-1"
                            aria-labelledby="RoutingMapLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="RoutingMapLabel">Ulinzi Sports Complex Langata, 54871 Langata Rd, Nairobi
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <iframe class="w-100"
                                                src="https://www.google.com/maps/dir//Ulinzi+Sports+Complex+Langata,+54871+Langata+Rd,+Nairobi/@-1.3096348,36.7860515,14z/data=!4m9!4m8!1m0!1m5!1m1!1s0x182f117e8b602a2d:0xcbadd496b3d4d07d!2m2!1d36.7901273!2d-1.3297215!3e0?entry=ttu"
                                                width="600" height="450" style="border:0;" allowfullscreen=""
                                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal-Map -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Subscription Section ======================-->

    </div>

    <!-- Footer ======================-->
    <footer class="footer-section footer-1 py-lg-50 py-40 mt-auto">
        <div class="container">
            <div class="row gy-lg-0 gy-10 justify-content-between align-items-center text-center text-lg-start">
                <div class="col-lg-4 order-3 order-lg-1">
                    <p class="custom-jakarta custom-font-style-2 footer-nav">&copy; 2024 <a
                            href="#" class="text-decoration-none" target="_blank">Baze Live</a>.
                        All rights Reserved.</p>
                </div>
                <div class="col-lg-4 order-1 order-lg-2">
                     <a class="navbar-brand d-flex justify-content-center" href="{{url('/')}}">
                        <img src="{{ asset('logo1.png') }}" height="50" class="logo-icon" alt="Baze Live Logo">
                    </a>
                 </div>
                <div class="col-lg-4 order-2 order-lg-3">
                    <ul
                        class="footer-nav list-unstyled d-flex justify-content-center justify-content-lg-end gap-30 mb-0">
                        <li class="nav-item">
                            <a href="{{route('terms')}}" class="nav-link" aria-label="nav-links">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('terms')}}" class="nav-link" aria-label="nav-links">Terms & Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </footer>
    <!-- Footer ======================-->


    <!-- SVG ======================-->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">

        <symbol id="eventiva-logo" viewBox="0 0 16 16">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20 0H0V20H10V24.5455H24.5455V10H20V0ZM20 10H10V20H20V10Z"
                fill="#B30D5D" />
        </symbol>

        <symbol id="buttons-logo" viewBox="0 0 25 25">
            <g clip-path="url(#clip0_565_2467)">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.58643 7.11145C8.18018 6.70519 8.18018 6.04582 8.58643 5.63853L10.7948 3.43019C11.2021 3.0229 11.8625 3.0229 12.2687 3.43019L21.1073 12.2677C21.5135 12.6739 21.5135 13.3344 21.1073 13.7406L18.8989 15.95C18.4916 16.3562 17.8312 16.3562 17.425 15.95C17.0187 15.5437 16.3583 15.5437 15.9521 15.95C15.5458 16.3573 15.5458 17.0177 15.9521 17.4229C16.3583 17.8292 16.3583 18.4885 15.9521 18.8958L13.7416 21.1073C13.3354 21.5135 12.675 21.5146 12.2687 21.1073L3.43018 12.2677C3.02393 11.8614 3.02393 11.2021 3.43018 10.7948L5.64059 8.5854C6.04684 8.17915 6.70726 8.17915 7.11351 8.5854C7.51976 8.99165 8.18018 8.99165 8.58643 8.5854C8.99268 8.17915 8.99268 7.51874 8.58643 7.11145ZM10.6698 6.50103C11.225 7.66249 11.0218 9.09686 10.0593 10.0594C9.09684 11.0198 7.66351 11.2229 6.50309 10.6698L5.63955 11.5323L13.0041 18.8969L13.8677 18.0344C13.3135 16.8739 13.5166 15.4406 14.4781 14.4771C15.4396 13.5156 16.8739 13.3114 18.0354 13.8667L18.8979 13.0042L11.5323 5.63957L10.6698 6.50103Z" />
            </g>
            <defs>
                <clipPath id="clip0_565_2467">
                    <rect width="25" height="25" fill="white" />
                </clipPath>
            </defs>
        </symbol>

        <symbol id="contact-plus-icon" viewBox="0 0 41 34">
            <path
                d="M21.8017 12.6665H23.9612V14.6665H26.8405V12.6665H29V9.99994H26.8405V8H23.9612V9.99994H21.8017V12.6665Z" />
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M22.2407 18.0007C22.2407 19.4204 21.6417 20.7105 20.665 21.6662C22.0365 22.3705 23.1793 23.4106 23.964 24.6665L21.47 26C20.4742 24.4062 18.6133 23.3339 16.482 23.3339C14.3507 23.3339 12.4898 24.4062 11.494 26L9 24.6665C9.78465 23.4106 10.9274 22.3705 12.299 21.6662C11.3223 20.7105 10.7233 19.4204 10.7233 18.0007C10.7233 15.0553 13.3016 12.6675 16.482 12.6675C19.6624 12.6675 22.2407 15.0553 22.2407 18.0007ZM16.482 20.6673C18.0722 20.6673 19.3613 19.4734 19.3613 18.0007C19.3613 16.528 18.0722 15.3341 16.482 15.3341C14.8918 15.3341 13.6027 16.528 13.6027 18.0007C13.6027 19.4734 14.8918 20.6673 16.482 20.6673Z" />
        </symbol>

        <symbol id="dash-icon" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8Z" />
        </symbol>

        <symbol id="plus-icon" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
        </symbol>

        <symbol id="video-icon" viewBox="0 0 16 16">
            <path
                d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
        </symbol>

        <symbol id="chevron-right-icon" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
        </symbol>

        <symbol id="chevron-left-icon" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
        </symbol>

        <symbol id="arrow-up-right" viewBox="0 0 54 54">
            <path
                d="M12.1758 41.7882C11.4955 41.1079 11.4388 40.0401 12.0057 39.2953L12.1758 39.1007L39.0508 12.2256C39.7929 11.4835 40.9962 11.4835 41.7383 12.2256C42.4186 12.9059 42.4753 13.9737 41.9084 14.7185L41.7383 14.9131L14.8633 41.7882C14.1211 42.5303 12.9179 42.5303 12.1758 41.7882Z" />
            <path
                d="M18.7659 15.5153C17.7163 15.5175 16.8637 14.6685 16.8614 13.619C16.8594 12.6649 17.5608 11.8735 18.4769 11.7358L18.7577 11.7146L40.3903 11.668C41.3473 11.6659 42.14 12.3714 42.2746 13.2906L42.2947 13.5723L42.25 35.2067C42.2478 36.2562 41.3952 37.1052 40.3457 37.1031C39.3916 37.1011 38.6031 36.3963 38.4693 35.4797L38.4493 35.1988L38.489 15.4727L18.7659 15.5153Z" />
        </symbol>

        <symbol id="subscription-form-arrow" viewBox="0 0 37 38">
            <path
                d="M9.31312 32.4708C8.79853 31.9562 8.75564 31.1486 9.18447 30.5851L9.31312 30.4379L29.6424 10.1086C30.2038 9.54721 31.114 9.54721 31.6754 10.1086C32.19 10.6232 32.2329 11.4309 31.804 11.9943L31.6754 12.1415L11.3461 32.4708C10.7847 33.0322 9.8745 33.0322 9.31312 32.4708Z" />
            <path
                d="M14.2977 12.5972C13.5038 12.5989 12.8589 11.9567 12.8571 11.1628C12.8556 10.441 13.3862 9.84238 14.0792 9.73821L14.2915 9.72217L30.6553 9.68693C31.3792 9.68537 31.9788 10.219 32.0807 10.9143L32.0959 11.1274L32.062 27.4925C32.0604 28.2864 31.4154 28.9287 30.6215 28.927C29.8998 28.9255 29.3034 28.3924 29.2022 27.699L29.187 27.4866L29.217 12.565L14.2977 12.5972Z" />
        </symbol>

        <symbol id="arrow-down-right" viewBox="0 0 44 44">
            <path
                d="M9.52916 9.31312C10.0438 8.79853 10.8514 8.75564 11.4149 9.18447L11.5621 9.31312L31.8914 29.6424C32.4528 30.2038 32.4528 31.114 31.8914 31.6754C31.3768 32.19 30.5691 32.2329 30.0057 31.804L29.8585 31.6754L9.52916 11.3461C8.96778 10.7847 8.96778 9.8745 9.52916 9.31312Z" />
            <path
                d="M29.4028 14.2977C29.4011 13.5038 30.0433 12.8589 30.8372 12.8571C31.559 12.8556 32.1576 13.3862 32.2618 14.0792L32.2778 14.2916L32.3131 30.6553C32.3146 31.3792 31.781 31.9789 31.0857 32.0807L30.8726 32.0959L14.5075 32.062C13.7136 32.0604 13.0713 31.4154 13.073 30.6215C13.0745 29.8998 13.6076 29.3034 14.301 29.2022L14.5134 29.187L29.435 29.217L29.4028 14.2977Z" />
        </symbol>

        <symbol id="arrow-up-short" viewBox="0 0 12 12">
            <path fill-rule="evenodd"
                d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
        </symbol>

        <symbol id="faq-arrow" viewBox="0 0 14 14">
            <path fill-rule="evenodd"
                d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
        </symbol>

        <symbol id="calendar" viewBox="0 0 16 16">
            <path
                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z" />
        </symbol>

        <symbol id="arrow-up-right-big" viewBox="0 0 218 218">
            <path
                d="M49.6659 168.314C46.9214 165.57 46.6927 161.262 48.9798 158.257L49.6659 157.472L158.086 49.0517C161.08 46.0577 165.935 46.0577 168.929 49.0517C171.673 51.7961 171.902 56.1037 169.615 59.1086L168.929 59.8937L60.5079 168.314C57.514 171.308 52.6598 171.308 49.6659 168.314Z"
                stroke-width="15" />
            <path
                d="M76.2513 62.3226C72.0172 62.3318 68.5774 58.9067 68.5683 54.6727C68.56 50.8235 71.3899 47.6308 75.0856 47.0752L76.2183 46.9897L163.49 46.8018C167.35 46.7934 170.548 49.6396 171.091 53.3478L171.173 54.4841L170.992 141.763C170.983 145.997 167.544 149.422 163.31 149.413C159.46 149.405 156.28 146.562 155.74 142.864L155.659 141.731L155.819 62.1509L76.2513 62.3226Z"
                stroke-width="15" />
        </symbol>

        <symbol id="facebook-icon" viewBox="0 0 20 20">
            <path
                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
        </symbol>

        <symbol id="facebook-icon-2" viewBox="0 0 40 40">
            <path
                d="M37.7777 20C37.7777 10.1816 29.8183 2.22223 19.9999 2.22223C10.1815 2.22223 2.22217 10.1816 2.22217 20C2.22217 28.8733 8.72321 36.2281 17.2222 37.5618V25.1389H12.7083V20H17.2222V16.0833C17.2222 11.6278 19.8763 9.16667 23.9371 9.16667C25.8822 9.16667 27.9166 9.5139 27.9166 9.5139V13.8889H25.6749C23.4666 13.8889 22.7777 15.2594 22.7777 16.6653V20H27.7083L26.9201 25.1389H22.7777V37.5618C31.2767 36.2281 37.7777 28.8736 37.7777 20Z"
                fill="#1877F2" />
            <path
                d="M26.9203 25.1389L27.7085 20H22.7779V16.6653C22.7779 15.2594 23.4668 13.8889 25.6752 13.8889H27.9168V9.51391C27.9168 9.51391 25.8825 9.16669 23.9373 9.16669C19.8766 9.16669 17.2224 11.6278 17.2224 16.0834V20H12.7085V25.1389H17.2224V37.5618C18.1276 37.7038 19.055 37.7778 20.0002 37.7778C20.9453 37.7778 21.8727 37.7038 22.7779 37.5618V25.1389H26.9203Z"
                fill="white" />
        </symbol>

        <symbol id="instagram-icon" viewBox="0 0 20 20">
            <path
                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
        </symbol>

        <symbol id="instagram-icon-2" viewBox="0 0 40 40">
            <mask id="mask0_1342_841" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="36"
                height="36">
                <path
                    d="M12.5576 2.26547C10.666 2.35475 9.37408 2.65774 8.2451 3.10011C7.0764 3.55572 6.0857 4.16631 5.10016 5.15589C4.11346 6.14604 3.50751 7.1385 3.05536 8.30838C2.6176 9.44023 2.32095 10.7328 2.23742 12.6256C2.15448 14.5218 2.13547 15.1266 2.14471 19.9541C2.15392 24.7811 2.17522 25.387 2.26565 27.2838C2.35607 29.1749 2.65792 30.4663 3.10029 31.5958C3.55648 32.7646 4.16645 33.7547 5.15659 34.7408C6.14616 35.727 7.13919 36.3324 8.30961 36.7851C9.44031 37.2223 10.7334 37.5201 12.6256 37.603C14.5218 37.6866 15.1272 37.705 19.9535 37.6958C24.7821 37.6866 25.3875 37.6652 27.2837 37.5754C29.1753 37.485 30.4661 37.182 31.5962 36.7407C32.7649 36.2834 33.7556 35.6746 34.7411 34.6844C35.7267 33.6948 36.3326 32.7018 36.7848 31.5313C37.2226 30.4006 37.5204 29.1075 37.6027 27.2164C37.6857 25.3191 37.7053 24.7131 37.696 19.8862C37.6868 15.0586 37.665 14.4538 37.5751 12.5582C37.4852 10.6654 37.1823 9.37458 36.7405 8.24445C36.2837 7.07573 35.6743 6.08614 34.6847 5.09944C33.6951 4.11389 32.7021 3.50677 31.5317 3.05576C30.4004 2.61799 29.1079 2.31961 27.2157 2.23782C25.3195 2.15373 24.7141 2.1353 19.8861 2.14452C15.0592 2.15373 14.4538 2.17447 12.5576 2.26547ZM12.765 34.4033C11.0318 34.3278 10.0906 34.0398 9.46337 33.7985C8.63277 33.4771 8.04007 33.0917 7.41625 32.4725C6.7936 31.851 6.40709 31.26 6.08281 30.4311C5.83914 29.8039 5.54598 28.8638 5.46476 27.1306C5.37663 25.2569 5.35648 24.6947 5.34783 19.9478C5.33862 15.2021 5.35588 14.6399 5.43769 12.7644C5.51198 11.0323 5.80173 10.09 6.04248 9.46327C6.36388 8.63151 6.74807 8.03995 7.36844 7.41615C7.98993 6.79233 8.58091 6.40697 9.41035 6.08269C10.037 5.83787 10.9771 5.54698 12.7097 5.46463C14.5846 5.37591 15.1462 5.35691 19.8919 5.3477C24.6387 5.33847 25.2008 5.35519 27.0763 5.43756C28.8083 5.513 29.7507 5.79987 30.3768 6.04236C31.208 6.36377 31.8007 6.74682 32.4239 7.36834C33.0471 7.98985 33.4336 8.57968 33.7579 9.41086C34.0027 10.0358 34.2936 10.977 34.3754 12.7091C34.4647 14.584 34.4848 15.1462 34.4935 19.8919C34.5027 24.6388 34.4854 25.201 34.403 27.0753C34.3276 28.8085 34.0402 29.7503 33.7983 30.3781C33.4768 31.2082 33.0926 31.8009 32.4717 32.4247C31.8508 33.0462 31.2598 33.4339 30.4298 33.7582C29.8042 34.0024 28.8631 34.2938 27.1316 34.3762C25.2567 34.4643 24.6951 34.4845 19.9477 34.4932C15.202 34.5024 14.6405 34.4845 12.765 34.4033ZM27.2578 10.4172C27.2601 11.5945 28.2174 12.5478 29.3947 12.5455C30.5726 12.5432 31.5259 11.5865 31.5242 10.4091C31.5219 9.23172 30.5646 8.27786 29.3867 8.28017C28.2088 8.28247 27.2555 9.23979 27.2578 10.4172ZM10.7922 19.9374C10.802 24.9792 14.8968 29.0574 19.9374 29.0476C24.9785 29.0378 29.0589 24.9441 29.0491 19.9023C29.0393 14.8622 24.944 10.7818 19.9022 10.7916C14.8616 10.8013 10.7824 14.8968 10.7922 19.9374ZM13.9942 19.9311C13.9884 16.6588 16.6369 14.0005 19.9086 13.9947C23.1808 13.9884 25.8396 16.6357 25.846 19.9086C25.8523 23.1815 23.2039 25.8392 19.931 25.8455C16.6593 25.8519 14.0005 23.204 13.9942 19.9311Z"
                    fill="#000008" />
            </mask>
            <g mask="url(#mask0_1342_841)">
                <rect x="0.65332" y="0.653687" width="38.7111" height="38.7111" fill="url(#paint0_radial_1342_841)" />
                <g filter="url(#filter0_f_1342_841)">
                    <ellipse cx="7.89795" cy="-0.81944" rx="22.3333" ry="15.6333"
                        transform="rotate(-14.9317 7.89795 -0.81944)" fill="#7334FF" />
                </g>
                <g filter="url(#filter1_f_1342_841)">
                    <circle cx="38.1238" cy="0.901827" r="21.0926" fill="#B202F5" />
                </g>
                <g filter="url(#filter2_f_1342_841)">
                    <circle cx="8.09812" cy="35.3944" r="11.9111" fill="#FFB401" />
                </g>
            </g>
            <defs>
                <filter id="filter0_f_1342_841" x="-50.9081" y="-53.8383" width="117.612" height="106.038"
                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="18.425" result="effect1_foregroundBlur_1342_841" />
                </filter>
                <filter id="filter1_f_1342_841" x="-27.6354" y="-64.8574" width="131.518" height="131.519"
                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="22.3333" result="effect1_foregroundBlur_1342_841" />
                </filter>
                <filter id="filter2_f_1342_841" x="-39.5463" y="-12.25" width="95.2889" height="95.2889"
                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="17.8667" result="effect1_foregroundBlur_1342_841" />
                </filter>
                <radialGradient id="paint0_radial_1342_841" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(16.0385 37.6278) rotate(-61.8868) scale(20.5379)">
                    <stop stop-color="#FFD502" />
                    <stop offset="0.869374" stop-color="#FF1154" />
                </radialGradient>
            </defs>
        </symbol>

        <symbol id="youtube-icon" viewBox="0 0 20 20">
            <path
                d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
        </symbol>

        <symbol id="youtube-icon-2" viewBox="0 0 40 40">
            <path
                d="M37.0347 11.4671C36.6258 9.92754 35.4212 8.71481 33.8913 8.3033C31.1185 7.55542 19.9999 7.55542 19.9999 7.55542C19.9999 7.55542 8.88136 7.55542 6.10843 8.3033C4.57873 8.71481 3.37389 9.92754 2.965 11.4671C2.22217 14.258 2.22217 20.0807 2.22217 20.0807C2.22217 20.0807 2.22217 25.9035 2.965 28.6944C3.37389 30.234 4.57873 31.4465 6.10843 31.8582C8.88136 32.6059 19.9999 32.6059 19.9999 32.6059C19.9999 32.6059 31.1185 32.6059 33.8913 31.8582C35.4212 31.4465 36.6258 30.234 37.0347 28.6944C37.7777 25.9035 37.7777 20.0807 37.7777 20.0807C37.7777 20.0807 37.7777 14.258 37.0347 11.4671Z"
                fill="#ED1F24" />
            <path d="M16.3618 25.3671L25.6547 20.0806L16.3618 14.7939V25.3671Z" fill="white" />
        </symbol>

        <symbol id="spotify-icon" viewBox="0 0 20 20">
            <path
                d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.669 11.538a.498.498 0 0 1-.686.165c-1.879-1.147-4.243-1.407-7.028-.77a.499.499 0 0 1-.222-.973c3.048-.696 5.662-.397 7.77.892a.5.5 0 0 1 .166.686zm.979-2.178a.624.624 0 0 1-.858.205c-2.15-1.321-5.428-1.704-7.972-.932a.625.625 0 0 1-.362-1.194c2.905-.881 6.517-.454 8.986 1.063a.624.624 0 0 1 .206.858zm.084-2.268C10.154 5.56 5.9 5.419 3.438 6.166a.748.748 0 1 1-.434-1.432c2.825-.857 7.523-.692 10.492 1.07a.747.747 0 1 1-.764 1.288z" />
        </symbol>

        <symbol id="gear-icon" viewBox="0 0 16 16">
            <path
                d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z" />
            <path
                d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z" />

        </symbol>

        <symbol id="grammy-icon" viewBox="0 0 40 40">
            <mask id="path-1-outside-1_857_165" maskUnits="userSpaceOnUse" x="6.4585" y="0.776611" width="27"
                height="39" fill="black">
                <rect fill="white" x="6.4585" y="0.776611" width="27" height="39" />
                <path
                    d="M14.9892 1.78601C14.7493 1.74385 14.4963 1.84486 14.3557 2.06189C14.1682 2.35126 14.2512 2.73865 14.5412 2.92615L15.5898 3.60486C15.8792 3.79236 16.2665 3.71053 16.454 3.42053C16.6415 3.13116 16.5597 2.74377 16.2697 2.55627L15.2199 1.87634C15.1474 1.82947 15.0692 1.80006 14.9892 1.78601ZM19.3923 1.87512C19.1766 1.86956 18.9654 1.97712 18.8454 2.16931C18.7735 2.28369 11.6462 13.6688 9.80243 16.2806C9.61555 16.545 9.66116 16.9074 9.90741 17.1168C10.1524 17.3262 10.5184 17.3131 10.7497 17.0875C13.3516 14.5444 21.057 16.1089 23.8063 16.9264C23.8263 16.932 23.8467 16.9385 23.8674 16.9423C23.9621 16.9606 25.4181 17.2569 26.4577 18.1764L20.0649 17.5038C19.8911 17.485 19.7127 17.5431 19.5815 17.6613C19.4496 17.7794 19.3752 17.9482 19.3752 18.1251V20.6251H17.2158C14.9758 20.6251 14.3277 22.6574 14.2983 23.733L14.28 24.38L15.0002 24.3751V27.5001H13.9748C13.7448 27.5001 13.533 27.6266 13.4243 27.8297C11.6443 31.1403 10.4938 33.7506 10.0038 38.0543C9.98385 38.2312 10.0407 38.4083 10.1589 38.5414C10.2776 38.6745 10.4471 38.7501 10.6252 38.7501H29.3752C29.5471 38.7501 29.7112 38.6792 29.8293 38.5548C29.9474 38.4298 30.0083 38.2622 29.999 38.0909C29.7771 34.0991 28.6238 31.4725 26.57 27.8187C26.4594 27.6218 26.2512 27.5001 26.0256 27.5001H25.0002V26.7823C30.5119 26.503 31.2385 20.9898 31.2453 20.9327C31.2484 20.9084 31.2502 20.8845 31.2502 20.8595C31.2502 20.5183 31.2321 20.2293 31.216 19.9342L31.8093 19.9965C32.1536 20.0315 32.4603 19.7842 32.4965 19.441C32.5328 19.0979 32.283 18.79 31.9399 18.7538L31.0707 18.6622C30.6332 16.5715 29.2446 15.5496 25.447 12.7772C21.202 9.67785 19.4661 5.23279 19.988 2.62341C20.0473 2.32716 19.8865 2.03094 19.6059 1.91907C19.5361 1.89125 19.4642 1.87698 19.3923 1.87512ZM13.29 4.40808C13.0501 4.36601 12.7971 4.46693 12.6564 4.68396C12.4689 4.97333 12.552 5.36072 12.842 5.54822L13.8906 6.22815C14.1799 6.41565 14.5673 6.3326 14.7548 6.0426C14.9423 5.75323 14.8605 5.36584 14.5705 5.17834L13.5207 4.49841C13.4483 4.45169 13.3699 4.4221 13.29 4.40808ZM18.7172 4.72424C19.1522 7.85924 21.409 11.3774 24.7097 13.7867C28.0786 16.247 29.3155 17.1509 29.7597 18.5243L28.2021 18.3607C27.2615 16.76 25.3223 15.9524 24.1323 15.7203C23.5148 15.5391 16.6371 13.5809 12.239 14.8903C14.1596 11.954 17.0897 7.31362 18.7172 4.72424ZM11.5908 7.03137C11.3508 6.98921 11.0978 7.09022 10.9572 7.30725C10.7697 7.59663 10.8528 7.98401 11.1428 8.17151L12.1926 8.85022C12.4819 9.03772 12.8693 8.95589 13.0568 8.66589C13.2443 8.37652 13.1613 7.98914 12.8713 7.80164L11.8215 7.1217C11.749 7.07483 11.6707 7.04542 11.5908 7.03137ZM9.89154 9.65344C9.65169 9.61137 9.39862 9.71229 9.258 9.92932C9.0705 10.2187 9.15354 10.6061 9.44354 10.7936L10.4921 11.4735C10.7815 11.661 11.1701 11.578 11.3576 11.288C11.5451 10.9986 11.4621 10.6112 11.1721 10.4237L10.1223 9.745C10.0499 9.69828 9.97149 9.66747 9.89154 9.65344ZM8.19232 12.2767C7.95247 12.2344 7.6994 12.3351 7.55878 12.5526C7.37128 12.842 7.45432 13.2294 7.74432 13.4169L8.79291 14.0968C9.08228 14.2843 9.46967 14.2013 9.65717 13.9113C9.84467 13.6219 9.76284 13.2345 9.47284 13.047L8.42303 12.3671C8.35069 12.3202 8.27227 12.2908 8.19232 12.2767ZM20.6252 18.8185L27.3659 19.5289C27.4486 19.7915 27.5002 20.0763 27.5002 20.3907C27.5002 22.0043 26.4524 22.661 25.5641 22.9323C25.4508 22.2771 25.1786 21.7446 24.7499 21.3441C23.9206 20.5691 22.8523 20.617 22.7761 20.6251H20.6252V18.8185ZM28.6806 19.6669L29.9733 19.8036C29.9922 20.1102 29.9998 20.4416 30.0002 20.8192C29.938 21.2659 29.2541 25.2626 25.0002 25.5238V24.3336C25.0571 24.3262 25.12 24.3077 25.1784 24.2982L25.6252 24.2946V24.2103C27.0869 23.8454 28.7502 22.7953 28.7502 20.3907C28.7502 20.1364 28.7215 19.8981 28.6806 19.6669ZM22.842 21.8727C22.972 21.8727 23.5177 21.8964 23.9052 22.2645C24.1058 22.4558 24.2429 22.7199 24.3154 23.0543L15.6594 23.119C15.8231 22.5703 16.2264 21.8751 17.2158 21.8751H20.0002H20.0026L22.8139 21.8739C22.8139 21.8739 22.8239 21.8727 22.842 21.8727ZM16.2502 24.3751H23.7502V27.5001H16.2502V24.3751ZM14.352 28.7501H15.6252H24.3752H25.6569C27.395 31.9389 28.4188 34.0489 28.705 37.5001H11.332C11.8257 33.7757 12.8401 31.6664 14.352 28.7501Z" />
            </mask>
            <path
                d="M14.9892 1.78601C14.7493 1.74385 14.4963 1.84486 14.3557 2.06189C14.1682 2.35126 14.2512 2.73865 14.5412 2.92615L15.5898 3.60486C15.8792 3.79236 16.2665 3.71053 16.454 3.42053C16.6415 3.13116 16.5597 2.74377 16.2697 2.55627L15.2199 1.87634C15.1474 1.82947 15.0692 1.80006 14.9892 1.78601ZM19.3923 1.87512C19.1766 1.86956 18.9654 1.97712 18.8454 2.16931C18.7735 2.28369 11.6462 13.6688 9.80243 16.2806C9.61555 16.545 9.66116 16.9074 9.90741 17.1168C10.1524 17.3262 10.5184 17.3131 10.7497 17.0875C13.3516 14.5444 21.057 16.1089 23.8063 16.9264C23.8263 16.932 23.8467 16.9385 23.8674 16.9423C23.9621 16.9606 25.4181 17.2569 26.4577 18.1764L20.0649 17.5038C19.8911 17.485 19.7127 17.5431 19.5815 17.6613C19.4496 17.7794 19.3752 17.9482 19.3752 18.1251V20.6251H17.2158C14.9758 20.6251 14.3277 22.6574 14.2983 23.733L14.28 24.38L15.0002 24.3751V27.5001H13.9748C13.7448 27.5001 13.533 27.6266 13.4243 27.8297C11.6443 31.1403 10.4938 33.7506 10.0038 38.0543C9.98385 38.2312 10.0407 38.4083 10.1589 38.5414C10.2776 38.6745 10.4471 38.7501 10.6252 38.7501H29.3752C29.5471 38.7501 29.7112 38.6792 29.8293 38.5548C29.9474 38.4298 30.0083 38.2622 29.999 38.0909C29.7771 34.0991 28.6238 31.4725 26.57 27.8187C26.4594 27.6218 26.2512 27.5001 26.0256 27.5001H25.0002V26.7823C30.5119 26.503 31.2385 20.9898 31.2453 20.9327C31.2484 20.9084 31.2502 20.8845 31.2502 20.8595C31.2502 20.5183 31.2321 20.2293 31.216 19.9342L31.8093 19.9965C32.1536 20.0315 32.4603 19.7842 32.4965 19.441C32.5328 19.0979 32.283 18.79 31.9399 18.7538L31.0707 18.6622C30.6332 16.5715 29.2446 15.5496 25.447 12.7772C21.202 9.67785 19.4661 5.23279 19.988 2.62341C20.0473 2.32716 19.8865 2.03094 19.6059 1.91907C19.5361 1.89125 19.4642 1.87698 19.3923 1.87512ZM13.29 4.40808C13.0501 4.36601 12.7971 4.46693 12.6564 4.68396C12.4689 4.97333 12.552 5.36072 12.842 5.54822L13.8906 6.22815C14.1799 6.41565 14.5673 6.3326 14.7548 6.0426C14.9423 5.75323 14.8605 5.36584 14.5705 5.17834L13.5207 4.49841C13.4483 4.45169 13.3699 4.4221 13.29 4.40808ZM18.7172 4.72424C19.1522 7.85924 21.409 11.3774 24.7097 13.7867C28.0786 16.247 29.3155 17.1509 29.7597 18.5243L28.2021 18.3607C27.2615 16.76 25.3223 15.9524 24.1323 15.7203C23.5148 15.5391 16.6371 13.5809 12.239 14.8903C14.1596 11.954 17.0897 7.31362 18.7172 4.72424ZM11.5908 7.03137C11.3508 6.98921 11.0978 7.09022 10.9572 7.30725C10.7697 7.59663 10.8528 7.98401 11.1428 8.17151L12.1926 8.85022C12.4819 9.03772 12.8693 8.95589 13.0568 8.66589C13.2443 8.37652 13.1613 7.98914 12.8713 7.80164L11.8215 7.1217C11.749 7.07483 11.6707 7.04542 11.5908 7.03137ZM9.89154 9.65344C9.65169 9.61137 9.39862 9.71229 9.258 9.92932C9.0705 10.2187 9.15354 10.6061 9.44354 10.7936L10.4921 11.4735C10.7815 11.661 11.1701 11.578 11.3576 11.288C11.5451 10.9986 11.4621 10.6112 11.1721 10.4237L10.1223 9.745C10.0499 9.69828 9.97149 9.66747 9.89154 9.65344ZM8.19232 12.2767C7.95247 12.2344 7.6994 12.3351 7.55878 12.5526C7.37128 12.842 7.45432 13.2294 7.74432 13.4169L8.79291 14.0968C9.08228 14.2843 9.46967 14.2013 9.65717 13.9113C9.84467 13.6219 9.76284 13.2345 9.47284 13.047L8.42303 12.3671C8.35069 12.3202 8.27227 12.2908 8.19232 12.2767ZM20.6252 18.8185L27.3659 19.5289C27.4486 19.7915 27.5002 20.0763 27.5002 20.3907C27.5002 22.0043 26.4524 22.661 25.5641 22.9323C25.4508 22.2771 25.1786 21.7446 24.7499 21.3441C23.9206 20.5691 22.8523 20.617 22.7761 20.6251H20.6252V18.8185ZM28.6806 19.6669L29.9733 19.8036C29.9922 20.1102 29.9998 20.4416 30.0002 20.8192C29.938 21.2659 29.2541 25.2626 25.0002 25.5238V24.3336C25.0571 24.3262 25.12 24.3077 25.1784 24.2982L25.6252 24.2946V24.2103C27.0869 23.8454 28.7502 22.7953 28.7502 20.3907C28.7502 20.1364 28.7215 19.8981 28.6806 19.6669ZM22.842 21.8727C22.972 21.8727 23.5177 21.8964 23.9052 22.2645C24.1058 22.4558 24.2429 22.7199 24.3154 23.0543L15.6594 23.119C15.8231 22.5703 16.2264 21.8751 17.2158 21.8751H20.0002H20.0026L22.8139 21.8739C22.8139 21.8739 22.8239 21.8727 22.842 21.8727ZM16.2502 24.3751H23.7502V27.5001H16.2502V24.3751ZM14.352 28.7501H15.6252H24.3752H25.6569C27.395 31.9389 28.4188 34.0489 28.705 37.5001H11.332C11.8257 33.7757 12.8401 31.6664 14.352 28.7501Z"
                fill="#490AB5" />
            <path
                d="M14.9892 1.78601C14.7493 1.74385 14.4963 1.84486 14.3557 2.06189C14.1682 2.35126 14.2512 2.73865 14.5412 2.92615L15.5898 3.60486C15.8792 3.79236 16.2665 3.71053 16.454 3.42053C16.6415 3.13116 16.5597 2.74377 16.2697 2.55627L15.2199 1.87634C15.1474 1.82947 15.0692 1.80006 14.9892 1.78601ZM19.3923 1.87512C19.1766 1.86956 18.9654 1.97712 18.8454 2.16931C18.7735 2.28369 11.6462 13.6688 9.80243 16.2806C9.61555 16.545 9.66116 16.9074 9.90741 17.1168C10.1524 17.3262 10.5184 17.3131 10.7497 17.0875C13.3516 14.5444 21.057 16.1089 23.8063 16.9264C23.8263 16.932 23.8467 16.9385 23.8674 16.9423C23.9621 16.9606 25.4181 17.2569 26.4577 18.1764L20.0649 17.5038C19.8911 17.485 19.7127 17.5431 19.5815 17.6613C19.4496 17.7794 19.3752 17.9482 19.3752 18.1251V20.6251H17.2158C14.9758 20.6251 14.3277 22.6574 14.2983 23.733L14.28 24.38L15.0002 24.3751V27.5001H13.9748C13.7448 27.5001 13.533 27.6266 13.4243 27.8297C11.6443 31.1403 10.4938 33.7506 10.0038 38.0543C9.98385 38.2312 10.0407 38.4083 10.1589 38.5414C10.2776 38.6745 10.4471 38.7501 10.6252 38.7501H29.3752C29.5471 38.7501 29.7112 38.6792 29.8293 38.5548C29.9474 38.4298 30.0083 38.2622 29.999 38.0909C29.7771 34.0991 28.6238 31.4725 26.57 27.8187C26.4594 27.6218 26.2512 27.5001 26.0256 27.5001H25.0002V26.7823C30.5119 26.503 31.2385 20.9898 31.2453 20.9327C31.2484 20.9084 31.2502 20.8845 31.2502 20.8595C31.2502 20.5183 31.2321 20.2293 31.216 19.9342L31.8093 19.9965C32.1536 20.0315 32.4603 19.7842 32.4965 19.441C32.5328 19.0979 32.283 18.79 31.9399 18.7538L31.0707 18.6622C30.6332 16.5715 29.2446 15.5496 25.447 12.7772C21.202 9.67785 19.4661 5.23279 19.988 2.62341C20.0473 2.32716 19.8865 2.03094 19.6059 1.91907C19.5361 1.89125 19.4642 1.87698 19.3923 1.87512ZM13.29 4.40808C13.0501 4.36601 12.7971 4.46693 12.6564 4.68396C12.4689 4.97333 12.552 5.36072 12.842 5.54822L13.8906 6.22815C14.1799 6.41565 14.5673 6.3326 14.7548 6.0426C14.9423 5.75323 14.8605 5.36584 14.5705 5.17834L13.5207 4.49841C13.4483 4.45169 13.3699 4.4221 13.29 4.40808ZM18.7172 4.72424C19.1522 7.85924 21.409 11.3774 24.7097 13.7867C28.0786 16.247 29.3155 17.1509 29.7597 18.5243L28.2021 18.3607C27.2615 16.76 25.3223 15.9524 24.1323 15.7203C23.5148 15.5391 16.6371 13.5809 12.239 14.8903C14.1596 11.954 17.0897 7.31362 18.7172 4.72424ZM11.5908 7.03137C11.3508 6.98921 11.0978 7.09022 10.9572 7.30725C10.7697 7.59663 10.8528 7.98401 11.1428 8.17151L12.1926 8.85022C12.4819 9.03772 12.8693 8.95589 13.0568 8.66589C13.2443 8.37652 13.1613 7.98914 12.8713 7.80164L11.8215 7.1217C11.749 7.07483 11.6707 7.04542 11.5908 7.03137ZM9.89154 9.65344C9.65169 9.61137 9.39862 9.71229 9.258 9.92932C9.0705 10.2187 9.15354 10.6061 9.44354 10.7936L10.4921 11.4735C10.7815 11.661 11.1701 11.578 11.3576 11.288C11.5451 10.9986 11.4621 10.6112 11.1721 10.4237L10.1223 9.745C10.0499 9.69828 9.97149 9.66747 9.89154 9.65344ZM8.19232 12.2767C7.95247 12.2344 7.6994 12.3351 7.55878 12.5526C7.37128 12.842 7.45432 13.2294 7.74432 13.4169L8.79291 14.0968C9.08228 14.2843 9.46967 14.2013 9.65717 13.9113C9.84467 13.6219 9.76284 13.2345 9.47284 13.047L8.42303 12.3671C8.35069 12.3202 8.27227 12.2908 8.19232 12.2767ZM20.6252 18.8185L27.3659 19.5289C27.4486 19.7915 27.5002 20.0763 27.5002 20.3907C27.5002 22.0043 26.4524 22.661 25.5641 22.9323C25.4508 22.2771 25.1786 21.7446 24.7499 21.3441C23.9206 20.5691 22.8523 20.617 22.7761 20.6251H20.6252V18.8185ZM28.6806 19.6669L29.9733 19.8036C29.9922 20.1102 29.9998 20.4416 30.0002 20.8192C29.938 21.2659 29.2541 25.2626 25.0002 25.5238V24.3336C25.0571 24.3262 25.12 24.3077 25.1784 24.2982L25.6252 24.2946V24.2103C27.0869 23.8454 28.7502 22.7953 28.7502 20.3907C28.7502 20.1364 28.7215 19.8981 28.6806 19.6669ZM22.842 21.8727C22.972 21.8727 23.5177 21.8964 23.9052 22.2645C24.1058 22.4558 24.2429 22.7199 24.3154 23.0543L15.6594 23.119C15.8231 22.5703 16.2264 21.8751 17.2158 21.8751H20.0002H20.0026L22.8139 21.8739C22.8139 21.8739 22.8239 21.8727 22.842 21.8727ZM16.2502 24.3751H23.7502V27.5001H16.2502V24.3751ZM14.352 28.7501H15.6252H24.3752H25.6569C27.395 31.9389 28.4188 34.0489 28.705 37.5001H11.332C11.8257 33.7757 12.8401 31.6664 14.352 28.7501Z"
                stroke="#490AB5" mask="url(#path-1-outside-1_857_165)" />

        </symbol>


        <symbol id="billboard-icon" viewBox="0 0 40 40">
            <mask id="path-1-outside-1_857_167" maskUnits="userSpaceOnUse" x="0.25" y="0.25" width="38" height="38"
                fill="black">
                <rect fill="white" x="0.25" y="0.25" width="38" height="38" />
                <path
                    d="M19.375 1.25C9.38232 1.25 1.25 9.38232 1.25 19.375C1.25 29.3677 9.38232 37.5 19.375 37.5C29.3677 37.5 37.5 29.3677 37.5 19.375C37.5 9.38232 29.3677 1.25 19.375 1.25ZM19.375 2.5C28.6792 2.5 36.25 10.0708 36.25 19.375C36.25 28.6792 28.6792 36.25 19.375 36.25C10.0708 36.25 2.5 28.6792 2.5 19.375C2.5 10.0708 10.0708 2.5 19.375 2.5ZM19.1016 5.00488C15.4199 5.08057 11.8506 6.56738 9.20898 9.20898C3.60596 14.8145 3.60596 23.9331 9.20898 29.5386C9.33105 29.6606 9.49219 29.7217 9.65088 29.7217C9.81201 29.7217 9.97314 29.6606 10.0952 29.5386C10.3394 29.2944 10.3394 28.8989 10.0952 28.6548C4.97559 23.5376 4.97803 15.2124 10.0952 10.0952C13.0615 7.12646 17.312 5.74951 21.4624 6.41602C21.8018 6.47217 22.1216 6.23779 22.1777 5.89844C22.2339 5.55664 21.9995 5.23438 21.6602 5.18066C20.8057 5.04395 19.9512 4.98779 19.1016 5.00488ZM24.1602 5.8667C23.9185 5.87646 23.6938 6.03027 23.6035 6.27197C23.4814 6.59668 23.645 6.95557 23.9697 7.07764C24.4141 7.24365 24.8535 7.43408 25.2832 7.64893C25.3735 7.69531 25.4688 7.71729 25.564 7.71729C25.791 7.71729 26.0107 7.59277 26.123 7.37305C26.2769 7.06787 26.1523 6.68945 25.8447 6.5332C25.3784 6.29883 24.8926 6.08643 24.4067 5.90332C24.3262 5.87402 24.2432 5.86426 24.1602 5.8667ZM27.771 7.92236C27.6099 7.94189 27.4609 8.02246 27.3535 8.15674C27.1411 8.43018 27.1875 8.8208 27.4609 9.0332C27.8784 9.36035 28.2812 9.71924 28.6548 10.0952C33.7744 15.2124 33.7744 23.5376 28.6548 28.6548C28.4106 28.9014 28.4106 29.2944 28.6548 29.541C28.7769 29.6606 28.938 29.7241 29.0991 29.7241C29.2578 29.7241 29.4189 29.6606 29.541 29.541C35.144 23.9355 35.144 14.8145 29.541 9.20898C29.1284 8.79883 28.689 8.4082 28.2324 8.04932C28.0957 7.94434 27.9297 7.90283 27.771 7.92236ZM18.2275 10.0098C18.0469 9.98047 17.8613 10.0293 17.7222 10.1489C17.5806 10.2661 17.5 10.4419 17.5 10.625V18.8892C17.0923 18.7964 16.6748 18.75 16.25 18.75C13.4937 18.75 11.25 20.7129 11.25 23.125C11.25 25.5371 13.4937 27.5 16.25 27.5C19.0063 27.5 21.25 25.5371 21.25 23.125V16.1548L25.5225 16.8652C25.7056 16.897 25.8887 16.8457 26.0278 16.7285C26.1694 16.6089 26.25 16.4331 26.25 16.25V11.875C26.25 11.5698 26.0278 11.3086 25.7275 11.2598L18.2275 10.0098ZM18.75 11.3647L25 12.4048V15.5103L20.7275 14.7998C20.5469 14.7705 20.3613 14.8218 20.2222 14.9414C20.0806 15.0586 20 15.2344 20 15.4175V23.125C20 24.8486 18.3179 26.25 16.25 26.25C14.1821 26.25 12.5 24.8486 12.5 23.125C12.5 21.4014 14.1821 20 16.25 20C16.8164 20 17.3682 20.1074 17.8882 20.3198C18.0835 20.4004 18.3008 20.376 18.4741 20.2588C18.6475 20.1416 18.75 19.9487 18.75 19.7412V11.3647ZM13.3594 29.6216C13.1152 29.5923 12.8687 29.7046 12.7417 29.9292L12.1167 31.0107C11.9434 31.311 12.0459 31.6919 12.3438 31.8652C12.4414 31.9214 12.5488 31.9482 12.6562 31.9482C12.8711 31.9482 13.0811 31.8359 13.1958 31.6357L13.8208 30.5542C13.9941 30.2563 13.894 29.873 13.5938 29.6997C13.5181 29.6582 13.4399 29.6313 13.3594 29.6216ZM25.3906 29.6216C25.3101 29.6313 25.2295 29.6582 25.1562 29.6997C24.856 29.873 24.7559 30.2563 24.9292 30.5566L25.5542 31.6382C25.6689 31.8384 25.8789 31.9507 26.0938 31.9507C26.2012 31.9507 26.3086 31.9214 26.4062 31.8652C26.7065 31.6919 26.8066 31.311 26.6333 31.0132L26.0083 29.9316C25.8789 29.707 25.6323 29.5923 25.3906 29.6216ZM16.1353 30.8398C15.8936 30.874 15.6836 31.0474 15.6152 31.2988L15.293 32.5073C15.2026 32.8394 15.4028 33.1812 15.7349 33.2715C15.7886 33.2861 15.8423 33.291 15.8984 33.291C16.1719 33.291 16.4258 33.1079 16.5015 32.8296L16.8262 31.6211C16.9141 31.2891 16.7163 30.9448 16.3843 30.8569C16.2988 30.8325 16.2158 30.8301 16.1353 30.8398ZM22.6172 30.8398C22.5366 30.8301 22.4512 30.8325 22.3657 30.8569C22.0337 30.9448 21.8359 31.2891 21.9238 31.6211L22.2485 32.8296C22.3242 33.1079 22.5757 33.291 22.8516 33.291C22.9077 33.291 22.9614 33.2861 23.0151 33.2715C23.3472 33.1812 23.5474 32.8394 23.457 32.5073L23.1348 31.2988C23.0664 31.0474 22.8564 30.874 22.6172 30.8398ZM19.375 31.25C19.0283 31.25 18.75 31.5283 18.75 31.875V33.125C18.75 33.4717 19.0283 33.75 19.375 33.75C19.7217 33.75 20 33.4717 20 33.125V31.875C20 31.5283 19.7217 31.25 19.375 31.25Z" />
            </mask>
            <path
                d="M19.375 1.25C9.38232 1.25 1.25 9.38232 1.25 19.375C1.25 29.3677 9.38232 37.5 19.375 37.5C29.3677 37.5 37.5 29.3677 37.5 19.375C37.5 9.38232 29.3677 1.25 19.375 1.25ZM19.375 2.5C28.6792 2.5 36.25 10.0708 36.25 19.375C36.25 28.6792 28.6792 36.25 19.375 36.25C10.0708 36.25 2.5 28.6792 2.5 19.375C2.5 10.0708 10.0708 2.5 19.375 2.5ZM19.1016 5.00488C15.4199 5.08057 11.8506 6.56738 9.20898 9.20898C3.60596 14.8145 3.60596 23.9331 9.20898 29.5386C9.33105 29.6606 9.49219 29.7217 9.65088 29.7217C9.81201 29.7217 9.97314 29.6606 10.0952 29.5386C10.3394 29.2944 10.3394 28.8989 10.0952 28.6548C4.97559 23.5376 4.97803 15.2124 10.0952 10.0952C13.0615 7.12646 17.312 5.74951 21.4624 6.41602C21.8018 6.47217 22.1216 6.23779 22.1777 5.89844C22.2339 5.55664 21.9995 5.23438 21.6602 5.18066C20.8057 5.04395 19.9512 4.98779 19.1016 5.00488ZM24.1602 5.8667C23.9185 5.87646 23.6938 6.03027 23.6035 6.27197C23.4814 6.59668 23.645 6.95557 23.9697 7.07764C24.4141 7.24365 24.8535 7.43408 25.2832 7.64893C25.3735 7.69531 25.4688 7.71729 25.564 7.71729C25.791 7.71729 26.0107 7.59277 26.123 7.37305C26.2769 7.06787 26.1523 6.68945 25.8447 6.5332C25.3784 6.29883 24.8926 6.08643 24.4067 5.90332C24.3262 5.87402 24.2432 5.86426 24.1602 5.8667ZM27.771 7.92236C27.6099 7.94189 27.4609 8.02246 27.3535 8.15674C27.1411 8.43018 27.1875 8.8208 27.4609 9.0332C27.8784 9.36035 28.2812 9.71924 28.6548 10.0952C33.7744 15.2124 33.7744 23.5376 28.6548 28.6548C28.4106 28.9014 28.4106 29.2944 28.6548 29.541C28.7769 29.6606 28.938 29.7241 29.0991 29.7241C29.2578 29.7241 29.4189 29.6606 29.541 29.541C35.144 23.9355 35.144 14.8145 29.541 9.20898C29.1284 8.79883 28.689 8.4082 28.2324 8.04932C28.0957 7.94434 27.9297 7.90283 27.771 7.92236ZM18.2275 10.0098C18.0469 9.98047 17.8613 10.0293 17.7222 10.1489C17.5806 10.2661 17.5 10.4419 17.5 10.625V18.8892C17.0923 18.7964 16.6748 18.75 16.25 18.75C13.4937 18.75 11.25 20.7129 11.25 23.125C11.25 25.5371 13.4937 27.5 16.25 27.5C19.0063 27.5 21.25 25.5371 21.25 23.125V16.1548L25.5225 16.8652C25.7056 16.897 25.8887 16.8457 26.0278 16.7285C26.1694 16.6089 26.25 16.4331 26.25 16.25V11.875C26.25 11.5698 26.0278 11.3086 25.7275 11.2598L18.2275 10.0098ZM18.75 11.3647L25 12.4048V15.5103L20.7275 14.7998C20.5469 14.7705 20.3613 14.8218 20.2222 14.9414C20.0806 15.0586 20 15.2344 20 15.4175V23.125C20 24.8486 18.3179 26.25 16.25 26.25C14.1821 26.25 12.5 24.8486 12.5 23.125C12.5 21.4014 14.1821 20 16.25 20C16.8164 20 17.3682 20.1074 17.8882 20.3198C18.0835 20.4004 18.3008 20.376 18.4741 20.2588C18.6475 20.1416 18.75 19.9487 18.75 19.7412V11.3647ZM13.3594 29.6216C13.1152 29.5923 12.8687 29.7046 12.7417 29.9292L12.1167 31.0107C11.9434 31.311 12.0459 31.6919 12.3438 31.8652C12.4414 31.9214 12.5488 31.9482 12.6562 31.9482C12.8711 31.9482 13.0811 31.8359 13.1958 31.6357L13.8208 30.5542C13.9941 30.2563 13.894 29.873 13.5938 29.6997C13.5181 29.6582 13.4399 29.6313 13.3594 29.6216ZM25.3906 29.6216C25.3101 29.6313 25.2295 29.6582 25.1562 29.6997C24.856 29.873 24.7559 30.2563 24.9292 30.5566L25.5542 31.6382C25.6689 31.8384 25.8789 31.9507 26.0938 31.9507C26.2012 31.9507 26.3086 31.9214 26.4062 31.8652C26.7065 31.6919 26.8066 31.311 26.6333 31.0132L26.0083 29.9316C25.8789 29.707 25.6323 29.5923 25.3906 29.6216ZM16.1353 30.8398C15.8936 30.874 15.6836 31.0474 15.6152 31.2988L15.293 32.5073C15.2026 32.8394 15.4028 33.1812 15.7349 33.2715C15.7886 33.2861 15.8423 33.291 15.8984 33.291C16.1719 33.291 16.4258 33.1079 16.5015 32.8296L16.8262 31.6211C16.9141 31.2891 16.7163 30.9448 16.3843 30.8569C16.2988 30.8325 16.2158 30.8301 16.1353 30.8398ZM22.6172 30.8398C22.5366 30.8301 22.4512 30.8325 22.3657 30.8569C22.0337 30.9448 21.8359 31.2891 21.9238 31.6211L22.2485 32.8296C22.3242 33.1079 22.5757 33.291 22.8516 33.291C22.9077 33.291 22.9614 33.2861 23.0151 33.2715C23.3472 33.1812 23.5474 32.8394 23.457 32.5073L23.1348 31.2988C23.0664 31.0474 22.8564 30.874 22.6172 30.8398ZM19.375 31.25C19.0283 31.25 18.75 31.5283 18.75 31.875V33.125C18.75 33.4717 19.0283 33.75 19.375 33.75C19.7217 33.75 20 33.4717 20 33.125V31.875C20 31.5283 19.7217 31.25 19.375 31.25Z"
                fill="#490AB5" />
            <path
                d="M19.375 1.25C9.38232 1.25 1.25 9.38232 1.25 19.375C1.25 29.3677 9.38232 37.5 19.375 37.5C29.3677 37.5 37.5 29.3677 37.5 19.375C37.5 9.38232 29.3677 1.25 19.375 1.25ZM19.375 2.5C28.6792 2.5 36.25 10.0708 36.25 19.375C36.25 28.6792 28.6792 36.25 19.375 36.25C10.0708 36.25 2.5 28.6792 2.5 19.375C2.5 10.0708 10.0708 2.5 19.375 2.5ZM19.1016 5.00488C15.4199 5.08057 11.8506 6.56738 9.20898 9.20898C3.60596 14.8145 3.60596 23.9331 9.20898 29.5386C9.33105 29.6606 9.49219 29.7217 9.65088 29.7217C9.81201 29.7217 9.97314 29.6606 10.0952 29.5386C10.3394 29.2944 10.3394 28.8989 10.0952 28.6548C4.97559 23.5376 4.97803 15.2124 10.0952 10.0952C13.0615 7.12646 17.312 5.74951 21.4624 6.41602C21.8018 6.47217 22.1216 6.23779 22.1777 5.89844C22.2339 5.55664 21.9995 5.23438 21.6602 5.18066C20.8057 5.04395 19.9512 4.98779 19.1016 5.00488ZM24.1602 5.8667C23.9185 5.87646 23.6938 6.03027 23.6035 6.27197C23.4814 6.59668 23.645 6.95557 23.9697 7.07764C24.4141 7.24365 24.8535 7.43408 25.2832 7.64893C25.3735 7.69531 25.4688 7.71729 25.564 7.71729C25.791 7.71729 26.0107 7.59277 26.123 7.37305C26.2769 7.06787 26.1523 6.68945 25.8447 6.5332C25.3784 6.29883 24.8926 6.08643 24.4067 5.90332C24.3262 5.87402 24.2432 5.86426 24.1602 5.8667ZM27.771 7.92236C27.6099 7.94189 27.4609 8.02246 27.3535 8.15674C27.1411 8.43018 27.1875 8.8208 27.4609 9.0332C27.8784 9.36035 28.2812 9.71924 28.6548 10.0952C33.7744 15.2124 33.7744 23.5376 28.6548 28.6548C28.4106 28.9014 28.4106 29.2944 28.6548 29.541C28.7769 29.6606 28.938 29.7241 29.0991 29.7241C29.2578 29.7241 29.4189 29.6606 29.541 29.541C35.144 23.9355 35.144 14.8145 29.541 9.20898C29.1284 8.79883 28.689 8.4082 28.2324 8.04932C28.0957 7.94434 27.9297 7.90283 27.771 7.92236ZM18.2275 10.0098C18.0469 9.98047 17.8613 10.0293 17.7222 10.1489C17.5806 10.2661 17.5 10.4419 17.5 10.625V18.8892C17.0923 18.7964 16.6748 18.75 16.25 18.75C13.4937 18.75 11.25 20.7129 11.25 23.125C11.25 25.5371 13.4937 27.5 16.25 27.5C19.0063 27.5 21.25 25.5371 21.25 23.125V16.1548L25.5225 16.8652C25.7056 16.897 25.8887 16.8457 26.0278 16.7285C26.1694 16.6089 26.25 16.4331 26.25 16.25V11.875C26.25 11.5698 26.0278 11.3086 25.7275 11.2598L18.2275 10.0098ZM18.75 11.3647L25 12.4048V15.5103L20.7275 14.7998C20.5469 14.7705 20.3613 14.8218 20.2222 14.9414C20.0806 15.0586 20 15.2344 20 15.4175V23.125C20 24.8486 18.3179 26.25 16.25 26.25C14.1821 26.25 12.5 24.8486 12.5 23.125C12.5 21.4014 14.1821 20 16.25 20C16.8164 20 17.3682 20.1074 17.8882 20.3198C18.0835 20.4004 18.3008 20.376 18.4741 20.2588C18.6475 20.1416 18.75 19.9487 18.75 19.7412V11.3647ZM13.3594 29.6216C13.1152 29.5923 12.8687 29.7046 12.7417 29.9292L12.1167 31.0107C11.9434 31.311 12.0459 31.6919 12.3438 31.8652C12.4414 31.9214 12.5488 31.9482 12.6562 31.9482C12.8711 31.9482 13.0811 31.8359 13.1958 31.6357L13.8208 30.5542C13.9941 30.2563 13.894 29.873 13.5938 29.6997C13.5181 29.6582 13.4399 29.6313 13.3594 29.6216ZM25.3906 29.6216C25.3101 29.6313 25.2295 29.6582 25.1562 29.6997C24.856 29.873 24.7559 30.2563 24.9292 30.5566L25.5542 31.6382C25.6689 31.8384 25.8789 31.9507 26.0938 31.9507C26.2012 31.9507 26.3086 31.9214 26.4062 31.8652C26.7065 31.6919 26.8066 31.311 26.6333 31.0132L26.0083 29.9316C25.8789 29.707 25.6323 29.5923 25.3906 29.6216ZM16.1353 30.8398C15.8936 30.874 15.6836 31.0474 15.6152 31.2988L15.293 32.5073C15.2026 32.8394 15.4028 33.1812 15.7349 33.2715C15.7886 33.2861 15.8423 33.291 15.8984 33.291C16.1719 33.291 16.4258 33.1079 16.5015 32.8296L16.8262 31.6211C16.9141 31.2891 16.7163 30.9448 16.3843 30.8569C16.2988 30.8325 16.2158 30.8301 16.1353 30.8398ZM22.6172 30.8398C22.5366 30.8301 22.4512 30.8325 22.3657 30.8569C22.0337 30.9448 21.8359 31.2891 21.9238 31.6211L22.2485 32.8296C22.3242 33.1079 22.5757 33.291 22.8516 33.291C22.9077 33.291 22.9614 33.2861 23.0151 33.2715C23.3472 33.1812 23.5474 32.8394 23.457 32.5073L23.1348 31.2988C23.0664 31.0474 22.8564 30.874 22.6172 30.8398ZM19.375 31.25C19.0283 31.25 18.75 31.5283 18.75 31.875V33.125C18.75 33.4717 19.0283 33.75 19.375 33.75C19.7217 33.75 20 33.4717 20 33.125V31.875C20 31.5283 19.7217 31.25 19.375 31.25Z"
                stroke="#490AB5" mask="url(#path-1-outside-1_857_167)" />

        </symbol>

        <symbol id="star-icon" viewBox="0 0 47 42">
            <path d="M23.5 0L30.3794 14.8524L47 21L30.3794 27.1476L23.5 42L16.6206 27.1476L0 21L16.6206 14.8524L23.5 0Z"
                fill="white" />
        </symbol>

        <symbol id="pricing-1-icon" viewBox="0 0 55 49">
            <path opacity="0.4"
                d="M0.416504 38.0417C0.416504 44.0247 5.26675 48.875 11.2498 48.875H43.7498C49.7329 48.875 54.5832 44.0247 54.5832 38.0417V33.625C54.5832 33.0727 54.1333 32.6315 53.5851 32.5643C49.5686 32.0723 46.4582 28.6494 46.4582 24.5C46.4582 20.3506 49.5686 16.9277 53.5851 16.4357C54.1333 16.3685 54.5832 15.9273 54.5832 15.375V10.9583C54.5832 4.97525 49.7329 0.125 43.7498 0.125H11.2498C5.26675 0.125 0.416504 4.97525 0.416504 10.9583V15.375C0.416504 15.9273 0.866406 16.3685 1.41459 16.4357C5.43105 16.9277 8.5415 20.3506 8.5415 24.5C8.5415 28.6494 5.43105 32.0723 1.41459 32.5643C0.866407 32.6315 0.416504 33.0727 0.416504 33.625V38.0417Z" />
            <path
                d="M22.0832 16.3748C22.0832 17.8706 20.8706 19.0832 19.3748 19.0832C17.8791 19.0832 16.6665 17.8706 16.6665 16.3748C16.6665 14.8791 17.8791 13.6665 19.3748 13.6665C20.8706 13.6665 22.0832 14.8791 22.0832 16.3748Z" />
            <path
                d="M38.3332 32.6248C38.3332 34.1206 37.1206 35.3332 35.6248 35.3332C34.1291 35.3332 32.9165 34.1206 32.9165 32.6248C32.9165 31.1291 34.1291 29.9165 35.6248 29.9165C37.1206 29.9165 38.3332 31.1291 38.3332 32.6248Z" />
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M37.0613 14.9387C37.8546 15.7319 37.8546 17.0181 37.0613 17.8113L20.8113 34.0613C20.0181 34.8546 18.7319 34.8546 17.9387 34.0613C17.1454 33.2681 17.1454 31.9819 17.9387 31.1887L34.1887 14.9387C34.9819 14.1454 36.2681 14.1454 37.0613 14.9387Z" />
        </symbol>

        <symbol id="pricing-2-icon" viewBox="0 0 55 49">
            <path opacity="0.4"
                d="M45.2146 2.08224L53.1285 11.0978C54.9652 13.19 55.0745 16.3086 53.3889 18.5275L32.0644 46.5991C29.7592 49.6336 25.2405 49.6337 22.9353 46.5991L1.61075 18.5275C-0.0747739 16.3086 0.0345202 13.19 1.87113 11.0978L9.78513 2.08224C10.878 0.837296 12.4444 0.125 14.0893 0.125H22.3809H33.2586H40.9104C42.5553 0.125 44.1217 0.837296 45.2146 2.08224Z" />
            <path
                d="M52.5519 12.6727C53.6738 12.6727 54.5832 13.5821 54.5832 14.7039C54.5832 15.8258 53.6738 16.7352 52.5519 16.7352L2.44775 16.7349C1.32593 16.7349 0.416504 15.8254 0.416504 14.7036C0.416504 13.5818 1.32593 12.6724 2.44775 12.6724L52.5519 12.6727Z" />
        </symbol>

        <symbol id="pricing-3-icon" viewBox="0 0 55 49">
            <path opacity="0.4"
                d="M25.6496 6.69121C19.8979 2.66086 11.8259 0.834438 5.81781 0.155528C2.84432 -0.180475 0.416504 2.30256 0.416504 5.36611V35.8749C0.416504 38.9384 2.84432 41.4224 5.81781 41.7584C11.8259 42.4373 19.8979 44.2637 25.6496 48.294C26.2023 48.6814 26.8511 48.875 27.4998 48.875V7.27216C26.8511 7.27216 26.2023 7.07851 25.6496 6.69121Z" />
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.53447 14.0449C6.70616 12.9363 7.74405 12.1767 8.85266 12.3484C12.381 12.8949 16.3545 13.8082 20.1077 15.2596C21.1541 15.6642 21.6743 16.8404 21.2697 17.8867C20.865 18.933 19.6888 19.4533 18.6425 19.0486C15.2337 17.7304 11.5589 16.8785 8.23092 16.3631C7.12231 16.1914 6.36278 15.1535 6.53447 14.0449Z" />
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.53447 24.8784C6.70616 23.7698 7.74405 23.0102 8.85266 23.1819C10.6394 23.4586 12.5351 23.8287 14.4554 24.3131C15.5431 24.5876 16.2024 25.6918 15.928 26.7796C15.6536 27.8673 14.5493 28.5266 13.4616 28.2522C11.6784 27.8023 9.90804 27.4563 8.23092 27.1966C7.12231 27.0249 6.36278 25.987 6.53447 24.8784Z" />
            <path
                d="M29.3503 6.69121C35.1019 2.66086 43.1739 0.834438 49.182 0.155528C52.1555 -0.180475 54.5833 2.30256 54.5833 5.36611V35.8749C54.5833 38.9384 52.1555 41.4224 49.182 41.7584C43.1739 42.4373 35.1019 44.2637 29.3503 48.294C28.7976 48.6814 28.1488 48.875 27.5 48.875V7.27216C28.1488 7.27216 28.7976 7.07851 29.3503 6.69121Z" />

        </symbol>

        <symbol id="ticket" viewBox="0 0 420 639">
            <path
                d="M1 30C1 13.9838 13.9837 1 30 1H390C406.016 1 419 13.9837 419 30V427.633C419 435.944 414.514 443.607 407.267 447.675C390.261 457.222 390.261 481.702 407.267 491.249C414.514 495.317 419 502.98 419 511.291V609C419 625.016 406.016 638 390 638H30C13.9838 638 1 625.016 1 609V511.96C1 503.329 5.89026 495.444 13.6218 491.607C31.9323 482.521 31.9323 456.403 13.6218 447.317C5.89026 443.48 1 435.595 1 426.964V30Z"
                fill="#0C0614" stroke="#1E1728" stroke-width="2" />
        </symbol>

        <symbol id="check-icon" viewBox="0 0 16 16">
            <path
                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
        </symbol>

        <symbol id="offcanvas-menu-icon" viewBox="0 0 44 44">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M0 3.5C0 1.567 1.09441 0 2.44444 0H22C23.35 0 24.4444 1.567 24.4444 3.5C24.4444 5.433 23.35 7 22 7H2.44444C1.09441 7 0 5.433 0 3.5ZM0 22.1667C0 20.2337 1.09442 18.6667 2.44444 18.6667H41.5556C42.9056 18.6667 44 20.2337 44 22.1667C44 24.0997 42.9056 25.6667 41.5556 25.6667H2.44444C1.09442 25.6667 0 24.0997 0 22.1667ZM2.44444 37C1.09442 37 0 38.567 0 40.5C0 42.433 1.09442 44 2.44444 44H41.5556C42.9056 44 44 42.433 44 40.5C44 38.567 42.9056 37 41.5556 37H2.44444Z" />
        </symbol>

        <symbol id="highlights-icon-1" viewBox="0 0 68 64">
            <path opacity="0.4"
                d="M37.2547 2.37863L44.6763 17.2929C45.223 18.3734 46.2666 19.1238 47.4702 19.2906L64.1404 21.7185C65.114 21.8552 65.9975 22.3688 66.5943 23.1525C67.1844 23.9263 67.4378 24.9068 67.2944 25.8706C67.1778 26.671 66.801 27.4114 66.2242 27.9783L54.145 39.6876C53.2614 40.5047 52.8614 41.7153 53.0747 42.8992L56.0487 59.3609C56.3654 61.3486 55.0485 63.2229 53.0747 63.5998C52.2612 63.7298 51.4277 63.5931 50.6942 63.2196L35.8244 55.4723C34.7208 54.9153 33.4172 54.9153 32.3136 55.4723L17.4437 63.2196C15.6167 64.1901 13.3529 63.5297 12.336 61.7288C11.9592 61.0118 11.8259 60.1947 11.9492 59.3976L14.9232 42.9326C15.1366 41.752 14.7332 40.5347 13.853 39.7176L1.77371 28.015C0.33674 26.6276 0.293397 24.3431 1.67703 22.9057C1.70703 22.8757 1.74037 22.8424 1.77371 22.809C2.34717 22.2254 3.10067 21.8552 3.91417 21.7585L20.5844 19.3272C21.7847 19.1572 22.8282 18.4135 23.3784 17.3262L30.5332 2.37863C31.17 1.09798 32.4903 0.300909 33.924 0.334259H34.3707C35.6143 0.484336 36.6979 1.25473 37.2547 2.37863Z" />
            <path
                d="M33.9737 55.057C33.328 55.077 32.699 55.2505 32.1332 55.5607L17.3361 63.2904C15.5255 64.1544 13.3589 63.4839 12.3438 61.7525C11.9677 61.0452 11.8312 60.2346 11.9577 59.4406L14.9131 43.0104C15.1128 41.8161 14.7134 40.6018 13.8448 39.7611L1.76012 28.0615C0.325665 26.657 0.299039 24.3518 1.70354 22.914C1.72351 22.8939 1.74015 22.8773 1.76012 22.8606C2.33257 22.2934 3.07142 21.9198 3.86686 21.803L20.5511 19.3477C21.7592 19.1942 22.8076 18.4403 23.3401 17.3461L30.5923 2.21031C31.2812 0.989312 32.6025 0.262049 34.0003 0.338778C33.9737 1.32959 33.9737 54.3831 33.9737 55.057Z" />
        </symbol>

        <symbol id="highlights-icon-2" viewBox="0 0 58 68">
            <path
                d="M28.417 44.7239H29.5827C35.9956 44.7239 41.4366 40.6166 43.3514 34.9213C43.6291 34.0922 42.993 33.2376 42.1114 33.2376H38.5207C37.3776 33.2376 36.4541 32.3256 36.4541 31.2C36.4541 30.0711 37.3776 29.1559 38.5207 29.1559H42.0016C43.1544 29.1559 44.0876 28.2343 44.0876 27.0959C44.0876 25.9575 43.1544 25.0359 42.0016 25.0359H38.5207C37.3776 25.0359 36.4541 24.1207 36.4541 22.9951C36.4541 21.8662 37.3776 20.951 38.5207 20.951H42.0016C43.1544 20.951 44.0876 20.0294 44.0876 18.891C44.0876 17.7526 43.1544 16.831 42.0016 16.831H38.5207C37.3776 16.831 36.4541 15.9158 36.4541 14.787C36.4541 13.6613 37.3776 12.7461 38.5207 12.7461H42.2955C43.1609 12.7461 43.7809 11.9298 43.5484 11.1038C41.8337 5.08011 36.2313 0.666748 29.5827 0.666748H28.417C20.4057 0.666748 13.9121 7.07633 13.9121 14.991V30.3996C13.9121 38.3111 20.4057 44.7239 28.417 44.7239Z" />
            <path opacity="0.4"
                d="M54.1046 26.7522C52.3222 26.7522 50.8756 28.1776 50.8756 29.941C50.8756 41.8514 41.0625 51.5423 29.0019 51.5423C16.9382 51.5423 7.1251 41.8514 7.1251 29.941C7.1251 28.1776 5.67848 26.7522 3.89605 26.7522C2.11361 26.7522 0.666992 28.1776 0.666992 29.941C0.666992 44.2909 11.6652 56.1374 25.7729 57.7287V64.1446C25.7729 65.9049 27.2163 67.3335 29.0019 67.3335C30.7844 67.3335 32.231 65.9049 32.231 64.1446V57.7287C46.3355 56.1374 57.3337 44.2909 57.3337 29.941C57.3337 28.1776 55.887 26.7522 54.1046 26.7522Z" />
        </symbol>

        <symbol id="highlights-icon-3" viewBox="0 0 60 68">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M0 50.2334V24.8567H60V50.4367C60 60.9 53.4138 67.3334 42.876 67.3334H17.0911C6.65203 67.3334 0 60.7667 0 50.2334ZM16.5313 42.0334C15.0165 42.1034 13.7651 40.9 13.6992 39.37C13.6992 37.8367 14.8847 36.57 16.3996 36.5C17.8814 36.5 19.0999 37.67 19.1328 39.1667C19.1987 40.7034 18.0132 41.97 16.5313 42.0334ZM30.0659 42.0334C28.551 42.1034 27.2997 40.9 27.2338 39.37C27.2338 37.8367 28.4193 36.57 29.9341 36.5C31.416 36.5 32.6345 37.67 32.6674 39.1667C32.7333 40.7034 31.5478 41.97 30.0659 42.0334ZM43.5016 54.3C41.9868 54.2667 40.7684 53 40.7684 51.4667C40.7355 49.9334 41.9539 48.67 43.4687 48.6367H43.5016C45.0494 48.6367 46.3008 49.9034 46.3008 51.4667C46.3008 53.0334 45.0494 54.3 43.5016 54.3ZM27.2338 51.4667C27.2997 53 28.551 54.2034 30.0659 54.1334C31.5478 54.07 32.7333 52.8034 32.6674 51.27C32.6345 49.77 31.416 48.6 29.9341 48.6C28.4193 48.67 27.2338 49.9334 27.2338 51.4667ZM13.6663 51.4667C13.7322 53 14.9835 54.2034 16.4984 54.1334C17.9802 54.07 19.1658 52.8034 19.0999 51.27C19.067 49.77 17.8485 48.6 16.3666 48.6C14.8518 48.67 13.6663 49.9334 13.6663 51.4667ZM40.8013 39.3367C40.8013 37.8034 41.9868 36.57 43.5016 36.5367C44.9835 36.5367 46.169 37.7334 46.2349 39.2034C46.2678 40.7367 45.0823 42.0034 43.6004 42.0334C42.0856 42.0667 40.8343 40.9 40.8013 39.37V39.3367Z" />
            <path opacity="0.4"
                d="M0.0107422 24.8564C0.0535523 22.8997 0.218207 19.0164 0.527756 17.7664C2.10844 10.7364 7.47617 6.26972 15.1491 5.63306H44.8527C52.4597 6.30306 57.8933 10.7997 59.474 17.7664C59.7802 18.9831 59.9449 22.8964 59.9877 24.8564H0.0107422Z" />
            <path
                d="M17.6831 15.9667C19.1321 15.9667 20.2188 14.8701 20.2188 13.4001V3.23675C20.2188 1.76675 19.1321 0.666748 17.6831 0.666748C16.2342 0.666748 15.1475 1.76675 15.1475 3.23675V13.4001C15.1475 14.8701 16.2342 15.9667 17.6831 15.9667Z" />
            <path
                d="M42.3169 15.9667C43.733 15.9667 44.8526 14.8701 44.8526 13.4001V3.23675C44.8526 1.76675 43.733 0.666748 42.3169 0.666748C40.868 0.666748 39.7812 1.76675 39.7812 3.23675V13.4001C39.7812 14.8701 40.868 15.9667 42.3169 15.9667Z" />
        </symbol>

        <symbol id="highlights-icon-4" viewBox="0 0 68 68">
            <path
                d="M27.8428 12.5185C28.0126 12.864 28.1247 13.2341 28.1749 13.6145L29.1031 27.4142L29.5638 34.3503C29.5685 35.0636 29.6804 35.7723 29.8959 36.4535C30.4523 37.7752 31.7909 38.6153 33.2472 38.5567L55.4381 37.1052C56.399 37.0893 57.327 37.4487 58.0178 38.1043C58.5935 38.6506 58.9651 39.3653 59.0822 40.1339L59.1215 40.6007C58.2032 53.3164 48.8642 63.9222 36.1748 66.66C23.4855 69.3978 10.4732 63.6144 4.20269 52.4497C2.39495 49.2061 1.26583 45.6409 0.881599 41.9633C0.721087 40.8746 0.65041 39.775 0.670256 38.6752C0.650439 25.0425 10.3586 13.2566 23.9481 10.4153C25.5837 10.1606 27.1871 11.0264 27.8428 12.5185Z" />
            <path opacity="0.4"
                d="M36.9006 0.669479C52.1002 1.05617 64.8749 11.986 67.3339 26.7077L67.3104 26.8163L67.2433 26.9743L67.2526 27.408C67.2178 27.9825 66.996 28.5352 66.6138 28.9817C66.2156 29.4468 65.6716 29.7635 65.0725 29.8864L64.7072 29.9366L39.1046 31.5954C38.253 31.6794 37.405 31.4048 36.7717 30.8399C36.2439 30.3692 35.9065 29.7337 35.8112 29.049L34.0927 3.48362C34.0628 3.39718 34.0628 3.30347 34.0927 3.21702C34.1162 2.51232 34.4264 1.84621 34.954 1.36751C35.4816 0.888814 36.1827 0.637413 36.9006 0.669479Z" />
        </symbol>

        <symbol id="highlights-icon-5" viewBox="0 0 68 68">
            <path opacity="0.4"
                d="M38.3503 13.6082V14.2183C36.7147 14.1862 35.0791 14.1862 33.4435 14.1862V13.6403C33.4435 11.4245 31.5789 9.6262 29.3545 9.6262H26.1161C22.4196 9.6262 19.4102 6.67183 19.4102 3.0752C19.4102 1.75858 20.5224 0.666748 21.8635 0.666748C23.2374 0.666748 24.3169 1.75858 24.3169 3.0752C24.3169 4.03858 25.1347 4.80929 26.1161 4.80929H29.3545C34.294 4.8414 38.3176 8.79127 38.3503 13.6082Z" />
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M44.547 14.2756C45.5973 14.29 46.6501 14.3044 47.7066 14.3152C59.0576 14.3152 67.3337 22.4076 67.3337 33.5829V47.9372C67.3337 59.1125 59.0576 67.2049 47.7066 67.2049C43.1596 67.3012 38.6127 67.3333 34.033 67.3333C29.4534 67.3333 24.841 67.3012 20.2941 67.2049C8.94308 67.2049 0.666992 59.1125 0.666992 47.9372V33.5829C0.666992 22.4076 8.94308 14.3152 20.3268 14.3152C24.612 14.251 28.9954 14.1868 33.4442 14.1868C35.0798 14.1868 36.7154 14.1868 38.351 14.2189C40.4131 14.2189 42.4753 14.2472 44.547 14.2756ZM30.173 43.1524H26.7383V46.5564C26.7383 47.873 25.6261 48.9649 24.2849 48.9649C22.911 48.9649 21.8315 47.873 21.8315 46.5564V43.1524H18.3641C17.0229 43.1524 15.9107 42.0927 15.9107 40.744C15.9107 39.4274 17.0229 38.3355 18.3641 38.3355H21.8315V34.9637C21.8315 33.6471 22.911 32.5552 24.2849 32.5552C25.6261 32.5552 26.7383 33.6471 26.7383 34.9637V38.3355H30.173C31.5142 38.3355 32.6264 39.4274 32.6264 40.744C32.6264 42.0927 31.5142 43.1524 30.173 43.1524ZM44.0756 37.7254H44.4027C45.7439 37.7254 46.8561 36.6657 46.8561 35.3169C46.8561 34.0003 45.7439 32.9085 44.4027 32.9085H44.0756C42.7017 32.9085 41.6222 34.0003 41.6222 35.3169C41.6222 36.6657 42.7017 37.7254 44.0756 37.7254ZM49.6693 48.7722H49.9964C51.3376 48.7722 52.4498 47.7125 52.4498 46.3637C52.4498 45.0471 51.3376 43.9553 49.9964 43.9553H49.6693C48.2954 43.9553 47.2159 45.0471 47.2159 46.3637C47.2159 47.7125 48.2954 48.7722 49.6693 48.7722Z" />
        </symbol>

        <symbol id="shop-icon-xl" viewBox="0 0 42 46">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M16.0002 3.49097C16.5179 2.76484 16.378 1.73472 15.6877 1.19012C14.9973 0.645521 14.0179 0.792682 13.5002 1.51881L7.44531 10.0103C7.76654 9.97026 8.09404 9.94962 8.42687 9.94962H11.3948L16.0002 3.49097ZM34.5551 10.0103C34.2338 9.97027 33.9062 9.94963 33.5733 9.94963H30.6055L26.0002 3.49097C25.4824 2.76484 25.6223 1.73472 26.3127 1.19012C27.003 0.645521 27.9824 0.792682 28.5002 1.51881L34.5551 10.0103Z" />
            <g opacity="0.4">
                <path
                    d="M33.5735 9.94962L8.42712 9.94962C3.76986 9.94962 0.15754 13.9904 0.167011 18.7148H41.8336C41.8431 13.9904 38.2308 9.94962 33.5735 9.94962Z" />
            </g>
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M38.2544 38.0157C37.4631 42.0884 34.0848 45.0103 30.1674 45.0103H11.8331C7.91572 45.0103 4.53749 42.0884 3.74618 38.0157L0.340102 20.4854C0.225223 19.8941 0.169084 19.3069 0.166992 18.7298L41.8335 18.7298C41.8314 19.3069 41.7753 19.8941 41.6604 20.4854L38.2544 38.0157ZM11.1045 27.48C11.1045 26.5723 11.804 25.8365 12.667 25.8365H29.3337C30.1966 25.8365 30.8962 26.5723 30.8962 27.48C30.8962 28.3876 30.1966 29.1234 29.3337 29.1234H12.667C11.804 29.1234 11.1045 28.3876 11.1045 27.48ZM14.7503 34.6006C13.8874 34.6006 13.1878 35.3364 13.1878 36.244C13.1878 37.1517 13.8874 37.8875 14.7503 37.8875H27.2503C28.1133 37.8875 28.8128 37.1517 28.8128 36.244C28.8128 35.3364 28.1133 34.6006 27.2503 34.6006H14.7503Z" />
        </symbol>
    </svg>
    <!-- SVG ======================-->


    <!-- Scripts/Plugins ======================-->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/plyr@3"></script>
    <script src="{{asset('israel-assets')}}/js/jquery-3.7.0.min.js"></script>
    <script src="{{asset('israel-assets')}}/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('israel-assets')}}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{asset('israel-assets')}}/js/swiper-bundle.min.js"></script>
    <!-- text-opacity -->
    <script src="{{asset('israel-assets')}}/js/gsap.min.js"></script>
    <script src="{{asset('israel-assets')}}/js/scrollTrigger.min.js"></script>
    <script src="{{asset('israel-assets')}}/js/text-opacity.js"></script>
    <!-- text-opacity -->

    <!-- counter-up -->
    <script src="{{asset('israel-assets')}}/js/odometer.js"></script>
    <!-- counter-up -->
    <script src="{{asset('israel-assets')}}/js/countdown.js"></script>
    <script src="{{asset('israel-assets')}}/js/scroll.js"></script>

    <!-- map -->
    <script src="{{asset('israel-assets')}}/js/leaflet.js"></script>
    <script src="{{asset('israel-assets')}}/js/leaflet-routing-machine.min.js"></script>
    <script src="{{asset('israel-assets')}}/js/leaflet-scripts.js"></script>
    <!-- map -->


    <script src="{{asset('israel-assets')}}/js/scripts.js"></script>
    <!-- Scripts/Plugins ======================-->
   
<script>
document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('player');
    
    const videoUrl = 'https://live.baze.co.ke/videos/israel/promovid.m3u8';

    // Function to determine video type
    function getVideoType(url) {
        const extension = url.split('.').pop();
        if (extension === 'm3u8') {
            return 'application/vnd.apple.mpegurl';
        } else if (extension === 'mp4') {
            return 'video/mp4';
        } else if (extension === 'mov') {
            return 'video/quicktime';
        } else {
            return '';
        }
    }

    // Function to load video based on its type
    function loadVideo(url) {
        const type = getVideoType(url);

        if (type === 'application/vnd.apple.mpegurl') {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, () => {
                    video.play();
                });
            } else if (video.canPlayType(type)) {
                video.src = url;
                video.addEventListener('loadedmetadata', () => {
                    video.play();
                });
            } else {
                console.error('HLS is not supported in this browser');
            }
        } else if (video.canPlayType(type)) {
            video.src = url;
            video.addEventListener('loadedmetadata', () => {
                video.play();
            });
        } else {
            console.error('This video format is not supported in this browser');
        }
    }

    // Load the video
    loadVideo(videoUrl);
});
</script>
</body>


<!--  home-3 57:51 GMT -->

</html>