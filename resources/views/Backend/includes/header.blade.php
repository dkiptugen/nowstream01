<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ $author }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ ucfirst($name) }} : {{ $title }}</title>
    <link href="{{ asset('backend_assets/css/app.css?'.time())}}" rel="stylesheet">
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
        <ul class="navbar-nav align-items-center">

            <a class="nav-link d-flex align-items-center p-0" href="#" id="siteDropdown" data-toggle="dropdown">
                <img src="{{ Auth::user()->active_channel->thumbnail }}" class="rounded-circle navbar-brand py-0 border " style="object-fit: cover; object-position: center" height="50" width="50" alt="">
                <div class=" font-14">{{ Auth::user()->active_channel->name }}</div>
            </a>

            <div class="dropdown-menu" aria-labelledby="siteDropdown">
                @php($channels =Illuminate\Support\Facades\Cache::get('user_channels_'.Auth::user()->id)) 
                @if(is_array($channels))
                    @foreach( $channels as $channel)
                        <a class="dropdown-item" href="{{ route('change_channel',$channel->identifier) }}">
                            <img src="{{ $channel->thumbnail }}" class="rounded-circle my-0 py-0"  style="object-fit: cover; object-position: center" height="50" width="50" alt="">
                            {{ $channel->name }}</a>
                    @endforeach
                @endif

            </div>
        </ul>





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
                        <span class="text-dark">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('admin.profile.index') }}">Profile</a>
                        @canany(['view_channel','view_specific_channel'])

                                <a href="{{ route('channel.index') }}" class="dropdown-item">
                                    {{ __('Channels')}}
                                </a>
                        @endcanany
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"  onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">{{ __('Sign out') }}</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>



            </ul>
        </div>
    </nav>

