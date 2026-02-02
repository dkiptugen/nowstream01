@extends('Frontend.includes.israel.landing_layout') 
@php use Carbon\Carbon; @endphp
@section('content')
<style>
    .hero-3 h5 {
        font-size: 3rem;
        font-family: cursive;
        font-weight: 600;
    }

    .merchandise-wrapper img {
        height: auto;
        width: 100%;
        object-fit: contain;
    }

    .extra-huge-text-1 {
        font-size: 100px;
        line-height: 105px;
        white-space: nowrap;
    }

    .lineup-image-wrapper img {
        border-radius: 30px !important;
        display: block;
        width: 100%;
        height: 300px;
        object-fit: cover;
        object-position: top;
    }

    #player {
        width: 100%;
    }

    .hero-3 .hero-wrapper {
        --bg-parallax-image: transparent;
    }

    #player {
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

    .bottom {
        position: absolute;
        width: 100%;
        margin-top: -50px;
        background: black;
        color: white;
        height: auto;
        z-index: 9;

    }

    .event-info .event-inner h2,
    .event-info .event-inner .h2 {
        font-weight: 800;
        color: #fff;
    }

    .event-info .btn {
        white-space: nowrap;
    }

    @media (max-width: 1240.98px) {
        .extra-huge-text-1 {
            font-size: 90px;
            line-height: 80px;
        }

        .extra-huge-text-2 {
            font-size: 88px;
            line-height: 83px;
            font-weight: 800;
            letter-spacing: -4.8px;
        }
    }

    @media (min-width: 1200px) and (max-width: 1490px) {
        .extra-huge-text-1 {
            font-size: 69px !important;
            line-height: 64px !important;
        }

        .extra-huge-text-2 {
            font-size: 81px !important;
            line-height: 90px !important;
        }
    }

    .border-light {
        border-top: 1px solid #fff;
    }

    @media (max-width: 575.98px) {
        .extra-huge-text-1 {
            font-size: 45px;
            line-height: 47px;
            white-space: nowrap;
        }

        .dinone {
            display: block !important;
        }

        .hero-3 h5 {
            font-size: 1.5rem;
            font-family: cursive;
            font-weight: 600;
        }

        .extra-huge-text-2 {
            font-size: 49px;
            line-height: 40px;
            font-weight: 700;
        }

        .circle-wrapper {
            display: none;
        }

        .event-info input {
            font-size: 13px;
            border-radius: 10px !important;
        }

        .event-info .btn {
            width: 100%;
            border-radius: 10px !important;
            padding: 4px;
            margin-top: 7px;
        }

        .logo-icon {
            height: 50px;
        }
    }

    @media (max-width:590px) {
        .btn-logo {
            display: block !important;
        }

        .event-info .event-inner h2{
            line-height: 1.2;
        }
    }
</style>

<!--Hero Section ======================-->
<section class="hero-section hero-3 position-relative">
    <div class="hero-wrapper mx-auto position-relative parallax">

        <video id="player" controls playsinline loop autoplay muted
            data-poster="{{asset('israel-assets/images/important/israel1.jpeg')}}"></video>

        <!-- <iframe id="player" width="560" height="315"
            src="https://www.youtube.com/embed/v5nfmtFzvvk?si=n9DjrzVPVB-eki-r&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;autoplay=1&amp;loop=1&amp;muted=0&amp;rel=0&amp;enablejsapi=1"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> -->

        <div class="hero-inner-text position-relative event-info mt-0" style="backdrop-filter: none !important;">
            <div class="hero-6-texts">
                <h5>The AWE Concert</h5>
                <h1 class="custom-poppins extra-huge-text-1 text-primary fw-extra-huge-bold text-uppercase mb-0">ISRAEL
                    MBONYI
                </h1>
                <h2 class="custom-poppins extra-huge-text-2 fw-extra-bold">LIVE</h2>
            </div>
            <div class="circle-wrapper hero-3-circle">
                <div class="star-icon">
                    <span><svg width="47" height="42">
                            <use xlink:href="#star-icon"></use>
                        </svg></span>
                </div>
                <div class="circle-bg"></div>
                <div class="rotate-text text-uppercase">
                    <p>A Great Worship Experience-</p>
                </div>
            </div>
        </div>
        <div class="event-info custom-inner-bg mt-4">
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

                        <form action="{{ route('stream.find') }}" method="POST" class="form-inline w-100 d-md-flex">
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
    <div class="bottom text-center border-light py-2">Buy Israel Mbonyi Live Access <a class="btn btn-link px-1 py-1 text-warning"
            href="{{ url("/event/{$current_event->id}/{$current_event->slug}") }}">Here</a> OR Get <b>2GB</b> Bundles
        Plus
        Israel Mbonyi Live Access, Dial <b>*544*46#ok</b> </div>
