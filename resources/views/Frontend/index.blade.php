@extends('Frontend.includes.layout')


@section('content')

<div class="hero-area">
    @foreach($current_event as $event)
        <!-- banner-area -->
        <section class="banner-area banner-bg" data-background="{{asset('/assets/img/banner/banner_bg01.png')}}">
            <div class="container custom-container">
                <div class="row">
                    <div class="col-xl-6 col-lg-8">
                        <div class="banner-content">
                            <h6 class="sub-title wow fadeInUp" data-wow-delay=".2s" data-wow-duration="1.8s">Baze Live</h6>
                            <h2 class="title wow fadeInUp" data-wow-delay=".4s" data-wow-duration="1.8s">Buy
                                {{$event->title}} Access By <span><a class=""
                                        href="{{ url("/event/{$event->id}/{$event->slug}") }}">Clicking Here</a></span>.
                            </h2>
                            <div class="banner-meta wow fadeInUp" data-wow-delay=".6s" data-wow-duration="1.8s">
                                <ul>
                                    <li class="quality">
                                        <span>Pg 18</span>
                                        <span>hd</span>
                                    </li>
                                    <li class="category">
                                        <a href="#">Comedy,</a>
                                        <a href="#">Entertainment</a>
                                    </li>
                                    <li class="release-time">
                                        <span><i class="far fa-calendar-alt"></i> 2021</span>
                                        <span><i class="far fa-clock"></i> 128 min</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group w-100">
                                @if (session('error'))
                                    <div class="alert alert-danger mt-4">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <h3 class="text-light mb-3">
                                </h3> 
                                <form action="{{ route('stream.find') }}" method="POST" class="newsletter-form">
                                    @csrf
                                    <div class="input-group mw-500">
                                        <input type="text" class="" name="stream_token"
                                            placeholder="Enter Token or Phone Number" aria-label="Stream token"
                                            aria-describedby="button-addon2">
                                        <input type="hidden" name="event_id" value="{{$event->id}}">
                                        <button class="btn" type="submit" id="button-addon2"><i
                                                class="fas fa-play"></i> Watch Now</button>
                                    </div>
                                </form>
                                        <p class="w-100 text-left text-light mt-2 mb-0">Already Bought? Enter Stream Token
                                            Or Phone Number
                                            To Watch. OR get <b>2GB</b>Bundles with
                                            {{$event->title}} Access, Dial <b>*544*46#ok.</b>
                                        </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- banner-area-end -->

    @endforeach
</div>


<!-- up-coming-movie-area -->
<section class="ucm-area ucm-bg" data-background="{{ asset('assets')}}/img/bg/ucm_bg.jpg">
    <div class="ucm-bg-shape" data-background="{{ asset('assets')}}/img/bg/ucm_bg_shape.png"></div>
    <div class="container">
        <div class="row align-items-end mb-55">
            <div class="col-lg-6">
                <div class="section-title text-center text-lg-left">
                    <span class="sub-title">LIVE STREAMING</span>
                    <h2 class="title">Trending Streams</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- <div class="ucm-nav-wrap">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tvShow-tab" data-toggle="tab" href="#tvShow" role="tab" aria-controls="tvShow" aria-selected="true">TV Shows</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="movies-tab" data-toggle="tab" href="#movies" role="tab" aria-controls="movies" aria-selected="false">Movies</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="anime-tab" data-toggle="tab" href="#anime" role="tab" aria-controls="anime" aria-selected="false">Anime</a>
                            </li>
                        </ul>
                    </div> -->
            </div>
        </div>
        <div class="ucm-active owl-carousel">
            @foreach($streams as $stream)
                        @if ($stream->id == 7)
                            @continue
                        @endif

                        @php
                            $checkRate = $stream->eventRates()->where('status', true)->count();
                            $freeStream = $checkRate == 0;
                            $event = $stream->event;
                            $channel = $stream->channel;
                            $current_time = \Carbon\Carbon::now();
                        @endphp

                        <div class="movie-item mb-50">
                            <div class="movie-poster">
                                <a
                                    href="{{ url($freeStream ? "/stream/free/{$stream->id}/{$stream->slug}" : "/stream/{$stream->id}/{$stream->slug}") }}">
                                    <img src="{{$stream->thumbnail_url}}" class="w-100 d-block aspect16" alt="{{ $stream->title }}">
                                </a>
                            </div>

                            <a
                                href="{{ url($freeStream ? "/stream/free/{$stream->id}/{$stream->slug}" : "/stream/{$stream->id}/{$stream->slug}") }}">
                                <div class="play fs-40">
                                    <i class="fadeIn animated bx bx-play-circle"></i>
                                </div>
                            </a>

                            <div class="movie-content">
                                <div class="top">
                                    <h5 class="title">
                                        <a href="{{ url("/stream/free/{$stream->id}/{$stream->slug}") }}">
                                            {{ $stream->title }}
                                        </a>
                                    </h5>

                                    <span class="date">
                                        @if($event->start_time > $current_time)
                                            <small class="text-muted">Starts in
                                                {{ $event->start_time->diffForHumans() }}
                                            </small>
                                        @elseif($event->end_time > $current_time)
                                            <small class="text-muted">Started
                                                {{ $event->start_time->diffForHumans() }}
                                            </small>
                                        @else
                                            <small class="text-muted">Ended</small>
                                        @endif
                                    </span>
                                </div>

                                <div class="bottom px-0">
                                    <ul>
                                        <li><span class="quality">hd</span></li>
                                        <li>
                                            <span class="duration">
                                                <i class="far fa-clock"></i>
                                                <small class="text-muted mb-0 mt-1">
                                                    {{ $channel ? $channel->name : 'Unknown' }}
                                                </small>
                                            </span>
                                            <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
            @endforeach

        </div>
    </div>
