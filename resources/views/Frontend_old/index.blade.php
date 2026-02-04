@extends('Frontend.includes.layout')


@section('content')
<div class="d-md-none">
    @foreach($current_event as $event)
        <div id="carouselExampleSlidesOnly" class="carousel carousel-fade slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="4000">
                    <a href="{{ url("/event/{$event->id}/{$event->slug}") }}">
                        <img src="{{asset('/landing-assets/images/somalibg2.png')}}" class="img-fluid  w-100 ratio16"
                            alt="{{ $event->event_name }}">
                    </a>
                </div>
                {{-- <div class="carousel-item" data-bs-interval="4000"> --}}
                    {{-- <img src="{{asset('/sliders/mob2.png')}}" alt="" class="w-100"> --}}
                    {{-- </div> --}}
                <!-- <div class="carousel-item">
                            <img src="{{asset('/sliders/mob6.png')}}" alt="" class="w-100">
                            </div> -->
            </div>
        </div>
        <div class="text p-3">

            <!-- <h1 class=" my-4">Content every show <br>
                                     Every concert <br>  Every gig in Africa</h1> -->

            <div class="form-group w-100">
                @if (session('error'))
                    <div class="alert alert-danger mt-4">
                        {{ session('error') }}
                    </div>
                @endif
                <h3 class="text-light mb-3">Buy
                    {{$event->title}} Live Access By <a class=" btn-link p-0 text-warning"
                        href="{{ url("/event/{$event->id}/{$event->slug}") }}">Clicking Here</a>
                </h3>

                <form action="{{ route('stream.find') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="input-group mw-500">
                        <input type="text" class="form-control rounded-0" name="stream_token"
                            placeholder="Enter Token or Phone Number" aria-label="Stream token"
                            aria-describedby="button-addon2">
                        <input type="hidden" name="event_id" value="{{$event->id}}">
                        <button class="btn btn-danger px-2" type="submit" id="button-addon2">Watch Live</button>
                        <p class="w-100 text-left text-light mt-2 mb-0">Already Bought? Enter Stream Token Or Phone Number
                            To Watch. OR get <b>5GB</b>Bundles with Somali Nite Live Access, Dial <b>*544*46#ok.</b>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>

<div class="card bg-dark text-white mb-0 d-none d-md-block">
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{asset('/landing-assets/images/somalibg8.png')}}" class="card-img" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{asset('/landing-assets/images/somalibg7.png')}}" class="card-img" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{asset('/landing-assets/images/somalibg2.png')}}" class="card-img" alt="...">
            </div>
        </div>
    </div>
    <div class="card-img-overlay">
        <h1 class="heading-xl mb-4">Click <a class="text-warning"
                href="{{ url("/event/{$event->id}/{$event->slug}") }}">Here
            </a>to Enjoy <br> {{$event->title}}
        </h1>
        <div class="form-group w-100">
            @if (session('error'))
                <div class="alert alert-danger mt-4">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('stream.find') }}" method="POST" class="form-inline w-100 mb-5">
                @csrf
                <div class="input-group">
                    <input type="text" class="form-control" name="stream_token"
                        placeholder="Kindly Enter Token or Phone Number" aria-label="Stream token"
                        aria-describedby="button-addon2">
                    <input type="hidden" name="event_id" value="{{$event->id}}">
                    <button class="btn btn-danger btn-inline" type="submit" id="button-addon2">
                        Get Started<i class="bx bx-right-arrow-alt"></i>
                    </button>
                </div>
                <p class="w-100 mt-2 h6 text-light">Already Paid? Enter Mobile Number or Token To Watch. </p>
            </form>
            <div class="bottom text-center">Buy Somali Nite Live Access <a class="btn btn-link text-warning"
                    href="{{ url("/event/{$event->id}/{$event->slug}") }}">Here</a> OR Get <b>5GB</b> Bundles Plus
                Somali Nite Live Access, Dial <b>*544*46#ok</b> </div>
        </div>
    </div>
</div>

<div class="hero-image d-none"
    style="background-image: linear-gradient(74deg, #000000, #000000c2, #0000005c, #00000014, #12111100, #00000000, transparent), url({{asset('sliders/6.png')}})">
    <div class="container">
        <div class="hero-text col-md-10">
            <div class="align-self-center order-2 order-md-0">
                <div class="text p-md-4 p-2 text-light">

                    <h5 class="text-light">Explore Baze Live</h5>
                    <h1 class="text-light my-4"><b>Stream every show <br> Every concert <br> Every gig in Africa</b>
                    </h1>

                    <div class="form-group w-100 mt-3">
                        @if (session('error'))
                            <div class="alert alert-danger mt-4">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('stream.find') }}" method="POST" class="form-inline">
                            @csrf
                            <div class="input-group my-4 mw-500">
                                <input type="text" class="form-control" name="stream_token"
                                    placeholder="Enter Stream Token eg EWEEESU9 or Phone Number"
                                    aria-label="Stream token" aria-describedby="button-addon2">
                                <button class="btn btn-danger" type="submit" id="button-addon2">Submit To Watch</button>
                            </div>
                        </form>
                        <div class="d-flex justify-content-end flex-column">
                            <h5 class="w-100 text-left">Dont have a Stream Token? <a class="text-warning"
                                    href="{{url('/events')}}">Click Here
                                    to Buy</a></h5>
                        </div>
                    </div>
                </div>
                <div class="p-md-4 w-75">
                </div>
            </div>
        </div>
    </div>
</div>
<!--start page wrapper -->
<div class="page-wrapper mt-0">
    <div class="page-content">
        @include('Frontend.includes.components.trending-streams')
        @include('Frontend.includes.components.trending-videos')
        @include('Frontend.includes.components.channels')

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
                background-image: url({{asset('/landing-assets/images/somalibg2.png')}}) !important;
                background-size: cover;
                background-position: center;
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