</section>
<!--Hero Section ======================-->


<!--Countdown Section ======================-->
<div class="countdown-section bg-white py-5 py-lg-50 py-xl-70 position-relative">
    <div class="container">
        <!--CountDown Section ======================-->
        <div class="countdown">
            <div class="row row-cols-2 row-cols-lg-3 row-cols-xl-4 justify-content-between align-items-center">
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number text-primary custom-jakarta" id="days">00</span>
                        <span class="countdown-label countdown-text-stroke text-opacity custom-roboto">Days</span>
                    </div>
                </div>
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number text-primary custom-jakarta" id="hours">00</span>
                        <span class="countdown-label countdown-text-stroke text-opacity custom-roboto">Hours</span>
                    </div>
                </div>
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number text-primary custom-jakarta" id="minutes">00</span>
                        <span class="countdown-label countdown-text-stroke text-opacity custom-roboto">Minutes</span>
                    </div>
                </div>
                <div class="col">
                    <div class="countdown-item">
                        <span class="countdown-number text-primary custom-jakarta" id="seconds">00</span>
                        <span class="countdown-label countdown-text-stroke text-opacity custom-roboto">Seconds</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Countdown Section ======================-->


<!--About Section ======================-->
<section id="about" class="about-section about-3 pb-50 pb-lg-100 bg-white">
    <div class="container">
        <div class="row gx-30 gx-xxl-70 gy-lg-0 gy-40 align-items-center">
            <div class="col-lg-4 col-xl-5">
                <div class="about-3-image">
                    <img src="{{asset('israel-assets/images/important/israel1.jpeg')}}" class="img-fluid rounded-5"
                        alt="about-image">
                </div>
            </div>
            <!-- col-5 -->
            <div class="col-lg-8 col-xl-7">
                <div class="section-title mb-30 mb-xxl-40">
                    <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                            class="straight-line"></span>The Worship Experience</span>
                    <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                        <span class="mb-n2 text-opacity">Soulful</span>
                        <span class="sub-title fw-extra-bold text-primary">Symphony</span>
                    </h2>
                    <p class="custom-jakarta custom-font-style-2 mb-0 mt-30">
                        The Africa Worship Experience brings you Kenya’s biggest gospel concert this year. Israel Mbonyi
                        Live in the AWE Concert.
                        One of a kind gospel extravaganza, Thousands across the world, together in worship.
                    </p>
                </div>
                <!-- section-title -->
                <div
                    class="about-3-text d-flex flex-column flex-lg-row gap-4 justify-content-between align-items-lg-center mb-40 mb-xl-80 mb-xxl-100">
                    <div>
                        <h4 class="fw-extra-bold custom-jakarta">10th Aug, 2024</h4>
                        <p class="custom-jakarta custom-font-style-2 fw-semibold mb-0">10:00am</p>
                    </div>
                    <div>
                        <h4 class="text-uppercase fw-extra-bold custom-jakarta">Ulinzi Sports Complex</h4>
                        <p class="custom-jakarta custom-font-style-2 fw-semibold mb-0"> Langata, 54871 Langata Rd,
                            Nairobi</p>
                    </div>
                </div>
                <!-- about-3-text -->
                <div class="event-counter">
                    <div class="row row-cols-2 row-cols-md-3 gy-lg-0 gy-4 justify-content-between">
                        <div class="col">
                            <div class="d-flex align-items-center gap-1 gap-lg-2">
                                <span class="odometer text-primary display-2" data-count-to=30></span>
                                <h3 class="fw-extra-bold d-flex flex-column mb-0 custom-jakarta">
                                    <span></span><span>Artists</span>
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-1 gap-lg-2">
                                <span class="odometer text-primary display-2" data-count-to=1></span>
                                <h3 class="fw-extra-bold d-flex flex-column mb-0 custom-jakarta">
                                    <span></span><span>Stage</span>
                                </h3>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="d-flex align-items-center gap-1 gap-lg-2">
                                <span class="odometer text-primary display-2" data-count-to=1></span>
                                <h3 class="fw-extra-bold d-flex flex-column mb-0 custom-jakarta">
                                    <span>Memorable</span><span>Experience</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--About Section ======================-->