</section>
<!-- up-coming-movie-area-end -->


<!-- top-rated-movie -->
<section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
    <div class="container">
        <div class="row align-items-end mb-30">
            <div class="col-lg-6">
                <div class="section-title text-center text-lg-left">
                    <span class="sub-title">TOP VIDEOS</span>
                    <h2 class="title">Trending Videos</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- <div class="ucm-nav-wrap">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tvShow-tab" data-toggle="tab" href="#tvShow" role="tab" aria-controls="tvShow" aria-selected="true">TV Shows</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="movies-tab" data-toggle="tab" href="#movies" role="tab" aria-controls="movies" aria-selected="false">Movies</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="anime-tab" data-toggle="tab" href="#anime" role="tab" aria-controls="anime" aria-selected="false">Anime</a>
                            </li>
                        </ul>
                    </div> -->
            </div>
        </div>
        <div class="row tr-movie-active">

            @php use App\Models\Channel; @endphp
            @foreach($top_videos as $video) 
            <div class="col-xl-4 col-lg-4 col-sm-6 grid-item grid-sizer">
                    <div class="movie-item mb-60">
                        <div class="movie-poster">
                            <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                                <img src="{{$video->thumbnail ?? asset('frontend-assets/images/default.png')}}"
                                    class="w-100 d-block w-100" alt="...">
                                <div class="play fs-40">
                                    <i class="fadeIn animated bx bx-play-circle"></i>
                                </div>
                            </a>
                        </div>
                        <div class="movie-content">
                            <div class="top">
                            @php
                            $channel = Channel::find($video->channel_id);
                            @endphp
                                <h5 class="title mt-0">
                                    <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                                        {{ucfirst($video->title)}}
                                    </a>
                                </h5> 
                            </div>
                            <div class="bottom">
                                <!-- Display number of views -->

                                <ul>
                                    <li><span class="quality">hd</span></li>
                                    <li>
                                        <span class="channel"><i class="far fa-user"></i>
                                            {{ $channel ? $channel->name : 'Unknown' }}</span>
                                        <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                        <span class="views ml-2">
                                            <i class="fas fa-eye"></i> {{ $video->views ?? 0 }} views
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach 
        </div>
    </div>
