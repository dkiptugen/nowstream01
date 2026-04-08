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

        body {
            overflow-x: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .navbar-wrap>ul>li>a {
            display: inline-flex;
        }

        .menu-area .container.custom-container {
            width: min(100%, 1440px);
            padding-left: clamp(16px, 3vw, 28px);
            padding-right: clamp(16px, 3vw, 28px);
        }

        .menu-wrap,
        .menu-nav {
            min-width: 0;
        }

        .header-action ul {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .header-action ul li {
            margin-right: 0 !important;
        }

        .parent-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            color: #8fd7ff;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .nav-link:hover .parent-icon,
        .nav-link:focus .parent-icon,
        .menu-item-has-children:hover > .nav-link .parent-icon {
            background: rgba(255, 210, 79, 0.16);
            color: #ffd24f;
            transform: translateY(-1px);
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

        .breadcrumb-area .container,
        .movie-area .container,
        .top-rated-movie .container,
        .newsletter-area .container,
        .footer-top-wrap .container,
        .copyright-wrap .container {
            padding-left: clamp(16px, 3vw, 28px);
            padding-right: clamp(16px, 3vw, 28px);
        }

        .breadcrumb-content .title {
            overflow-wrap: anywhere;
        }

        .breadcrumb-content .breadcrumb,
        .ucm-nav-wrap .nav,
        .footer-menu nav,
        .quick-link-list ul {
            flex-wrap: wrap;
        }

        .ucm-nav-wrap .nav {
            gap: 10px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .ucm-nav-wrap .nav::-webkit-scrollbar {
            display: none;
        }

        .ucm-nav-wrap .nav-item {
            flex: 0 0 auto;
        }

        .tr-movie-active {
            row-gap: 20px;
        }

        .nowstream-grid-card {
            position: relative !important;
        }

        .nowstream-media-card {
            height: 100%;
            border-radius: 18px;
            overflow: hidden;
        }

        .nowstream-media-card__image {
            width: 100%;
            display: block;
        }

        .nowstream-media-card__title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            overflow-wrap: anywhere;
        }

        .nowstream-media-card__meta ul,
        .nowstream-media-card__meta li {
            min-width: 0;
        }

        .nowstream-media-card__meta .channel,
        .nowstream-media-card__meta .views,
        .nowstream-media-card__meta .rating {
            overflow-wrap: anywhere;
        }

        .section-title .title,
        .episode-top-wrap .title {
            overflow-wrap: anywhere;
        }

        .newsletter-inner-wrap,
        .footer-menu-wrap,
        .footer-quick-link-wrap {
            overflow: hidden;
        }

        .newsletter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .newsletter-form input {
            flex: 1 1 220px;
            min-width: 0;
        }

        .newsletter-form .btn {
            flex: 0 0 auto;
        }

        .footer-menu .navigation,
        .quick-link-list ul,
        .footer-social ul {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 18px;
        }

        .footer-search form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-search input {
            min-width: 0;
            width: 100%;
        }

        .footer-search button {
            flex: 0 0 auto;
        }

        .mobile-menu .navigation li > a,
        .mobile-menu .submenu li > a {
            overflow-wrap: anywhere;
        }

        @media (max-width: 1199px) {
            .menu-wrap {
                padding: 16px 0;
            }

            .header-btn .btn {
                min-height: 40px;
            }

            .breadcrumb-area {
                padding: 128px 0 72px;
            }
        }

        @media (max-width: 991px) {
            .menu-area .container.custom-container {
                padding-left: 16px;
                padding-right: 16px;
            }

            .mobile-nav-toggler {
                margin-left: auto;
            }

            .breadcrumb-area {
                padding: 120px 0 56px;
            }

            .breadcrumb-content {
                text-align: left;
            }

            .breadcrumb-content .title {
                font-size: clamp(2rem, 8vw, 2.8rem);
                line-height: 1.1;
            }

            .breadcrumb-content .breadcrumb {
                justify-content: flex-start;
                gap: 6px 10px;
            }

            .section-title,
            .episode-top-wrap,
            .newsletter-content,
            .footer-logo,
            .footer-search,
            .copyright-text,
            .payment-method-img {
                text-align: left !important;
            }

            .row.align-items-end.mb-60,
            .episode-top-wrap {
                margin-bottom: 28px !important;
            }

            .newsletter-inner-wrap,
            .footer-menu-wrap {
                padding: 28px 22px;
            }

            .newsletter-content h4,
            .section-title .title,
            .episode-top-wrap .title {
                font-size: clamp(1.5rem, 6vw, 2rem);
                line-height: 1.15;
            }
        }

        @media (max-width: 767px) {
            body {
                padding-bottom: calc(92px + env(safe-area-inset-bottom, 0px));
            }

            #sticky-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1045;
                transition: transform 0.28s ease, opacity 0.28s ease, box-shadow 0.28s ease, background-color 0.28s ease;
            }

            #sticky-header.is-mobile-hidden {
                transform: translateY(calc(-100% - 8px));
                opacity: 0;
            }

            body.mobile-menu-visible #sticky-header {
                opacity: 1;
                transform: translateY(0);
            }

            .menu-wrap {
                padding: 12px 0;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 24px;
                background: rgba(7, 15, 24, 0.84);
                backdrop-filter: blur(18px);
                box-shadow: 0 14px 34px rgba(0, 0, 0, 0.22);
            }

            .logo img.logo-icon {
                max-height: 34px;
                width: auto;
            }

            .menu-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 0 14px;
            }

            .mobile-nav-toggler {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
                margin-left: auto;
                border-radius: 16px;
                background: linear-gradient(180deg, rgba(255, 210, 79, 0.18), rgba(255, 210, 79, 0.06));
                color: #ffffff;
                font-size: 22px;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
                flex: 0 0 auto;
                float: none;
                cursor: pointer;
                z-index: 4;
            }

            .menu-nav .logo {
                min-width: 0;
                flex: 1 1 auto;
            }

            .menu-nav .logo a {
                display: inline-flex;
                align-items: center;
            }

            .mobile-menu {
                width: min(100%, 380px);
                padding-right: 0;
                z-index: 1055;
            }

            .menu-backdrop {
                z-index: 1050;
                background: rgba(4, 10, 16, 0.74);
                backdrop-filter: blur(10px);
            }

            .mobile-menu .menu-box {
                background:
                    radial-gradient(circle at top, rgba(24, 92, 145, 0.22), transparent 34%),
                    linear-gradient(180deg, #07111a 0%, #091520 100%);
                box-shadow: -16px 0 44px rgba(0, 0, 0, 0.34);
            }

            .mobile-menu .nav-logo {
                padding: 0;
            }

            .mobile-menu__panel {
                padding: 22px 18px 16px;
            }

            .mobile-menu__topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 18px;
            }

            .mobile-menu__brand {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            .mobile-menu__brand-mark {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
                border-radius: 16px;
                background: linear-gradient(180deg, rgba(255, 210, 79, 0.24), rgba(143, 215, 255, 0.16));
                color: #ffffff;
                font-size: 22px;
            }

            .mobile-menu__brand-copy {
                min-width: 0;
            }

            .mobile-menu__eyebrow {
                margin: 0 0 4px;
                color: #8fd7ff;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.16em;
                text-transform: uppercase;
            }

            .mobile-menu__title {
                margin: 0;
                color: #ffffff;
                font-size: 18px;
                font-weight: 700;
                line-height: 1.2;
            }

            .mobile-menu .close-btn {
                position: static;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.08);
                color: #ffffff;
                font-size: 18px;
                line-height: 1;
            }

            .mobile-menu__account {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 14px;
                padding: 14px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.04);
            }

            .mobile-menu__account-main {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            .mobile-menu__avatar,
            .mobile-menu__avatar-fallback {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                object-fit: cover;
            }

            .mobile-menu__avatar-fallback {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(255, 210, 79, 0.16);
                color: #ffd24f;
                font-size: 18px;
            }

            .mobile-menu__account-copy {
                min-width: 0;
            }

            .mobile-menu__account-name,
            .mobile-menu__account-meta {
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .mobile-menu__account-name {
                color: #ffffff;
                font-size: 14px;
                font-weight: 700;
            }

            .mobile-menu__account-meta {
                color: rgba(235, 242, 250, 0.68);
                font-size: 11px;
            }

            .mobile-menu__account-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: #ffd24f;
                font-size: 12px;
                font-weight: 700;
                white-space: nowrap;
            }

            .mobile-menu__shortcuts {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 16px;
            }

            .mobile-menu__shortcut {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                min-height: 86px;
                padding: 12px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.04);
                color: #f3f7fb;
            }

            .mobile-menu__shortcut-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border-radius: 12px;
                background: rgba(143, 215, 255, 0.12);
                color: #8fd7ff;
                font-size: 18px;
            }

            .mobile-menu__shortcut-label {
                font-size: 12px;
                font-weight: 700;
                line-height: 1.25;
            }

            .mobile-menu .menu-outer {
                padding: 0 18px 18px;
            }

            .mobile-menu .navigation {
                display: grid;
                gap: 10px;
            }

            .mobile-menu .navigation li,
            .mobile-menu .navigation > li:last-child,
            .mobile-menu .navigation li > ul > li:first-child {
                border: 0;
            }

            .mobile-menu .navigation li > a {
                display: flex;
                align-items: center;
                gap: 12px;
                min-height: 56px;
                padding: 14px 18px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.035);
                font-size: 14px;
                font-weight: 700;
            }

            .mobile-menu .navigation li > a .parent-icon {
                flex: 0 0 auto;
                width: 34px;
                height: 34px;
                border-radius: 12px;
                background: rgba(143, 215, 255, 0.12);
                color: #8fd7ff;
            }

            .mobile-menu .navigation li > a .menu-title {
                flex: 1 1 auto;
                min-width: 0;
            }

            .mobile-menu .navigation li.current > a,
            .mobile-menu .navigation li > a:hover,
            .mobile-menu .navigation li > a:focus {
                background: linear-gradient(180deg, rgba(255, 210, 79, 0.14), rgba(255, 210, 79, 0.05));
                color: #ffffff;
            }

            .mobile-menu .navigation li.current > a .parent-icon,
            .mobile-menu .navigation li > a:hover .parent-icon,
            .mobile-menu .navigation li > a:focus .parent-icon {
                background: rgba(255, 210, 79, 0.16);
                color: #ffd24f;
            }

            .mobile-menu .navigation li ul {
                margin-top: 10px;
                padding-left: 12px;
            }

            .mobile-menu .navigation li ul li > a {
                margin-left: 0;
                min-height: 48px;
                padding: 12px 16px;
                font-size: 13px;
                border-radius: 16px;
            }

            .mobile-menu .navigation li.menu-item-has-children .dropdown-btn {
                right: 14px;
                top: 12px;
                width: 32px;
                height: 32px;
                line-height: 32px;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.08);
            }

            .mobile-menu .social-links {
                padding: 0 18px 26px;
                text-align: left;
            }

            .mobile-menu .social-links ul {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .mobile-menu .social-links li {
                margin: 0;
            }

            .mobile-menu .social-links li a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.06);
            }

            .breadcrumb-area {
                padding: 112px 0 44px;
            }

            .movie-area,
            .top-rated-movie,
            .newsletter-area {
                padding-top: 52px;
                padding-bottom: 52px;
            }

            .tr-movie-active {
                row-gap: 16px;
            }

            .nowstream-media-card {
                border-radius: 16px;
            }

            .movie-content {
                padding-top: 12px;
            }

            .newsletter-form > * {
                width: 100%;
            }

            .newsletter-form .btn {
                justify-content: center;
            }

            .footer-menu .navigation,
            .quick-link-list ul,
            .footer-social ul {
                gap: 10px 14px;
            }

            .footer-search form {
                flex-direction: column;
                align-items: stretch;
            }

            .page-footer {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1040;
                padding: 0 12px calc(12px + env(safe-area-inset-bottom, 0px));
                pointer-events: none;
            }

            .page-footer__dock {
                display: grid;
                grid-template-columns: repeat(6, minmax(0, 1fr));
                align-items: stretch;
                gap: 6px;
                margin: 0;
                padding: 10px 8px;
                list-style: none;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 26px;
                background: rgba(7, 15, 24, 0.92);
                backdrop-filter: blur(18px);
                box-shadow: 0 18px 44px rgba(0, 0, 0, 0.3);
                pointer-events: auto;
            }

            .page-footer__item {
                min-width: 0;
            }

            .page-footer__link,
            .page-footer__toggle {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                min-height: 58px;
                width: 100%;
                padding: 6px 4px;
                border: 0;
                border-radius: 18px;
                background: transparent;
                color: rgba(235, 242, 250, 0.74);
                text-align: center;
                transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            }

            .page-footer__link:hover,
            .page-footer__link:focus,
            .page-footer__toggle:hover,
            .page-footer__toggle:focus,
            .page-footer__item.is-active .page-footer__link,
            .page-footer__item.is-active .page-footer__toggle {
                background: linear-gradient(180deg, rgba(255, 210, 79, 0.2), rgba(255, 210, 79, 0.08));
                color: #ffffff;
                transform: translateY(-1px);
            }

            .page-footer__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 30px;
                height: 30px;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.06);
                font-size: 18px;
                line-height: 1;
            }

            .page-footer__item.is-active .page-footer__icon,
            .page-footer__toggle[aria-expanded="true"] .page-footer__icon {
                background: rgba(255, 210, 79, 0.18);
                color: #ffd24f;
            }

            .page-footer__label {
                display: block;
                max-width: 100%;
                overflow: hidden;
                color: inherit;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.02em;
                line-height: 1.1;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .page-footer__avatar {
                width: 30px;
                height: 30px;
                border-radius: 12px;
                object-fit: cover;
            }

            .page-footer__menu {
                min-width: 180px;
                margin-bottom: 14px !important;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 18px;
                background: rgba(7, 15, 24, 0.96);
                box-shadow: 0 18px 38px rgba(0, 0, 0, 0.24);
            }

            .page-footer__menu .dropdown-item {
                color: #f3f7fb;
            }

            .page-footer__menu .dropdown-item i {
                margin-right: 8px;
            }
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
                        <div class="menu-wrap">
                            <nav class="menu-nav show">
                                <div class="logo">
                                    <a href="{{url('/')}}">
                                        <img src="{{ asset('assets/img/logo/logo.png') }}" class="logo-icon" alt="Streamer Logo" height="40">
                                    </a>
                                </div>
                                <div class="mobile-nav-toggler" aria-label="Open menu"><i class="bx bx-grid-alt"></i></div>
                                @include('Frontend.includes.nav')

                                <div class="header-action d-none d-md-block">
                                    <ul>
                                        <li class="header-search"><a href="#" data-bs-toggle="modal"
                                                data-bs-target="#search-modal"><i class="bx bx-search-alt-2"></i></a></li>
                                        <li class="header-search"><a href="{{ route('video.myfavorite') }}"><i class="bx bx-heart"></i></a></li>
                                        <li class="header-search"><a href="{{ route('watch.content') }}"><i class="bx bx-history"></i></a></li>
                                        <li class="header-search">
                                            <a href="{{ route('cart.index') }}" class="header-cart-link">
                                                <i class="bx bx-cart-alt"></i>
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
                            <nav class="menu-box">
                                <div class="mobile-menu__panel">
                                    <div class="mobile-menu__topbar">
                                        <div class="mobile-menu__brand">
                                            <span class="mobile-menu__brand-mark"><i class="bx bx-layer"></i></span>
                                            <div class="mobile-menu__brand-copy">
                                                <p class="mobile-menu__eyebrow">Nowstream</p>
                                                <h2 class="mobile-menu__title">Browse the app</h2>
                                            </div>
                                        </div>
                                        <div class="close-btn"><i class="bx bx-x"></i></div>
                                    </div>

                                    <div class="mobile-menu__account">
                                        <div class="mobile-menu__account-main">
                                            @auth
                                                <img src="{{ Auth::user()->image ?? asset('avatar.png') }}" class="mobile-menu__avatar" alt="{{ Auth::user()->name }}">
                                                <div class="mobile-menu__account-copy">
                                                    <span class="mobile-menu__account-name">{{ Auth::user()->name }}</span>
                                                    <span class="mobile-menu__account-meta">Your profile and history</span>
                                                </div>
                                            @else
                                                <span class="mobile-menu__avatar-fallback"><i class="bx bx-user"></i></span>
                                                <div class="mobile-menu__account-copy">
                                                    <span class="mobile-menu__account-name">Welcome back</span>
                                                    <span class="mobile-menu__account-meta">Sign in to save your picks</span>
                                                </div>
                                            @endauth
                                        </div>
                                        <a href="{{ auth()->check() ? route('profile.show') : route('user.login') }}" class="mobile-menu__account-link">
                                            <span>{{ auth()->check() ? 'Open' : 'Login' }}</span>
                                            <i class="bx bx-chevron-right"></i>
                                        </a>
                                    </div>

                                    <div class="mobile-menu__shortcuts">
                                        <a href="#" class="mobile-menu__shortcut" data-bs-toggle="modal" data-bs-target="#search-modal">
                                            <span class="mobile-menu__shortcut-icon"><i class="bx bx-search-alt-2"></i></span>
                                            <span class="mobile-menu__shortcut-label">Search</span>
                                        </a>
                                        <a href="{{ route('video.myfavorite') }}" class="mobile-menu__shortcut">
                                            <span class="mobile-menu__shortcut-icon"><i class="bx bx-heart"></i></span>
                                            <span class="mobile-menu__shortcut-label">Favorites</span>
                                        </a>
                                        <a href="{{ route('cart.index') }}" class="mobile-menu__shortcut">
                                            <span class="mobile-menu__shortcut-icon"><i class="bx bx-cart-alt"></i></span>
                                            <span class="mobile-menu__shortcut-label">Cart</span>
                                        </a>
                                    </div>

                                    <div class="nav-logo d-none"><a href="{{url('/')}}">
                                            <img src="{{ asset('logo1.png') }}" class="logo-icon" alt="Streamer Logo">
                                        </a>
                                    </div>
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
