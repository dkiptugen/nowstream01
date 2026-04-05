@extends('Frontend.includes.landing_layout') 
@php use Carbon\Carbon; @endphp
@section('content')
<style>
    .music-fest {
        font-family: Arial, sans-serif;
        font-size: 30px;
        font-weight: bold;
        background: linear-gradient(to right, #FF5F6D, #FFC371);
        -webkit-background-clip: text;
        color: transparent !important;
        position: relative;
        display: inline-block;
    }

    .music-fest::before {
        content: '#MusicFest';
        position: absolute;
        top: 0;
        left: 0;
        background: linear-gradient(to right, #000000, #000000);
        -webkit-background-clip: text;
        color: transparent !important;
        z-index: -1;
        filter: blur(5px);
    }

    .music-fest::after {
        content: '#MusicFest';
        position: absolute;
        top: 0;
        left: 0;
        background: linear-gradient(to right, #000000, #000000);
        -webkit-background-clip: text;
        color: transparent !important;
        z-index: -2;
        filter: blur(10px);
    }

    .music-fest {
        color: white !important;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.675rem 0.75rem;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.5;
        color: #000000;
    }

    @media (max-width: 575.98px) {
        .extra-huge-text {
            font-size: 34px;
            line-height: 45px;
        }

        .navbar .btn-primary {
            padding: 6px;
            font-size: 13px;
        }
    }

    @media (max-width: 767.98px) {
        .hero-wrapper.box-hero {
            max-width: 100%;
        }

        .event-info .btn {
            border-radius: 7px !important;
        }

        .music-fest {
            font-size: 24px !important;
            color: white;
        }

        .event-info {
            margin-top: 26px;
        }

        .hero-1 .hero-wrapper {
            max-width: 100%;
            padding: 50px 0 40px 0;
        }
    }

    @media (max-width: 1200px) {
        .dt-block {
            display: inline !important;
        }

        .extra-huge-text {
            font-size: 50px;
            line-height: 50px;
        }
    }

    @media screen and (min-width: 366px) and (max-width: 429px) {
        .event-info {
            max-width: 365px;
        }

        .pt-sm-2 {
            padding-top: 0.125rem !important;
        }

        .btn-primary {
            padding: 8px;
        }
    }

    .hero-1 .hero-wrapper {
        position: relative;
        padding: 150px 0 10px 10px;
        overflow: hidden;
        border-radius: 35px;
    }

    #bg-video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -1;
        transform: translate(-50%, -50%);
    }

    .highlight-section .swiper-slide h2 {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: linear-gradient(2deg, black, transparent);
        padding: 10px;
    }

    #bg-video {
        width: 100%;
    }

    @media (max-width: 430px) {
        .extra-huge-text {
            font-size: 32px;
            line-height: 37px;
        }

        .event-info .event-inner h2 {
            font-weight: 600;
            font-size: 17px;
            line-height: 19px;
        }

        .hero-1 .hero-wrapper {
            position: relative;
            padding: 60px 0 17px 0px;
            overflow: hidden;
            border-radius: 35px;
        }
    }
</style>
<!--Hero Section ======================-->
<section class="hero-section hero-1">
    <div class="container-fluid">
        <div class="hero-wrapper box-hero mx-auto position-relative parallax px-3">
            <video autoplay muted loop id="bg-video">
                <source src="{{asset('landing-assets/video/promo.mp4')}}" type="video/mp4">
            </video>
            <div class="container">
                <div class="position-relative">
                    <div class="music-fest">Somali Nite Live. </div>
                    <h1 class="text-gradient extra-huge-text text-uppercase fst-italic fw-extra-bold mb-0">A spectacular
                        <br> Virtual Experience!!!

                    </h1>

                    <p class="text-white h6 fw-semibold mb-0 mt-2">3rd August 2024 | Starts: 8:00PM</p>
                    <!-- <h6 class="mt-3"><b>NB:</b> Dial <b>*544*XX#Ok</b> On your Safaricom Line to buy Access with <b>5GB</b> Bundles.</h6> -->
                </div>

                <div class="event-info custom-inner-bg">
                    <div class="event-inner">
                        <div class="row align-items-center justify-content-between gy-xl-0 gy-4">
                            {{-- <div class="col-md-3"> --}}
                                {{-- <div class="event-content"> --}}
                                    {{-- <h2>3rd August 2024</h2> --}}
                                    {{-- <h4>Starting 8PM</h4> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}
                            <div class="col-md-5">
                                <div class="event-content">
                                    <h2>Please Enter Your Mobile Number or Token to Watch.</h2>
                                </div>
                            </div>
                            <div class="col-md-7 mt-0">
                                {{-- <h4 class="mb-2">Enter Mobile Number or Token to Watch.</h4> --}}
                                @if (session('error'))
                                    <div class="alert alert-danger mt-4">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form action="{{ route('stream.find') }}" method="POST"
                                    class="w-100 d-flex flex-column flex-md-row align-items-stretch">
                                    @csrf
                                    <div class="input-group w-100">
                                        <input type="text" class="form-control w-100 rounded-end-0" name="stream_token"
                                            placeholder="Kindly Enter Token or Phone Number" aria-label="Stream token"
                                            aria-describedby="button-addon2">
                                        <input type="hidden" name="event_id" value="{{$current_event->id}}">
                                    </div>
                                    <button type="submit"
                                        class="btn btn-primary text-center align-items-center rounded-end rounded-start-0"
                                        aria-label="buttons">
                                        Watch Now</button>
                            </div>
                        </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom text-center text-warning pt-3">Buy Somali Nite Live Access <a class="btn btn-link p-0"
                style="color: yellow" href="{{ url("/event/{$current_event->id}/{$current_event->slug}") }}">Here</a> OR
            Get <b>5GB</b> Bundles Plus Somali Nite Live Access, Dial <b>*544*46#OK</b> </div>
</section>
<!--Hero Section ======================-->

<!--Countdown Section ======================-->
<div class="countdown-section pt-30 pt-lg-30 pt-xxl-30 position-relative pt-sm-2">
    <div class="container">
        <!--CountDown Section ======================-->
        <div class="countdown">
            <div class="row row-cols-2 row-cols-lg-3 row-cols-xl-4 justify-content-between align-items-center">
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number primary-text-shadow" id="days">00</span>
                        <span class="countdown-label text-opacity">Days</span>
                    </div>
                </div>
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number primary-text-shadow" id="hours">00</span>
                        <span class="countdown-label text-opacity">Hours</span>
                    </div>
                </div>
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number primary-text-shadow" id="minutes">00</span>
                        <span class="countdown-label text-opacity">Minutes</span>
                    </div>
                </div>
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number primary-text-shadow" id="seconds">00</span>
                        <span class="countdown-label text-opacity">Seconds</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Countdown Section ======================-->



<!--About Section ======================-->
<section class="about-section about-1 pb-50 pb-lg-80 pt-50 pb-xxl-100">
    <div class="container">
        <div class="section-title section-title-style-2 mb-4 mb-lg-30 mb-xxl-40">
            <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                    class="straight-line"></span>About The Event</span>
            <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                <span class="text-opacity mb-n2">Somali</span>
                <span class="sub-title fw-extra-bold primary-text-shadow">Nite</span>
            </h2>
        </div>
        <div class="row gy-50 gy-lg-0 gx-80 justify-content-lg-between">
            <div class="col-lg-5">
                <div>

                    <!-- section-title -->
                    <p class="custom-roboto mb-4 mb-lg-30">
                        Somali Nite is a comedy extravaganza featuring Africa’s top comedians, artists, musicians
                        and influencers from Somalia and Kenya.
                        It is an entertainment event to showcase and celebrate the diverse culture and talent
                        among the Somali community and appreciate the strings that bind them together ranging
                        from food to music, dance to fashion and their endearing style and a sense of belonging.
                        Somali Nite Live Access gives you a virtual experience to the event at the comfort of your own
                        home.

                    </p>

                    <p class="custom-roboto mb-0">
                        <b>Highlights</b>
                        <b>Music</b>: Live performances by Suldan Seer Somali's most wanted contemporary music artist.
                        <br>
                        <b>Comedy</b>: Catch Nasra as she cracks up the audience with her wit and humor. It’s gonna be a
                        joyful Nite! <br>
                        <b>Food</b>: Indulge in authentic delicious flavors of Somali cuisine. <br>
                        <b>Fashion</b>: Catch the dazzling showcase of Somali fashion, featuring traditional attire and
                        modern interpretations, highlighting the diversity and elegance of Somali clothing.
                        Friendships and Networking Opportunities
                    </p>
                    <a href="{{ url("/event/{$current_event->id}/{$current_event->slug}") }}"
                        class="btn btn-gradient d-inline-flex align-items-center gap-2 mt-4 mt-lg-30 mt-xxl-40"
                        aria-label="buttons"><span class="buttons-logo"><svg width="25" height="25">
                                <use xlink:href="#buttons-logo"></use>
                            </svg></span>Get Ticket</a>
                </div>
            </div>
            <!-- col-5 -->
            <div class="col-lg-6">
                <div class="about-content-wrapper position-relative">
                    <div class="about-image-1 position-relative">
                        <div class="about-image-wrapper">
                            <img src="{{ asset('landing-assets/images/float.png')}}" class="img-fluid" alt="img">
                        </div>
                        <a href="{{asset('landing-assets/video/promo.mp4')}}"
                            class="video-popup video-popup-center position-absolute video-popup-link">
                            <div class="circle-wrapper">
                                <div class="circle-bg"></div>
                                <span class="inner-circle video-icon">
                                    <span class=""><svg width="30" height="30">
                                            <use xlink:href="#video-icon"></use>
                                        </svg></span>
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="about-image-2">
                    </div>
                    <div class="ellipse-image-1">
                        <img src="{{ asset('landing-assets/images')}}/home-1/ellipse-1.png" class="img-fluid" alt="img">
                    </div>
                </div>
                <!-- about-content-wrapper -->
            </div>
        </div>
    </div>
</section>
<!--About Section ======================-->


<!--LineUp Section ======================-->
<section id="line-up" class="lineup-section lineup-1 pt-60 mb-20 mb-lg-30 mb-xxl-40">
    <div class="lineup-contents bg-lg custom-inner-bg py-30 py-lg-50 position-relative">
        <div class="container">
            <div class="row gx-60 gx-xxl-80 gy-30">
                <div class="col-lg-7">
                    <div class="swiper-custom-progress position-relative">
                        <div class="swiper lineup-swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="lineup-image-wrapper position-relative">
                                        <div class="lineup-image">
                                            <img src="{{ asset('landing-assets/images/nasra.jpeg')}}" class="img-fluid"
                                                alt="lineup-image">
                                        </div>
                                        <div class="lineup-image-hover">
                                            <p class="author-name">Nasra Comedian</p>
                                            <div class="line-up-hover-content">
                                                <h5 class="fw-medium mb-20">Genere : <span
                                                        class="text-uppercase">Comedian</span></h5>
                                                <ul
                                                    class="list-unstyled line-up-icons d-flex align-items-center gap-3 gap-lg-20 mb-0">
                                                    <li><a href="#" class="facebook-icon"><svg width="20" height="20">
                                                                <use xlink:href="#facebook-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="instagram-icon"><svg width="20" height="20">
                                                                <use xlink:href="#instagram-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="youtube-icon"><svg width="20" height="20">
                                                                <use xlink:href="#youtube-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="spotify-icon"><svg width="20" height="20">
                                                                <use xlink:href="#spotify-icon"></use>
                                                            </svg></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- lineup-image-hover -->
                                    </div>
                                    <!-- lineup-image-wrapper -->
                                </div>
                                <!-- swiper-slide-->
                                <div class="swiper-slide">
                                    <div class="lineup-image-wrapper position-relative">
                                        <div class="lineup-image">
                                            <img src="{{ asset('landing-assets/images/suldaan.jpeg')}}"
                                                class="img-fluid" alt="lineup-image">
                                        </div>
                                        <div class="lineup-image-hover">
                                            <p class="author-name">Suldaan Seerar</p>
                                            <div class="line-up-hover-content">
                                                <h5 class="fw-medium mb-20">Genere : <span
                                                        class="text-uppercase">Comedian</span></h5>
                                                <ul
                                                    class="list-unstyled line-up-icons d-flex align-items-center gap-3 gap-lg-20 mb-0">
                                                    <li><a href="#" class="facebook-icon"><svg width="20" height="20">
                                                                <use xlink:href="#facebook-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="instagram-icon"><svg width="20" height="20">
                                                                <use xlink:href="#instagram-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="youtube-icon"><svg width="20" height="20">
                                                                <use xlink:href="#youtube-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="spotify-icon"><svg width="20" height="20">
                                                                <use xlink:href="#spotify-icon"></use>
                                                            </svg></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- lineup-image-hover -->
                                    </div>
                                    <!-- lineup-image-wrapper -->
                                </div>
                                <!-- swiper-slide-->
                                <div class="swiper-slide">
                                    <div class="lineup-image-wrapper position-relative">
                                        <div class="lineup-image">
                                            <img src="{{ asset('landing-assets/images/subeer.jpeg')}}" class="img-fluid"
                                                alt="lineup-image">
                                        </div>
                                        <div class="lineup-image-hover">
                                            <p class="author-name">DJ Subeer</p>
                                            <div class="line-up-hover-content">
                                                <h5 class="fw-medium mb-20">Genere : <span
                                                        class="text-uppercase">DJ</span></h5>
                                                <ul
                                                    class="list-unstyled line-up-icons d-flex align-items-center gap-3 gap-lg-20 mb-0">
                                                    <li><a href="#" class="facebook-icon"><svg width="20" height="20">
                                                                <use xlink:href="#facebook-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="instagram-icon"><svg width="20" height="20">
                                                                <use xlink:href="#instagram-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="youtube-icon"><svg width="20" height="20">
                                                                <use xlink:href="#youtube-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="spotify-icon"><svg width="20" height="20">
                                                                <use xlink:href="#spotify-icon"></use>
                                                            </svg></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- lineup-image-hover -->
                                    </div>
                                    <!-- lineup-image-wrapper -->
                                </div>
                                <!-- swiper-slide-->
                                <div class="swiper-slide">
                                    <div class="lineup-image-wrapper position-relative">
                                        <div class="lineup-image">
                                            <img src="{{ asset('landing-assets/images/chur.png')}}" class="img-fluid"
                                                alt="lineup-image">
                                        </div>
                                        <div class="lineup-image-hover">
                                            <p class="author-name">Churchill</p>
                                            <div class="line-up-hover-content">
                                                <h5 class="fw-medium mb-20">Genere : <span
                                                        class="text-uppercase">Comedian</span></h5>
                                                <ul
                                                    class="list-unstyled line-up-icons d-flex align-items-center gap-3 gap-lg-20 mb-0">
                                                    <li><a href="#" class="facebook-icon"><svg width="20" height="20">
                                                                <use xlink:href="#facebook-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="instagram-icon"><svg width="20" height="20">
                                                                <use xlink:href="#instagram-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="youtube-icon"><svg width="20" height="20">
                                                                <use xlink:href="#youtube-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="spotify-icon"><svg width="20" height="20">
                                                                <use xlink:href="#spotify-icon"></use>
                                                            </svg></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- lineup-image-hover -->
                                    </div>
                                    <!-- lineup-image-wrapper -->
                                </div>
                                <!-- swiper-slide-->
                                <div class="swiper-slide">
                                    <div class="lineup-image-wrapper position-relative">
                                        <div class="lineup-image">
                                            <img src="{{ asset('landing-assets/images/hassan.jpeg')}}" class="img-fluid"
                                                alt="lineup-image">
                                        </div>
                                        <div class="lineup-image-hover">
                                            <p class="author-name">Hassan Gantaal</p>
                                            <div class="line-up-hover-content">
                                                <h5 class="fw-medium mb-20">Genere : <span
                                                        class="text-uppercase">Performing Artist</span></h5>
                                                <ul
                                                    class="list-unstyled line-up-icons d-flex align-items-center gap-3 gap-lg-20 mb-0">
                                                    <li><a href="#" class="facebook-icon"><svg width="20" height="20">
                                                                <use xlink:href="#facebook-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="instagram-icon"><svg width="20" height="20">
                                                                <use xlink:href="#instagram-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="youtube-icon"><svg width="20" height="20">
                                                                <use xlink:href="#youtube-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="spotify-icon"><svg width="20" height="20">
                                                                <use xlink:href="#spotify-icon"></use>
                                                            </svg></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- lineup-image-hover -->
                                    </div>
                                    <!-- lineup-image-wrapper -->
                                </div>
                                <!-- swiper-slide-->
                                <div class="swiper-slide">
                                    <div class="lineup-image-wrapper position-relative">
                                        <div class="lineup-image">
                                            <img src="{{ asset('landing-assets/images/abdi1.jpeg')}}" class="img-fluid"
                                                alt="lineup-image">
                                        </div>
                                        <div class="lineup-image-hover">
                                            <p class="author-name"> Adan Abdi</p>
                                            <div class="line-up-hover-content">
                                                <h5 class="fw-medium mb-20">Genere : <span
                                                        class="text-uppercase">Comedian</span></h5>
                                                <ul
                                                    class="list-unstyled line-up-icons d-flex align-items-center gap-3 gap-lg-20 mb-0">
                                                    <li><a href="#" class="facebook-icon"><svg width="20" height="20">
                                                                <use xlink:href="#facebook-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="instagram-icon"><svg width="20" height="20">
                                                                <use xlink:href="#instagram-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="youtube-icon"><svg width="20" height="20">
                                                                <use xlink:href="#youtube-icon"></use>
                                                            </svg></a></li>
                                                    <li><a href="#" class="spotify-icon"><svg width="20" height="20">
                                                                <use xlink:href="#spotify-icon"></use>
                                                            </svg></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- lineup-image-hover -->
                                    </div>
                                    <!-- lineup-image-wrapper -->
                                </div>
                                <!-- swiper-slide-->
                            </div>
                            <!-- swiper-wrapper -->
                        </div>
                        <!-- swiper -->
                        <div class="lineup-swiper-pagination"></div>

                        <div class="swiper-button-progress">
                            <div class="swiper-button-next">
                                <span class="chevron-right-icon"><svg width="12" height="14">
                                        <use xlink:href="#chevron-right-icon"></use>
                                    </svg></span>
                            </div>
                            <div class="swiper-button-prev">
                                <span class="chevron-left-icon"><svg width="12" height="14">
                                        <use xlink:href="#chevron-left-icon"></use>
                                    </svg></span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- col-7 -->
                <div class="col-lg-5">
                    <div class="lineup-right-content mt-60 mt-lg-0">
                        <div class="section-title section-title-style-2 mb-4 mb-lg-30 mb-xxl-40">
                            <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                                    class="straight-line"></span>Line-Up</span>
                            <h2 class="title display-3 fw-extra-bold mb-n2 text-opacity">All The Big</h2>
                            <h3 class="sub-title display-3 fw-extra-bold primary-text-shadow"> Shots Will Be Here</h3>
                        </div>
                        <!-- section-title -->
                        <p class="mb-4 mb-lg-30">
                            Suldaan Seerar, DJ Subeer, Nasra, Hassan Gantaal, Abdi Adan, Dancers, Fashion Models, and
                            more.
                        </p>

                        <div class="mt-20 mt-lg-0">
                            <a href="#" class="download-link d-flex align-items-center justify-content-lg-end gap-30"
                                aria-label="buttons">See More<span class="ticket-arrow arrow-up-right"><svg width="32"
                                        height="32">
                                        <use xlink:href="#arrow-up-right"></use>
                                    </svg></span></a>
                        </div>
                    </div>
                    <!-- lineup-right-content -->
                </div>
                <!-- col-5 -->
            </div>
            <!-- row -->
        </div>
        <!-- container -->
        <div class="ellipse-image-2">
            <img src="{{ asset('landing-assets/images')}}/home-1/ellipse-2.png" class="img-fluid" alt="img">
        </div>
    </div>
    <!-- lineup-contents -->
</section>
<!--LineUp Section ======================-->


<!--Scroll Section ======================-->
<div class="scroll-section py-30 position-relative d-none">
    <div class="marquee-elements">
        <div class="scroll-elements">
            <span class="scroll-items js-elements">
                <span class="scroll-light-text fs-180 fw-extra-bold text-uppercase d-flex gap-10 mb-0"><span>Somali
                    </span>
                    <span class="mx-10 mx-lg-4">.</span><span
                        class="primary-text-shadow me-30 me-lg-50">experience</span> <span
                        class="mx-10 mx-lg-4">.</span></span>
            </span>
        </div>
    </div>
</div>
<!--Scroll Section ======================-->


<!--Highlights Section ======================-->
<section class="highlight-section highlight-1 py-50 py-lg-100 py-xxl-120 mt-20 mt-lg-40">
    <div class="container position-relative">
        <div class="ellipse-image-1">
            <img src="{{ asset('landing-assets/images')}}/home-1/ellipse-1.png" alt="ellipse-1">
        </div>
        <div class="row gy-4 gy-lg-0 align-items-lg-end justify-content-lg-between mb-30 mb-lg-70">
            <div class="col-lg-5">
                <div class="section-title section-title-style-2">
                    <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                            class="straight-line"></span>Highlights</span>
                    <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                        <span class="mb-n2 text-opacity">Comedy</span>
                        <span class="sub-title fw-extra-bold primary-text-shadow">Extravaganza</span>
                    </h2>
                </div>
                <!-- section-title -->
            </div>
            <div class="col-lg-5">
                <div class="highlights-text">
                    <p class="custom-roboto text-lg-end">

                        Catch Nasra crack up the world with her
                        <br> wit and humour.

                    </p>
                </div>
            </div>
        </div>
        <!-- row -->

        <div class="swiper highlight-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div
                        class="highlights-item position-relative d-flex flex-column gap-20 px-30 py-40 px-xl-40 py-xl-60 active">
                        <div class="highlights-icon">
                            <img src="{{ asset('landing-assets/images/stage.png')}}" class="w-100" alt="img">
                        </div>
                        <h2 class="fw-extra-bold mb-0">Main Stage Extravaganza</h2>
                    </div>
                </div>
                <!-- swiper-slide-->
                <div class="swiper-slide">
                    <div
                        class="highlights-item position-relative  d-flex flex-column gap-20 px-30 py-40 px-xl-40 py-xl-60">
                        <div class="highlights-icon">
                            <img src="{{ asset('landing-assets/images/lighting.png')}}" class="w-100" alt="img">
                        </div>
                        <h2 class="fw-extra-bold mb-0">Immersive Sound and Lighting</h2>
                    </div>
                </div>
                <!-- swiper-slide-->
                <div class="swiper-slide">
                    <div
                        class="highlights-item position-relative  d-flex flex-column gap-20 px-30 py-40 px-xl-40 py-xl-60">
                        <div class="highlights-icon">
                            <img src="{{ asset('landing-assets/images/food.png')}}" class="w-100" alt="img">
                        </div>
                        <h2 class="fw-extra-bold mb-0">Exquisite Food & Drinks</h2>
                    </div>
                </div>
                <!-- swiper-slide-->
            </div>
            <!-- swiper-wrapper-->
        </div>
        <!-- swiper-->
    </div>
</section>
<!--Highlights Section ======================-->


<!--Sponsor Section ======================-->
<section id="sponsor" class="sponsor-section sponsor-2 position-relative pt-50 pt-lg-100 pt-xxl-120">

    <div class="container">

        <div class="row gy-4 gy-lg-0 justify-content-lg-between mb-60 mb-lg-100">
            <div class="col-lg-4">
                <div class="sponsors-type">

                    <h2 class="fw-extra-bold mb-0">Powered By</h2>
                </div>
            </div>
            <!-- col-3 -->
            <div class="col-lg-8">
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 g-20 g-lg-30 align-items-center">
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm">
                            <img src="{{ asset('landing-assets/images/saf.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm">
                            <img src="{{ asset('landing-assets/images/laugh.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm">
                            <img src="{{ asset('landing-assets/images/baze.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm">
                            <img src="{{ asset('landing-assets/images/angani.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm">
                            <img src="{{ asset('landing-assets/images/dpo.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="https://www.caydeesoft.com/" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm">
                            <img src="{{ asset('landing-assets/images/caydee.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- col-8 -->
        </div>
        <!-- row -->
    </div>
</section>
<!--Sponsor Section ======================-->

<!--Scroll Section ======================-->
<div class="scroll-section py-30 position-relative d-none">
    <div class="marquee-elements">
        <div class="scroll-elements">
            <span class="scroll-items js-elements">
                <span class="scroll-light-text fs-180 fw-extra-bold text-uppercase d-flex gap-10 mb-0"><span>Somali
                    </span>
                    <span class="mx-10 mx-lg-4">.</span><span
                        class="primary-text-shadow me-30 me-lg-50">experience</span> <span
                        class="mx-10 mx-lg-4">.</span></span>
            </span>
        </div>
    </div>
</div>
<!--Scroll Section ======================-->

<!-- Blog Section ====================== -->
<section class="blog-section blog-horizontal pt-3 pb-50 pb-lg-80 pb-xxl-100">
    <div class="container">
        <div class="row gy-4 gy-lg-0 align-items-lg-end justify-content-lg-between mb-30 mb-lg-70">
            <div class="col-lg-4">
                <div class="section-title section-title-style-2">

                    <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                        <span class="mb-n2 text-opacity">Other</span>
                        <span class="sub-title fw-extra-bold primary-text-shadow">Events</span>
                    </h2>
                </div>
                <!-- section-title -->
            </div>
            <div class="col-lg-5">
                <div class="highlights-text">
                    <p class="custom-roboto text-lg-end">
                        Somali Nite; A Fun-filled entertainment show featuring comedic performances from Somalia and
                        Kenya. <a href="{{ url("/event/{$current_event->id}/{$current_event->slug}") }}">Click to watch
                            Somali Nite.</a>


                    </p>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="blog-content-wrapper position-relative">
            <div class="ellipse-image-1">
                <img src="{{ asset('landing-assets/images')}}/home-1/ellipse-1.png" alt="ellipse-1">
            </div>
            <div class="swiper blog-swiper">
                <div class="swiper-wrapper">
                    @foreach ($events as $event)
                        <div class="swiper-slide">
                            <div class="blog-content">
                                <div class="blog-content-4 custom-inner-bg">
                                    <div class="row gx-20 gy-50 gy-lg-0 align-items-center justify-content-between">
                                        <div class="col-lg-6 order-lg-2">
                                            <div class="blog-image">
                                                <img src="{{ $event->event_image }}" class="img-fluid" alt="img"
                                                    style="aspect-ratio: 1/1.1; object-fit: cover; min-height: 288px;">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 order-lg-1">
                                            <div class="blog-left-content">
                                                <p>
                                                    <span class="calendar me-10">
                                                        <svg width="16" height="17">
                                                            <use xlink:href="#calendar"></use>
                                                        </svg>
                                                    </span>{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y') }}
                                                </p>
                                                <h2 class="blog-link fs-4 fw-bold">
                                                    <a class="text-decoration-none"
                                                        href="{{ url("/event/{$event->id}/{$event->slug}") }}"
                                                        aria-label="blog-links">
                                                        {{ $event->event_name }}
                                                    </a>
                                                </h2>
                                                <!-- <p>{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p> -->
                                                <div>
                                                    <a href="{{ url("/event/{$event->id}/{$event->slug}") }}"
                                                        class="download-link d-flex align-items-center gap-30"
                                                        aria-label="buttons">
                                                        Read more
                                                        <span class="ticket-arrow arrow-up-right">
                                                            <svg width="32" height="32">
                                                                <use xlink:href="#arrow-up-right"></use>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- left-content -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- blog-content -->
                        </div>
                        <!-- swiper-slide-->
                    @endforeach
                </div>
                <!-- swiper-wrapper -->
            </div>
            <!-- swiper -->
        </div>
        <!-- blog-content-wrapper -->
    </div>
</section>
@endsection