<!--Highlights Section ======================-->
<section class="highlight-section highlight-2 pb-50 pb-lg-100 pb-xxl-120">
    <div class="container position-relative">
        <div class="row gy-4 gy-lg-0 align-items-lg-end justify-content-lg-between mb-30 mb-lg-70">
            <div class="col-lg-5">
                <div class="section-title">
                    <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                            class="straight-line"></span>Highlights</span>
                    <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                        <span class="mb-n2 text-opacity">Music</span>
                        <span class="sub-title fw-extra-bold text-primary">Extravaganza</span>
                    </h2>
                </div>
                <!-- section-title -->
            </div>
            <div class="col-lg-5">
                <div class="highlights-text">
                    <!-- <p class="custom-jakarta custom-font-style-2 text-lg-end mb-2">
                        Immerse in mesmerizing performances, vibrant soundscapes and interactive art at our music
                        extravaganza. Experience a festival atmosphere like no other.
                    </p> -->
                </div>
            </div>
        </div>
        <!-- row -->

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-30">
            <div class="col">
                <a href="#"
                    class="highlights-item-3 text-decoration-none position-relative d-flex flex-column gap-20 px-30 px-lg-40 py-40 py-lg-50">
                    <div class="highlights-icon-style-1">
                        <svg width="68" height="64">
                            <use xlink:href="#highlights-icon-1"></use>
                        </svg>
                    </div>
                    <h3 class="fw-extra-bold mb-0 custom-jakarta">Diverse Musical Lineup</h3>
                    <p class="custom-jakarta custom-font-style-2">
                        Experience a diverse array of musical genres of talented artists.
                    </p>
                </a>
            </div>
            <!-- col -->
            <div class="col">
                <a href="#"
                    class="highlights-item-3 text-decoration-none position-relative d-flex flex-column gap-20 px-30 px-lg-40 py-40 py-lg-50">
                    <div class="highlights-icon-style-1">
                        <svg width="58" height="68">
                            <use xlink:href="#highlights-icon-2"></use>
                        </svg>
                    </div>
                    <h3 class="fw-extra-bold mb-0 custom-jakarta">Immersive Performance</h3>
                    <p class="custom-jakarta custom-font-style-2">
                        Immerse yourself in captivating live performances that ignite the stage
                    </p>
                </a>
            </div>
            <!-- col -->
            <div class="col">
                <a href="#"
                    class="highlights-item-3 text-decoration-none position-relative d-flex flex-column gap-20 px-30 px-lg-40 py-40 py-lg-50">
                    <div class="highlights-icon-style-1">
                        <svg width="60" height="68">
                            <use xlink:href="#highlights-icon-3"></use>
                        </svg>
                    </div>
                    <h3 class="fw-extra-bold mb-0 custom-jakarta">Interactive Schedule</h3>
                    <p class="custom-jakarta custom-font-style-2">
                        Easily plan your day with an interactive event schedule.
                    </p>
                </a>
            </div>
            <!-- col -->
            <div class="col">
                <a href="#"
                    class="highlights-item-3 text-decoration-none position-relative d-flex flex-column gap-20 px-30 px-lg-40 py-40 py-lg-50">
                    <div class="highlights-icon-style-1">
                        <svg width="68" height="68">
                            <use xlink:href="#highlights-icon-4"></use>
                        </svg>
                    </div>
                    <h3 class="fw-extra-bold mb-0 custom-jakarta">Culinary Delights</h3>
                    <p class="custom-jakarta custom-font-style-2">
                        Indulge in a delightful culinary journey with a variety of food and beverage.
                    </p>
                </a>
            </div>
            <!-- col -->
            <div class="col">
                <a href="#"
                    class="highlights-item-3 text-decoration-none position-relative d-flex flex-column gap-20 px-30 px-lg-40 py-40 py-lg-50">
                    <div class="highlights-icon-style-1">
                        <svg width="68" height="68">
                            <use xlink:href="#highlights-icon-5"></use>
                        </svg>
                    </div>
                    <h3 class="fw-extra-bold mb-0 custom-jakarta">Engaging Activities</h3>
                    <p class="custom-jakarta custom-font-style-2">
                        Totos Corner by Funcity. <br>
                        A food Village with varierty of food and drinks
                    </p>
                </a>
            </div>
            <!-- col -->
        </div>
        <!-- row -->

    </div>
