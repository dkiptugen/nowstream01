{{-- CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Basic SEO --}}
<meta name="description" content="@yield('description', config('site.description'))">
<meta name="keywords" content="@yield('keywords', config('site.keywords'))">
<meta name="author" content="{{ config('site.name') }}">
<meta name="copyright" content="{{ config('site.name') }}">
<meta name="application-name" content="@yield('title', config('site.title'))">

{{-- Canonical --}}
<link rel="canonical" href="{{ request()->fullUrl() }}">

{{-- Favicon --}}
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">

{{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
<meta property="og:site_name" content="{{ config('site.name') }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:title" content="@yield('title', config('site.title'))">
<meta property="og:description" content="@yield('description', config('site.description'))">
<meta property="og:image" content="@yield('image', config('site.image'))">
<meta property="og:locale" content="en_GB">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{{ config('site.twitter_handle') }}">
<meta name="twitter:title" content="@yield('title', config('site.title'))">
<meta name="twitter:description" content="@yield('description', config('site.description'))">
<meta name="twitter:image" content="@yield('image', config('site.image'))">
<link href="{{ asset('frontend-assets/css/pace.min.css') }}" rel="stylesheet" />
<script src="{{ asset('frontend-assets/js/pace.min.js') }}"></script>
<!-- Bootstrap CSS -->
<link href="{{ asset('frontend-assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('frontend-assets/css/bootstrap-extended.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
<link href="{{ asset('frontend-assets/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('frontend-assets/css/icons.css') }}" rel="stylesheet">
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        @yield('content')
    </div>
    <!-- end wrapper -->
    <script src="{{ asset('frontend-assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('frontend-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="{{ asset('frontend-assets/js/app.js') }}"></script>
</body>


</html>