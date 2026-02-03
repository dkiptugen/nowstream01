<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

     <!-- Favicons -->

    <link rel="icon" href="{{ secure_asset('favicon.ico') }}" type="image/x-icon">

    <title>{{ $title }}</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <!-- Styles -->
    <link href="{{ secure_asset('backend_assets/css/app.css?'.time()) }}" rel="stylesheet">


</head>
<body>
    <main class="main h-100 w-100">
        <div class="container h-100">
            <div class="row h-100">
                @yield('content')
            </div>
        </div>
    </main>
    <!-- Scripts -->
    <script src="{{ secure_asset('backend_assets/js/app.js?'.time()) }}" ></script>

</body>
</html>