</section>
<!--Highlights Section ======================-->


<!--LineUp Section ======================-->
<section id="line-up"
    class="lineup-section lineup-2 subscription-2 pt-40 pb-100 pt-lg-100 pb-lg-130 pt-xxl-120 pb-xxl-180 mb-20">

    <div class="container">
        <div class="row gx-60 gx-xxl-80 gy-30 align-items-center">
            <div class="col-lg-12">
                <div class="lineup-right-content mt-3 mt-lg-0 d-md-flex justify-content-between">
                    <div class="section-title mb-4 mb-lg-30 mb-xxl-40">
                        <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                                class="straight-line"></span>Line-Up</span>
                        <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                            <span class="mb-n2 text-opacity">Top</span>
                            <span class="sub-title fw-extra-bold text-primary">Performances</span>
                        </h2>
                    </div>
                    <!-- section-title -->
                    <div class="col-md-5">
                        <!-- <p class="custom-jakarta custom-font-style-2 mb-4 mb-lg-30">
                            Unleash the rhythm with an extraordinary lineup. Get ready for a musical extravaganza that
                            will
                            captivate your senses.
                        </p> -->


                    </div>
                </div>
                <!-- lineup-right-content -->
            </div>
            <!-- col-5 -->
            <div class="col-lg-12">
                <div class="swiper-custom-progress progress-style-1 position-relative">
                    <div class="swiper lineup-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/israel.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">Israel Mbonyi</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span class="text-uppercase">pop</span>
                                            </h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- lineup-image-hover -->
                                </div>
                                <!-- lineup-image-wrapper -->
                            </div>
                            <!-- swiper-slide-->
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/destiny.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">Destiny Voices</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span class="text-uppercase">pop</span>
                                            </h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- lineup-image-hover -->
                                </div>
                                <!-- lineup-image-wrapper -->
                            </div>
                            <!-- swiper-slide-->
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/citylighters.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">City Lighters</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span
                                                    class="text-uppercase">rock</span></h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- lineup-image-hover -->
                                </div>
                                <!-- lineup-image-wrapper -->
                            </div>
                            <!-- swiper-slide-->
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/karura.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">Karura Voices</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span
                                                    class="text-uppercase">hip-hop</span></h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- lineup-image-hover -->
                                </div>
                                <!-- lineup-image-wrapper -->
                            </div>
                            <!-- swiper-slide-->
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/Rworship.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">R Worship</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span class="text-uppercase">pop</span>
                                            </h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- lineup-image-hover -->
                                </div>
                                <!-- lineup-image-wrapper -->
                            </div>
                            <!-- swiper-slide-->
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/Rworship.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">Adawnage Band</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span
                                                    class="text-uppercase">Choir</span></h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- lineup-image-hover -->
                                </div>
                                <!-- lineup-image-wrapper -->
                            </div>
                            <!-- swiper-slide-->
                            <div class="swiper-slide">
                                <div class="lineup-image-wrapper position-relative">
                                    <div class="lineup-image lineup-image-style-2">
                                        <img src="{{asset('israel-assets/images/important/kcrew.jpeg')}}"
                                            class="img-fluid" alt="lineup-image">
                                    </div>
                                    <div class="lineup-image-hover">
                                        <p class="author-name">K-Crew</p>
                                        <div class="line-up-hover-content">
                                            <h5 class="fw-medium mb-20">Genere : <span
                                                    class="text-uppercase">Choir</span></h5>
                                            <div class="line-up-icons d-flex align-items-center gap-3 gap-lg-20">
                                                <a href="#" class="facebook-icon" aria-label="facebook"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#facebook-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="instagram-icon" aria-label="instagram"><svg
                                                        width="20" height="20">
                                                        <use xlink:href="#instagram-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="youtube-icon" aria-label="youtube"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#youtube-icon"></use>
                                                    </svg></a>
                                                <a href="#" class="spotify-icon" aria-label="spotify"><svg width="20"
                                                        height="20">
                                                        <use xlink:href="#spotify-icon"></use>
                                                    </svg></a>
                                            </div>
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
                    <div class="ellipse-image-5">
                        <img src="{{asset('israel-assets')}}/images/ellipse-5.png" class="img-fluid" alt="img">
                    </div>
                </div>
            </div>
            <!-- col-7 -->
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</section>
<!--LineUp Section ======================-->


