@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
<main> <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Our<span>Podcasts</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Podcasts</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="ucm-nav-wrap">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($categories as $category)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="{{ $category->slug }}-tab" data-toggle="tab" href="#{{ $category->slug }}" role="tab" aria-controls="{{ $category->slug }}" aria-selected="false">
                            {{ ucfirst($category->name) }}
                        </a>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest Podcasts</span>
                    <h2 class="title">Latest Podcasts</h2>
                </div>
            </div>
            <div class="row tr-movie-active">

                @foreach($topPodcasts as $podcast)
                @include('Frontend.includes.components.cards.podcast-card')
                @endforeach
            </div>
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest Podcasts</span>
                    <h2 class="title">Latest Podcasts</h2>
                </div>
            </div>
            <div
                class="row tr-movie-active h-100"
                id="podcast-container"
                data-next-page-url="{{ $podcasts->nextPageUrl() }}"
                data-loading-label="Loading more podcasts..."
                data-idle-label="More podcasts coming up"
                data-complete-label="All podcasts loaded"
                data-error-label="Could not load more podcasts right now"
            >
                @include('Frontend.includes.components.partials.podcast-list', ['podcasts' => $podcasts])
            </div>

            <div class="text-center my-4 infinite-scroll-loader" id="podcast-loading" @if(!$podcasts->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="podcast-loading-status">
                    {{ $podcasts->hasMorePages() ? 'More podcasts coming up' : 'All podcasts loaded' }}
                </span>
            </div>

        </div>
    </section>

</main>
@endsection
@section('header')
<style>
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
    'containerId' => 'podcast-container',
    'loaderId' => 'podcast-loading',
    'statusId' => 'podcast-loading-status',
])
@endsection
