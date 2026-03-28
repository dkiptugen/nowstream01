@extends('Frontend.includes.layout')
@section('content')
<main>
    <!-- Breadcrumb -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Live <span>TVs</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">TVs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- TV Genres -->
            <div class="ucm-nav-wrap">
                <ul class="nav nav-tabs" id="genreTabs" role="tablist">
                    @foreach($genres->filter()->unique() as $genre)
                    @php
                    $slug = Str::slug($genre);
                    $label = ucfirst(trim($genre));
                    @endphp
                    @if(!empty($slug))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('genre.tvs', ['genre' => $slug]) }}">
                            {{ $label }}
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <!-- Top Trending TVs Slider -->
    <section class="top-rated-movie tr-movie-bg pb-0" data-background="{{ asset('assets/img/bg/tr_movies_bg.jpg') }}">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title">
                    <span class="sub-title">Trending TVs</span>
                    <h2 class="title">Trending TVs</h2>
                </div>
            </div>

            <div class="pcar-wrapper">
                <div class="pcar-overlay pcar-overlay-left"></div>
                <div class="pcar-overlay pcar-overlay-right"></div>

                <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="11" data-tablet="3" data-mobile="2">
                    <div class="pcar-track">
                        @foreach($toptvs as $tv)
                        <div class="pcar-item">
                            @include('Frontend.includes.components.cards.slider-card', ['item' => $tv])
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- English Channels -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img/bg/tr_movies_bg.jpg') }}">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title">
                    <span class="sub-title">English Channels</span>
                    <h2 class="title">English Channels</h2>
                </div>
            </div>

            <div class="row tr-movie-active">
                @foreach($english_tvs as $tv)
                @include('Frontend.includes.components.cards.tv-card', ['tv' => $tv])
                @endforeach
            </div>
        </div>
    </section>

    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        
        <div class="container mt-md-5">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest tvs</span>
                    <h2 class="title">Latest tvs</h2>
                </div>
            </div>

            <div
                class="row tr-movie-active h-100"
                id="tv-container"
                data-next-page-url="{{ $tvs->nextPageUrl() }}"
                data-loading-label="Loading more TVs..."
                data-idle-label="More channels coming up"
                data-complete-label="All live channels loaded"
                data-error-label="Could not load more TVs right now"
                style="position: relative; height:auto !important;"
            >
                 @include('Frontend.includes.components.partials.tv-items', ['tvs' => $tvs])
            </div>

            <div class="text-center my-4 infinite-scroll-loader" id="tv-loading" @if(!$tvs->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="tv-loading-status">
                    {{ $tvs->hasMorePages() ? 'More channels coming up' : 'All live channels loaded' }}
                </span>
            </div>

        </div>
        </div>
    </section>
</main>
@endsection
@section('header')
<style>
    .col-xl-2.col-lg-3.col-sm-6.grid-item{
        position: relative !important;
    }

    .infinite-scroll-loader {
        display: grid;
        place-items: center;
        gap: 12px;
        min-height: 88px;
    }

    .infinite-scroll-loader[hidden] {
        display: none !important;
    }

    .infinite-scroll-dot {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-top-color: #ffd24f;
        animation: infiniteScrollSpin 0.9s linear infinite;
    }

    .infinite-scroll-loader:not(.is-loading) .infinite-scroll-dot {
        animation-play-state: paused;
        opacity: 0.45;
    }

    .infinite-scroll-copy {
        color: rgba(255, 255, 255, 0.72);
        font-size: 13px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    @keyframes infiniteScrollSpin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endsection
@section('footer')
@include('Frontend.includes.components.partials.infinite-scroll', [
    'containerId' => 'tv-container',
    'loaderId' => 'tv-loading',
    'statusId' => 'tv-loading-status',
])
@endsection