<!--Schedule Section ======================-->
<section id="schedule" class="schedule-section schedule-1 schedule-style-3 pt-50 pt-lg-120 position-relative">
    <div class="container">
        <div class="row gx-70 gy-40">
            <div class="col-lg-4">
                <div class="sticky-contents">
                    <div class="schedule-left-content">
                        <div class="section-title mb-30 mb-lg-40 mb-xxl-60">
                            <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                                    class="straight-line"></span>Schedule</span>
                            <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                                <span class="mb-n2 text-opacity">Melody</span>
                                <span class="sub-title fw-extra-bold text-primary">Agenda</span>
                            </h2>
                        </div>
                        <!-- section-title -->
                        <p class="custom-jakarta custom-font-style-2">
                            Captivating performances, interactive workshops, and delightful culinary delights await you
                            at Harmonia Music Festival. Let the melodies transcend boundaries on this unforgettable
                            musical journey.
                        </p>

                    </div>
                    <!-- schedule-left-content -->
                </div>
            </div>
            <!-- col-4 -->
            <div class="col-lg-8">
                <div class="schedule-right-content position-relative">
                    <div class="ellipse-image-3">
                        <img src="{{asset('israel-assets')}}/images/ellipse-3.png" class="img-fluid" alt="img">
                    </div>

                    <!-- Tabs -->
                    <ul class="schedule-tabs nav nav-pills mb-50 mb-lg-70 d-flex justify-content-between justify-content-lg-center"
                        id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="schedule-button active" id="day-1-tab" data-bs-toggle="pill"
                                data-bs-target="#day-1" type="button" role="tab" aria-controls="day-1"
                                aria-selected="true">
                                <span class="fs-3 fw-extra-bold mb-0">August 10th, 2024</span>
                                <span class="fs-5 fw-semibold mb-0 d-none d-lg-block">From 10:00 AM</span>
                            </button>
                        </li>
                    </ul>
                    <!-- Tabs -->


                    <!-- Tabs-Contents -->
                    <div class="tab-content" id="pills-tabContent">

                        <!--day-1-tab  -->
                        <div class="tab-pane fade show active" id="day-1" role="tabpanel" aria-labelledby="day-1-tab"
                            tabindex="0">
                            <ul class="schedule-tabs-content list-unstyled d-flex flex-column mb-0 gap-30">
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">10:00 AM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">Pre-Event Registration</h2>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">11:30 AM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">Family Fun
                                        </h2>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">02:00 PM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">Adawnage Band</h2>
                                        <p class="custom-jakarta custom-font-style-2">
                                            Adawnage, the Kenyan gospel band, popularly known for its landmark songs
                                            Naomba and Uwezo. Come experience their savvy talent and skill in Gospel
                                            songwriting and music production.
                                        </p>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">03:00 PM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">R-Worship
                                            Unveiled</h2>
                                        <p class="custom-jakarta custom-font-style-2">



                                            R - Worship. Music ministry of Ruach Tabernacle, a ministry of The Purpose
                                            Center Church.
                                        </p>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">04:00 PM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">Destiny Voices</h2>
                                        <p class="custom-jakarta custom-font-style-2">
                                            Destiny Voices Band from Life Church Limuru will be performing live at the
                                            #IsraelLiveInKenya
                                        </p>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">05:00 PM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">City Lighters
                                        </h2>
                                        <p class="custom-jakarta custom-font-style-2">
                                            City Lighters is an interdenominational, multicultural band born of the need
                                            for fellowship for young adults in the city. A Christ-centered, Word-based,
                                            Spirit-led band working in the heart of the City of Nairobi, Kenya.
                                        </p>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">06:00 PM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">Karura Voices
                                        </h2>
                                        <p class="custom-jakarta custom-font-style-2">
                                            Karura Voices is the worship team for Karura Community Chapel Adult Church.
                                            They have produced multiple albums that include Wimbo Wa Sifa and Momentum
                                            2021. Come meet a band that exists to serve God, serve all and inspire
                                            others through music.
                                        </p>
                                    </div>
                                </li>
                                <li class="d-flex flex-column flex-lg-row gap-1 gap-lg-70 gap-xxl-90">
                                    <h2 class="fw-extra-bold schedule-time text-opacity custom-jakarta">07:00 PM</h2>
                                    <div>
                                        <h2 class="fw-semibold text-opacity custom-jakarta">Israel Mbonyi
                                        </h2>
                                        <p class="custom-jakarta custom-font-style-2">
                                            Extend your night under the stars with intimate acoustic sessions. Unwind
                                            with soul-stirring melodies and acoustic performances by guest artists.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!--day-1-tab  -->


                    </div>
                    <!-- Tabs-Contents -->

                </div>
                <!-- schedule-right-content -->
            </div>
            <!-- col-8 -->
        </div>
    </div>
