<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="@yield('description', config('site.description'))">
    <meta name="keywords" content="@yield('keywords', config('site.keywords'))">
    <meta name="author" content="{{ config('site.name') }}">
    <meta name="copyright" content="{{ config('site.name') }}">
    <meta name="application-name" content="@yield('title', config('site.title'))">

    <link rel="canonical" href="{{ request()->fullUrl() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">

    <title>{{ ucfirst($name) }} : {{ $title }}</title>
    <link href="{{ asset('backend_assets/css/app.css?'.time())}}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />
    @yield('header')
</head>

<body>
    @include('Backend.includes.sidebar')
    <div class="main">
        <nav class="navbar navbar-expand navbar-light bg-white sticky-top">
            <a class="sidebar-toggle d-flex mr-3 text-dark">
                <i class="fas fa-bars fa-lg"></i>
            </a>





            <div class="navbar-collapse collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle ml-2 d-inline-block d-sm-none" href="#" id="userDropdown" data-toggle="dropdown">
                            <div class="position-relative">
                                <i class="align-middle mt-n1" data-feather="settings"></i>
                            </div>
                        </a>
                        <a class="nav-link nav-link-user dropdown-toggle d-none d-sm-inline-block" href="#" id="userDropdown" data-toggle="dropdown">
                            <img src="{{ asset('backend_assets/img/avatar.png') }}" class="avatar img-fluid rounded-circle mr-1" alt="Avatar " />
                            <span class="text-dark">{{ Auth::guard('admin')->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('backend.profile.index') }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">{{ __('Sign out') }}</a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>



                </ul>
            </div>
        </nav>