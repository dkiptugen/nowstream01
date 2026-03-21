@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
 @endphp
        <!DOCTYPE html>
        <html lang="en" data-theme="light">

        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>{{ $title??'' }}</title>
            <script>
                (function () {
                    const storedTheme = localStorage.getItem('app-theme');
                    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const resolvedTheme = storedTheme === 'dark' || storedTheme === 'light'
                        ? storedTheme
                        : (prefersDark ? 'dark' : 'light');

                    document.documentElement.setAttribute('data-theme', resolvedTheme);
                }());
            </script>
            <link href="{{ asset('backend_assets/css/app.css?v='.(file_exists(public_path('assets/css/app.css')) ? filemtime(public_path('assets/css/app.css')) : time())) }}" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
            @include('Backend.includes.meta')
            <meta name="csrf-token" content="{{ csrf_token() }}">
            @yield('header')

        </head>

        <body>
        <a href="#main-content" class="visually-hidden-focusable position-absolute top-0 start-0 m-3 px-3 py-2 bg-white border rounded text-dark">
            Skip to main content
        </a>
        <div class="wrapper">

            @include('Backend.includes.sidebar')

            <div class="main">
                <nav class="navbar navbar-expand navbar-light navbar-bg" aria-label="Top navigation">
                    <button
                        type="button"
                        class="sidebar-toggle js-sidebar-toggle btn btn-link p-0 border-0 text-decoration-none"
                        aria-label="Toggle sidebar navigation"
                        aria-controls="sidebar"
                        aria-expanded="true"
                    >
                        <i class="hamburger align-self-center"></i>
                    </button>

                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav navbar-align">
                            <li class="nav-item">
                                <button
                                    type="button"
                                    class="btn btn-theme-toggle nav-theme-toggle"
                                    data-theme-toggle
                                    aria-label="Switch to dark mode"
                                    title="Switch theme"
                                >
                            <span class="theme-toggle-icon theme-toggle-icon-light" aria-hidden="true">
                                <i class="align-middle" data-feather="sun"></i>
                            </span>
                                    <span class="theme-toggle-icon theme-toggle-icon-dark" aria-hidden="true">
                                <i class="align-middle" data-feather="moon"></i>
                            </span>
                                    <span class="theme-toggle-label">Light</span>
                                </button>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown" aria-label="Open notifications" aria-expanded="false">
                                    <div class="position-relative">
                                        <i class="align-middle" data-feather="bell" aria-hidden="true"></i>
                                        <span class="indicator" data-top-notification-count aria-label="0 notifications">0</span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                                    <div class="dropdown-menu-header" data-top-notification-header>
                                        No New Notifications
                                    </div>
                                    <div class="list-group" data-top-notification-list aria-live="polite" aria-atomic="true">
                                        <div class="list-group-item text-center text-muted py-4" data-top-notification-empty-state>
                                            Notifications will appear here in real time.
                                        </div>
                                    </div>
                                    <div class="dropdown-menu-footer">
                                        <a href="#" class="text-muted">Show all notifications</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown" aria-label="Open account menu" aria-expanded="false">
                                    <i class="align-middle" data-feather="settings" aria-hidden="true"></i>
                                </a>

                                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown" aria-label="Open account menu" aria-expanded="false">
                                    <img src="{{ asset('assets/img/avatar.png')
}}" class="avatar img-fluid rounded me-1" alt="{{ Auth::guard('admin')->user()->name }}" /> <span class="text-dark">{{ Auth::guard('admin')->user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('backend.profile.index') }}"><i class="align-middle me-1" data-feather="user" aria-hidden="true"></i> Profile</a>
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="align-middle me-1" data-feather="log-out" aria-hidden="true"></i> {{ __('Sign out') }}</a>
                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