</section>
<!--Schedule Section ======================-->

<div class="my-5">.</div>

<!--Pricing Section ======================-->
<section id="pricing" class="pricing-section pricing-1 pt-lg-4 pt-5 pb-50 pb-lg-100 pb-xxl-120">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="row gy-4 gy-lg-0 align-items-lg-end justify-content-lg-between mb-30 mb-lg-70">
                    <div class="">
                        <div class="section-title">
                            <span class="fs-3 straight-line-wrapper fw-semibold position-relative"> <span
                                    class="straight-line"></span>Buy virtual Live access</span>
                            <h2 class="title display-3 fw-extra-bold d-flex flex-column">
                                <span class="mb-n2 text-opacity">Streaming</span>
                                <span class="sub-title fw-extra-bold text-primary">Tickets</span>
                            </h2>
                        </div>
                        <!-- section-title -->
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <style>
                    .pricing-icon {
                        display: flex;
                        align-items: center;
                    }
                </style>
                <!-- row -->
                <div class="row gx-30 gy-50">
                    <div class="col-md-6">
                        <div class="pricing-wrapper bg-ticket position-relative">
                            <div class="pricing-top-content">
                                <div class="pricing-icon mb-10">
                                    <svg width="55" height="49">
                                        <use xlink:href="#pricing-1-icon"></use>
                                    </svg>
                                    <h3 class="fw-semibold pricing-title mb-0 ms-2">Streaming Access</h3>
                                </div>
                                <h3 class="mb-40"><span class="fw-semibold pricing-subtitle"> KES</span>
                                    <span class="display-2 fw-extra-bold text-primary me-10 custom-jakarta"> 500</span>
                                </h3>
                                <ul class="pricing-list list-unstyled d-flex flex-column gap-10">
                                    <li class="mb-0"><span class="check-icon"><svg width="16" height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>Stream Live in HD</li>
                                    <li class="mb-0"><span class="check-icon"><svg width="16" height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>30-Days Catch-Up</li>
                                    <li class="mb-0"><span class="check-icon"><svg width="16" height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>30-Days VOD</li>
                                    <li class="mb-0" style="opacity: 0;"><span class="check-icon"><svg width="16"
                                                height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>.</li>
                                </ul>
                            </div>

                            <div class="pricing-separator">
                                <svg viewBox="0 0 420 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_3632_1203)">
                                        <g clip-path="url(#clip1_3632_1203)">
                                            <path
                                                d="M30 -418H390C406.016 -418 419 -405.016 419 -389V8.63272C419 16.9435 414.514 24.6071 407.267 28.6754C390.261 38.222 390.261 62.702 407.267 72.2486C414.514 76.3169 419 83.9804 419 92.2912V190C419 206.016 406.016 219 390 219H30C13.9838 219 1 206.016 1 190V92.9604C1 84.3293 5.89026 76.4435 13.6218 72.607C31.9323 63.5211 31.9323 37.4029 13.6218 28.317C5.89026 24.4804 1 16.5946 1 7.9635V-389C1 -405.016 13.9837 -418 30 -418Z"
                                                stroke-width="2" />
                                            <line x1="28" y1="49" x2="394" y2="49" stroke-width="2"
                                                stroke-dasharray="10 10" />
                                        </g>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_3632_1203">
                                            <rect width="420" height="100" fill="white" />
                                        </clipPath>
                                        <clipPath id="clip1_3632_1203">
                                            <rect width="420" height="616" fill="white" transform="translate(0 -419)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>

                            <div class="pricing-bottom-content position-relative">

                                <a href="#" class="btn btn-primary" aria-label="buttons">Buy Now</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="pricing-wrapper bg-ticket position-relative">
                            <div class="pricing-top-content">
                                <div class="pricing-icon mb-10">
                                    <svg width="55" height="49">
                                        <use xlink:href="#pricing-1-icon"></use>
                                    </svg>
                                    <h3 class="fw-semibold pricing-title mb-0 ms-2">Max Package</h3>
                                </div>
                                <h3 class="mb-40"><span class="fw-semibold pricing-subtitle"> KES</span>
                                    <span class="display-2 fw-extra-bold text-primary me-10 custom-jakarta"> 600</span>
                                </h3>
                                <ul class="pricing-list list-unstyled d-flex flex-column gap-10">
                                    <li class="mb-0"><span class="check-icon"><svg width="16" height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>2GB Streaming Data Bundle(24 Hrs)</li>
                                    <li class="mb-0"><span class="check-icon"><svg width="16" height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>Stream Live in HD</li>
                                    <li class="mb-0"><span class="check-icon"><svg width="16" height="16">
                                                <use xlink:href="#check-icon"></use>
                                            </svg></span>30-Days Catch-Up/VOD</li>
                                </ul>
                            </div>

                            <div class="pricing-separator">
                                <svg viewBox="0 0 420 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_3632_1203)">
                                        <g clip-path="url(#clip1_3632_1203)">
                                            <path
                                                d="M30 -418H390C406.016 -418 419 -405.016 419 -389V8.63272C419 16.9435 414.514 24.6071 407.267 28.6754C390.261 38.222 390.261 62.702 407.267 72.2486C414.514 76.3169 419 83.9804 419 92.2912V190C419 206.016 406.016 219 390 219H30C13.9838 219 1 206.016 1 190V92.9604C1 84.3293 5.89026 76.4435 13.6218 72.607C31.9323 63.5211 31.9323 37.4029 13.6218 28.317C5.89026 24.4804 1 16.5946 1 7.9635V-389C1 -405.016 13.9837 -418 30 -418Z"
                                                stroke-width="2" />
                                            <line x1="28" y1="49" x2="394" y2="49" stroke-width="2"
                                                stroke-dasharray="10 10" />
                                        </g>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_3632_1203">
                                            <rect width="420" height="100" fill="white" />
                                        </clipPath>
                                        <clipPath id="clip1_3632_1203">
                                            <rect width="420" height="616" fill="white" transform="translate(0 -419)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>


                            <div class="pricing-bottom-content position-relative">

                                <a href="tel:*544*46#" class="btn btn-outline-primary d-none d-md-block" disabled
                                    aria-label="buttons">Dial *544*46#</a>
                                <a href="tel:*544*46%23" class="btn btn-outline-primary d-md-none"
                                    aria-label="buttons">Dial
                                    *544*46#</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--Pricing Section ======================-->


