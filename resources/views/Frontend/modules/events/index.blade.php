@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!-- main-area -->
<main>
 
	<!-- breadcrumb-area -->
	<section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img')}}/bg/breadcrumb_bg.jpg">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="breadcrumb-content">
						<h2 class="title">Our <span>Events</span></h2>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Events</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- breadcrumb-area-end -->
	 
    <section class="top-rated-movie tr-movie-bg pb-0" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> 
					<span class="sub-title">Trending Events</span>
                    <h2 class="title">Trending Events</h2>
                </div>
            </div>
        </div>

        <div class="pcar-wrapper">

            <!-- Outside container overlays -->
            <div class="pcar-overlay pcar-overlay-left"></div>
            <div class="pcar-overlay pcar-overlay-right"></div>

            <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="5" data-tablet="3"
                data-mobile="2">

                <div class="pcar-track">
                    @foreach($topevents as $event)
                    <div class="pcar-item">
					@include('Frontend.includes.components.cards.events')
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
	<!-- movie-area -->
	<section class="movie-area movie-bg" data-background="{{ asset('assets/img')}}/bg/movie_bg.jpg">
		<div class="container">
			<h5 class="mb-3 section-title">
				<!-- Error Alert -->
				@if (session('success'))
					You dont Have an active subscription. Pick an Event Below <br>

				@endif 
			</h5>
			<div class="row align-items-end mb-60">
				<div class="col-lg-6">
					<div class="section-title text-center text-lg-left">
						<span class="sub-title">.......</span>
						<h2 class="title">Latest Events</h2>
					</div>
				</div>
				<div class="col-lg-6">
				</div>
			</div>
			<div
                class="row tr-movie-active"
                id="event-container"
                data-next-page-url="{{ $events->nextPageUrl() }}"
                data-loading-label="Loading more events..."
                data-idle-label="More events on the way"
                data-complete-label="All events loaded"
                data-error-label="Could not load more events right now"
            >
				@include('Frontend.includes.components.partials.event-items', ['events' => $events])
			</div>
            <div class="text-center my-4 infinite-scroll-loader" id="event-loading" @if(!$events->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="event-loading-status">
                    {{ $events->hasMorePages() ? 'More events on the way' : 'All events loaded' }}
                </span>
            </div>
		</div>
	</section>
	<!-- movie-area-end -->
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
        'containerId' => 'event-container',
        'loaderId' => 'event-loading',
        'statusId' => 'event-loading-status',
    ])
	@endsection
