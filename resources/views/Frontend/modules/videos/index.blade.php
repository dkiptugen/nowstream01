@extends('Frontend.includes.layout')

@section('content')
<main>
    <!-- breadcrumb-area -->
    @php
    $breadcrumbTitle = $breadcrumbTitle ?? 'Videos';
    $breadcrumbSubtitle = $breadcrumbSubtitle ?? 'Our';
    $breadcrumbItems = $breadcrumbItems ?? [
    ['title' => 'Home', 'url' => url('/')],
    ['title' => $breadcrumbTitle, 'url' => null],
    ];
    @endphp

    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">{{ $breadcrumbSubtitle }} <span>{{ $breadcrumbTitle }}</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @foreach ($breadcrumbItems as $item)
                                @if ($loop->last || !$item['url'])
                                <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                @endif
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->
    @php use App\Models\Channel; @endphp
    @if($top_videos->isNotEmpty())
    <!-- Top Videos -->
    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-end mb-60">
                <div class="col-lg-6">
                    <div class="section-title text-center text-lg-left">
                        <span class="sub-title">.......</span>
                        <h2 class="title">Top Videos</h2>
                    </div>
                </div>
            </div>

            <div class="row tr-movie-active">
                @foreach($top_videos as $video)
                @php
                $channel = Channel::find($video->channel_id);
                $thumbnail = $video->thumbnail_url ? Storage::disk(config('filesystems.default'))->url($video->thumbnail_url) : asset('frontend-assets/images/default.png');
                @endphp
                @include('Frontend.includes.components.cards.video-card')
                @endforeach
            </div>
        </div>
    </section>
    <!-- Top Videos End -->
    @endif
    @if($top_videos->isNotEmpty())
    <!-- Latest Videos -->
    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-end mb-60">
                <div class="col-lg-6">
                    <div class="section-title text-center text-lg-left">
                        <span class="sub-title">.......</span>
                        <h2 class="title">Latest Videos</h2>
                    </div>
                </div>
            </div>

            <div
                class="row tr-movie-active"
                id="video-container"
                data-next-page-url="{{ $videos->nextPageUrl() }}"
                data-loading-label="Loading more videos..."
                data-idle-label="More videos coming up"
                data-complete-label="All videos loaded"
                data-error-label="Could not load more videos right now"
            >
                @include('Frontend.includes.components.partials.video-items', ['videos' => $videos])
            </div>

            <div class="text-center my-4 infinite-scroll-loader" id="video-loading" @if(!$videos->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="video-loading-status">
                    {{ $videos->hasMorePages() ? 'More videos coming up' : 'All videos loaded' }}
                </span>
            </div>
        </div>
    </section>
    <!-- Latest Videos End -->
    @endif
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
    'containerId' => 'video-container',
    'loaderId' => 'video-loading',
    'statusId' => 'video-loading-status',
])
@endsection