<!--Separator Section ======================-->
<div class="container">
    <div class="separator"></div>
</div>
<!--Separator Section ======================-->


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
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm border py-3">
                            <img src="{{ asset('landing-assets/images/saf.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm border py-3">
                            <img src="{{ asset('landing-assets/images/laugh.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm border py-3">
                            <img src="{{ asset('landing-assets/images/baze.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm border py-3">
                            <img src="{{ asset('landing-assets/images/angani.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="sponsor-wrapper p-2 bg-white border-0 shadow-sm border py-3">
                            <img src="{{ asset('landing-assets/images/dpo.png')}}" class="img-fluid w-100" alt="img">
                        </a>
                    </div>
                    <div class="col">
                        <a href="https://www.caydeesoft.com/"
                            class="sponsor-wrapper p-2 bg-white border-0 shadow-sm border py-3">
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


<!--Gallery Section ======================-->
<div class="gallery-section gallery-1 py-50 py-lg-100 py-xxl-120">
    <div class="swiper swiper_gallery">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="http://www.youtube.com/watch?v=iwcjcSEmw60" class="video-popup-link hover-area"
                        data-cursor-text="Video">
                        <img src="{{asset('israel-assets/images/gallery/nitaa.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="{{asset('israel-assets')}}/images/gallery-2.jpg" class="image-link hover-area"
                        data-cursor-text="Image">
                        <img src="{{asset('israel-assets')}}/images/gallery-2.jpg" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="http://www.youtube.com/watch?v=S1hjPq5nvis" class="video-popup-link hover-area"
                        data-cursor-text="Video">
                        <img src="{{asset('israel-assets/images/gallery/jambo.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="http://www.youtube.com/watch?v=4J9npr3fdk4" class="video-popup-link hover-area"
                        data-cursor-text="Video">
                        <img src="{{asset('israel-assets/images/gallery/malengo.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="{{asset('israel-assets')}}/images/gallery-5.jpg" class="image-link hover-area"
                        data-cursor-text="Image">
                        <img src="{{asset('israel-assets')}}/images/gallery-5.jpg" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="http://www.youtube.com/watch?v=qI1yCpa4UFE" class="video-popup-link hover-area"
                        data-cursor-text="Video">
                        <img src="{{asset('israel-assets/images/gallery/sikiliza.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- swiper-gallery -->

    <div class="swiper swiper_gallery2">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="{{asset('israel-assets/images/gallery/israel2.jpg')}}" class="image-link hover-area"
                        data-cursor-text="Image">
                        <img src="{{asset('israel-assets/images/gallery/israel2.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="http://www.youtube.com/watch?v=v5nfmtFzvvk" class="video-popup-link hover-area"
                        data-cursor-text="Video">
                        <img src="{{asset('israel-assets/images/gallery/siri.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="{{asset('israel-assets/images/important/israel1.jpeg')}}" class="image-link hover-area"
                        data-cursor-text="Image">
                        <img src="{{asset('israel-assets/images/important/israel1.jpeg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="http://www.youtube.com/watch?v=lZMXzE_0bMQ" class="video-popup-link hover-area"
                        data-cursor-text="Video">
                        <img src="{{asset('israel-assets/images/gallery/amenisamehe.jpg')}}" alt="img">
                    </a>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="gallery-image">
                    <a href="{{asset('israel-assets')}}/images/gallery-1.jpg" class="image-link hover-area"
                        data-cursor-text="Image">
                        <img src="{{asset('israel-assets')}}/images/gallery-1.jpg" alt="img">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- swiper-gallery2 -->
</div>
<!--Gallery Section ======================-->



<script>
    function updateTargetDate() {
        const targetDate = new Date('2024-08-10T09:59:59');
        return targetDate.getTime();
    }


    function updateCountdown() {
        const targetDate = updateTargetDate();
        const now = new Date().getTime();
        const timeLeft = targetDate - now;
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        document.getElementById("days").textContent = days < 10 ? "0" + days : days;
        document.getElementById("hours").textContent = hours < 10 ? "0" + hours : hours;
        document.getElementById("minutes").textContent = minutes < 10 ? "0" + minutes : minutes;
        document.getElementById("seconds").textContent = seconds < 10 ? "0" + seconds : seconds;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
@endsection