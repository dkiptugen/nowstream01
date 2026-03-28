@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
<main> <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Live <span>radios</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">radios</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="ucm-nav-wrap">
                <ul class="nav nav-tabs" id="genreTabs" role="tablist">
                    @foreach($genres->filter()->unique() as $genre)
                    @php
                    $slug = Str::slug($genre);
                    $label = ucfirst(trim($genre));
                    @endphp
                    @if(!empty($slug))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('genre.radios', ['genre' => $slug]) }}">
                            {{ $label }}
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Trending Radios</span>
                    <h2 class="title">Trending Radios</h2>
                </div>
            </div>
        </div>

        <div class="pcar-wrapper">

            <!-- Outside container overlays -->
            <div class="pcar-overlay pcar-overlay-left"></div>
            <div class="pcar-overlay pcar-overlay-right"></div>

            <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="11" data-tablet="3"
                data-mobile="2">

                <div class="pcar-track">
                    @foreach($topradios as $item)
                    <div class="pcar-item">
                        @include('Frontend.includes.components.cards.slider-card')
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="container mt-md-5">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest radios</span>
                    <h2 class="title">Latest radios</h2>
                </div>
            </div>

            <div
                class="row tr-movie-active h-100"
                id="radio-container"
                data-next-page-url="{{ $radios->nextPageUrl() }}"
                data-loading-label="Loading more radios..."
                data-idle-label="More stations coming up"
                data-complete-label="All radio stations loaded"
                data-error-label="Could not load more radios right now"
                style="position: relative; height:auto !important;"
            >
                @include('Frontend.includes.components.partials.radio-items', ['radios' => $radios])
            </div>

            <div class="text-center my-4 infinite-scroll-loader" id="radio-loading" @if(!$radios->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="radio-loading-status">
                    {{ $radios->hasMorePages() ? 'More stations coming up' : 'All radio stations loaded' }}
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
    'containerId' => 'radio-container',
    'loaderId' => 'radio-loading',
    'statusId' => 'radio-loading-status',
])
@endsection