</section>
<!-- top-rated-movie-end -->
@endsection
@section('header')
<style>
    .card.bg-dark {
        object-position: top;
    }

    .card-img-overlay {
        padding: 2rem 0 3rem;
        z-index: 9;

        background: linear-gradient(180deg, transparent, #00000014, #0000007a, #000000b3, #000000cc);
    }

    a,
    button {
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: none;
        outline: none;
        background: none;
    }

    .bottom {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: linear-gradient(1deg, #0b0908, #231c1900);
        padding-left: 19px;
        left: 0;
        padding: 13px;
        color: #69df04;
    }

    html.dark-theme .form-control {
        background-color: #3b3b3bc2;
        border: 1px solid rgb(55 52 51) !important;
    }

    .section {
        margin: 0 auto;
        padding: 5rem 0 2rem;
    }

    .relative {
        position: relative;
    }

    .black-bg {
        background: linear-gradient(90deg, #177373, #30c5ca, #30c5ca, #30c5ca);
        padding: 0;
    }

    .banner-column {
        background: linear-gradient(90deg, #0000001c, #00000000, #00000000, #00000000, #00000000);
        padding: 0;
    }

    html.dark-theme .form-control {
        background-color: #3b3b3bc2;
        border: 1px solid rgb(0 0 0 / 34%);
    }

    .paragraph {
        font-family: inherit;
        text-wrap: balance;
        color: white;
    }

    .heading-xl {
        font-family: inherit;
        font-size: clamp(2.648rem, 6vw, 3.241rem);
        font-weight: 600;
        line-height: 1.15;
        letter-spacing: -1px;
        color: white;
        text-shadow: 3px 3px 12px #1f09067d;
    }

    .heading-lg {
        font-family: inherit;
        font-size: clamp(2.179rem, 5vw, 3.176rem);
        font-weight: 600;
        line-height: 1.15;
        letter-spacing: -1px;
        color: white;
    }

    .heading-md {
        font-family: inherit;
        font-size: clamp(1.794rem, 4vw, 2.379rem);
        font-weight: 600;
        line-height: 1.25;
        letter-spacing: -1px;
        color: white;
    }

    .btn {
        display: inline-block;
        font-family: inherit;
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1.5;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        outline: none;
        border: none;
        border-radius: 0.25rem;
        text-transform: unset;
        transition: all 0.3s ease-in-out;
    }

    .btn-inline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        -moz-column-gap: 0.5rem;
        column-gap: 0.5rem;
    }

    .btn-darken {
        padding: 0.75rem 2rem;
        color: var(--color-white-100);
        background-color: var(--color-black-200);
        box-shadow: var(--shadow-medium);
    }

    .darkmode .btn-darken {
        background-color: var(--color-blue-500);
    }

    .header {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        width: 100%;
        height: auto;
        margin: 0 auto;
        background-color: var(--color-white-100);
        box-shadow: var(--shadow-medium);
    }

    .darkmode .header {
        background-color: var(--color-black-400);
    }

    .navbar {
        display: flex;
        flex-direction: row;
        align-items: center;
        align-content: center;
        justify-content: space-between;
        width: 100%;
        height: 4rem;
        margin: 0 auto;
    }

    .brand {
        font-family: inherit;
        font-size: 1.6rem;
        font-weight: 600;
        line-height: 1.25;
        margin-right: auto;
        letter-spacing: -1px;
        text-transform: uppercase;
        color: var(--color-blue-500);
    }

    @media only screen and (max-width: 992px) {
        .heading-xl {
            font-size: 25px;
            margin-bottom: 0;
        }

        .btn-darken {
            padding: 0 7px;
        }

        .banner-section,
        .banner-column {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .banner-column input {
            height: 40px;
        }

        .banner-column p {
            font-size: 14px;
            font-weight: 400;
        }

        .mw-500 {
            max-width: 327px;
        }
    }

    .banner-section {
        background-size: cover;
        background-position: center;
        background-image: url({{asset('/landing-assets/images/somalibg2.png')}}) !important;
    }

    .burger {
        position: relative;
        display: block;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        order: -1;
        z-index: 12;
        width: 1.6rem;
        height: 1.15rem;
        margin-right: 1.25rem;
        border: none;
        outline: none;
        background: none;
        visibility: visible;
        transform: rotate(0deg);
        transition: 0.35s ease;
    }

    @media only screen and (min-width: 48rem) {
        .burger {
            display: none;
            visibility: hidden;
        }
    }

    .burger-line {
        position: absolute;
        display: block;
        left: 0;
        width: 100%;
        height: 2px;
        border: none;
        outline: none;
        opacity: 1;
        border-radius: 1rem;
        transform: rotate(0deg);
        background-color: var(--color-black-300);
        transition: 0.25s ease-in-out;
    }

    .darkmode .burger-line {
        background-color: var(--color-white-100);
    }

    .burger-line:nth-child(1) {
        top: 0px;
    }

    .burger-line:nth-child(2) {
        top: 0.5rem;
        width: 70%;
    }

    .burger-line:nth-child(3) {
        top: 1rem;
    }

    .burger.is-active .burger-line:nth-child(1) {
        top: 0.5rem;
        transform: rotate(135deg);
    }

    .burger.is-active .burger-line:nth-child(2) {
        opacity: 0;
        visibility: hidden;
    }

    .burger.is-active .burger-line:nth-child(3) {
        top: 0.5rem;
        transform: rotate(-135deg);
    }

    .switch {
        position: relative;
        display: block;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        z-index: 9;
        margin-left: 5rem;
        margin-right: 0.5rem;
    }

    .switch-light,
    .switch-dark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform-origin: center;
        transform: translate(-50%, -50%);
        transition: all 0.3s ease-in;
    }

    .banner-column {
        position: relative;
        display: grid;
        align-items: center;
        row-gap: 2rem;
    }

    @media only screen and (min-width: 48rem) {
        .banner-column {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            justify-content: center;
            margin-top: 3rem;
        }
    }

    @media only screen and (min-width: 64rem) {
        .banner-column {
            grid-template-columns: 1fr -webkit-max-content;
            grid-template-columns: 1fr max-content;
            -moz-column-gap: 2rem;
            column-gap: 2rem;
        }
    }

    .banner-image {
        display: block;
        max-width: 45rem;
        height: auto;
        -o-object-fit: cover;
        object-fit: cover;
        justify-self: center;
    }

    @media only screen and (min-width: 48rem) {
        .banner-image {
            order: 1;
            max-width: 45rem;
            height: auto;
        }
    }

    @media only screen and (min-width: 64rem) {
        .banner-image {
            max-width: 38rem;
            height: auto;
        }
    }

    .banner-inner {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        row-gap: 1.5rem;
    }

    @media only screen and (max-width: 1024px) {
        .heading-xl {
            font-size: 35px;
            margin-bottom: 0;
        }

        .btn-darken {
            padding: 0 7px;
        }

        .banner-image {
            max-width: 36rem;
            height: auto;
        }

        .banner-column input {
            height: 40px;
        }

        .banner-column p {
            font-size: 14px;
            font-weight: 400;
        }
    }

    @media screen and (max-width: 1244px) {
        .heading-xl {
            font-size: 33px;
            margin-bottom: 0;
        }

        .btn-darken {
            padding: 0 7px;
        }

        .banner-image {
            max-width: 36rem;
            height: auto;
        }

        .banner-column input {
            height: 40px;
        }

        .banner-column p {
            font-size: 14px;
            font-weight: 400;
        }
    }

    @media screen and (max-width: 768px) {
        .mw-500 {
            max-width: 500px;
        }
    }



    @media (min-width: 768px) {
        .card-img-overlay form {
            max-width: 79%;
            margin: auto;
            background: #a4a4a44a;
            padding: 30px;
            border-radius: 24px;
            border: 1px solid #6e6e6e;
        }

        .card-img-overlay form .input-group {
            max-width: 56%;
            margin: auto;
        }

        html.dark-theme .form-control {
            background-color: #00000038;
            border: 1px solid rgb(0 0 0 / 34%);
            color: red;
        }
    }

    @media (min-width: 768px) and (max-width: 992px) {
        .card.bg-dark img {
            min-height: 430px;
            object-fit: cover;
        }

        .card-img-overlay form {
            max-width: 91%;
            padding: 25px;
            border-radius: 20px;
        }

        .card-img-overlay form .input-group {
            max-width: 75%;
        }

        .heading-xl {
            font-size: 46px;
            margin-bottom: 0;
        }

        .card-img-overlay form {
            max-width: 91%;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px !important;
        }
    }

    .btn-darken {
        background: black;
        color: white;
    }
</style>

@endsection
@section('footer')
<script>
    const navbarMenu = document.getElementById("menu");
    const burgerMenu = document.getElementById("burger");
    const headerMenu = document.getElementById("header");
    const overlayMenu = document.querySelector(".overlay");

    // Open Close Navbar Menu on Click Burger
    if (burgerMenu && navbarMenu) {
        burgerMenu.addEventListener("click", () => {
            burgerMenu.classList.toggle("is-active");
            navbarMenu.classList.toggle("is-active");
        });
    }

    // Close Navbar Menu on Click Links
    document.querySelectorAll(".menu-link").forEach((link) => {
        link.addEventListener("click", () => {
            burgerMenu.classList.remove("is-active");
            navbarMenu.classList.remove("is-active");
        });
    });

    // Fixed Navbar Menu on Window Resize
    window.addEventListener("resize", () => {
        if (window.innerWidth >= 992) {
            if (navbarMenu.classList.contains("is-active")) {
                navbarMenu.classList.remove("is-active");
                overlayMenu.classList.remove("is-active");
            }
        }
    });

    // Dark and Light Mode on Switch Click
    document.addEventListener("DOMContentLoaded", () => {
        const darkSwitch = document.getElementById("switch");

        darkSwitch.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");
            document.body.classList.toggle("darkmode");
        });
    });

</script>
@endsection