@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
    <main> <!-- breadcrumb-area -->
        <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-content">
                            <h2 class="title">Live <span>Tvs</span></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Tvs</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="ucm-nav-wrap">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($categories as $category)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="{{ $category->slug }}-tab" data-toggle="tab"
                                    href="#{{ $category->slug }}" role="tab" aria-controls="{{ $category->slug }}"
                                    aria-selected="false">
                                    {{ ucfirst($category->name) }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </section> <!-- breadcrumb-area-end -->
        <div class="custom-carousel" 
     data-autoplay="true" 
     data-interval="4000">

    <button class="carousel-btn prev">&#10094;</button>

    <div class="carousel-viewport">
        <div class="carousel-track">

        <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>
            <div class="carousel-item">
                uygfuy
            </div>

        </div>
    </div>

    <button class="carousel-btn next">&#10095;</button>
</div>


        <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
            <div class="container">
                <div class="episode-top-wrap">
                    <div class="section-title"> <span class="sub-title">Trending TVs</span>
                        <h2 class="title">Trending TVs</h2>
                    </div>
                </div>
                <div class="row tr-movie-active">

                    @foreach($toptvs as $tv)
                        @include('Frontend.includes.components.cards.tv-card')
                    @endforeach
                </div>
                <div class="row tr-movie-active">

                    @foreach($english_tvs as $tv)
                        @include('Frontend.includes.components.cards.tv-card')
                    @endforeach
                </div>
                <div class="episode-top-wrap">
                    <div class="section-title"> <span class="sub-title">Latest TVs</span>
                        <h2 class="title">Latest TVs</h2>
                    </div>
                </div>
                <div class="row tr-movie-active">

                    @foreach($tvs as $tv)
                        @include('Frontend.includes.components.cards.tv-card')
                    @endforeach
                </div>
            </div>
        </section>

    </main>
@endsection
<style>
    .custom-carousel {
        position: relative;
        width: 100%;
    }

    .carousel-viewport {
        overflow: hidden;
        width: 100%;
    }

    .carousel-track {
        display: flex;
        transition: transform 0.4s ease;
        will-change: transform;
    }

    .carousel-item {
        flex: 0 0 auto;
        position: relative;
        padding: 5px;
    }

    /* Navigation buttons */
    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        background: rgba(0, 0, 0, 0.6);
        border: none;
        color: #fff;
        font-size: 24px;
        padding: 8px 12px;
        cursor: pointer;
    }

    .carousel-btn.prev {
        left: 0;
    }

    .carousel-btn.next {
        right: 0;
    }

    /* Edge overlay effect */
    .carousel-item::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0);
        transition: background 0.3s ease;
        pointer-events: none;
    }

    .carousel-item.edge-overlay::after {
        background: rgba(0, 0, 0, 0.35);
    }

    /* Disable overlay on tablet/mobile */
    @media (max-width: 991px) {
        .carousel-item.edge-overlay::after {
            background: transparent;
        }
    }
</style> 

@section('header')
@endsection
@section('footer')

@endsection