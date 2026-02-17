@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')
@section('content')
@php
$playlist = $podcast->episodes->map(function($ep) use ($podcast) {
return [
        'src' => $ep->stream_url,
        'title' => $ep->title,
        'podcast' => $podcast->title,
        'thumbnail' => $podcast->thumbnail_url,
        'type' => 'audio', // explicitly set type
    ];
});
@endphp

<!-- main-area -->
<main>

    <!-- movie-details-area -->
    <section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-center position-relative">
                <div class="col-xl-8 col-lg-8">
                    <div class="row mx-0 g-3">

                <div class="col-xl-4 col-lg-4">
                    <div class="movie-details-img">
                        <img src="{{ $podcast->thumbnail_url }}" class="img-fluid" alt="{{ $podcast->title }}">
                       <span class="popup-video" onclick='window.playGlobalAudio(@json($playlist), 0)'>
    <img src="{{ asset('assets/img/images/play_icon.png') }}" alt="Play Podcast">
</span>

 

                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="movie-details-content">
                        <h5>Top podcast</h5>
                        <h2>
                            {{ $podcast->title }}
                        </h2>
                        <div class="banner-meta">
                            <ul>
                                <li class="quality">
                                    <span>{{ $podcast->explicit == 1 ? 'PG 18' : 'GA' }}</span>
                                    <span class="ml-2 btn-primary"> <i class="far fa-eye"></i> {{ $podcast->views }}</span>
                                    <span class="ml-2 btn-primary">{{ $podcast->language ?? 'N/A' }}</span>
                                    <span class="popup-video"
 onclick='playGlobalAudio(@json($playlist), 0)' style="cursor: pointer;">
                            Play All
                                    </span>
                                </li>
                            </ul>
                            <ul>
                                <li class="category">
                                    <a href="#">{{ ucfirst($podcast->author) }}</a>
                                </li>
                                <li class="release-time">

                                    <span><i class="far fa-calendar-alt"></i>
                                        Newest {{ strtoupper(Carbon::parse($podcast->last_edited)->format('d M, Y')) }}
                                    </span>
                                    <span>
                                        <i class="far fa-clock"></i>
                                        Oldest {{ Carbon::parse($podcast->created_at)->format('d M, Y') }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <p class="mb-3 clamp-4">
                            {!! $podcast->description !!}
                        </p>
                        <div class="movie-details-prime d-none">
                            <ul>
                                <li class="share"><a href="#"><i class="fas fa-share-alt"></i> Share</a></li>
                                <li class="streaming">
                                    <h6>Prime Video</h6>
                                    <span>Streaming Channels</span>
                                </li>
                                <li class="watch">
                                    <a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="btn popup-video"><i
                                            class="fas fa-play"></i> Watch Now</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                <!-- <div class="movie-details-btn">
					<a href="{{ asset('assets/img/poster/movie_details_img.jpg') }}" class="download-btn"
						download="">Create podcast <img src="fonts/download.svg" alt=""></a>
				</div> --> 
                    		@include('Frontend.includes.components.partials.video-comments', [
    'comments' => $comments,
    'commentableType' => 'podcast',
    'commentableId' => $podcast->uuid
]) 
            </div>
        </div>
    </section>
    <section class="episode-area episode-bg" data-background="{{ asset('assets/img/bg/episode_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="movie-episode-wrap">
                        <div class="episode-top-wrap">
                            <div class="section-title">
                                <span class="sub-title">ONLINE STREAMING</span>
                                <h2 class="title">Stream Full Episode</h2>
                            </div>
                            <div class="total-views-count">
                                <p>{{ $podcast->views }},125 <i class="far fa-eye"></i></p>
                            </div>
                        </div>
                        <div class="episode-watch-wrap">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <button class="btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            <span class="season">By {{ $podcast->author }}</span>
                                            <span class="video-count">{{ $podcast->episodes_count}} Full Episodes</span>
                                        </button>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                                        <div class="card-body">
                                            <ul>

                                                @foreach($podcast->episodes as $index => $episode)
                                                <li>
                                                    <a href="javascript:void(0)"
                                                        onclick='playGlobalAudio(@json($playlist), {{ $index }})'>
                                                        <i class="fas fa-play"></i>
                                                        {{ $episode->title }}
                                                    </a><span class="duration"> <i class="far fa-clock"></i> {{ $episode->duration ? gmdate("i:s", $episode->duration) : 'Duration not available' }} </span>
                                                </li>
                                                @endforeach


                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Related Podcasts -->
                    <div class="related-podcast">
                        <div class="section-title mb-3"> <span class="sub-title">RELATED</span>
                            <h5 class="season">Related Podcasts</h5>
                        </div>
                        <div class="row">
                            @foreach($related as $relatedPodcast)
                            <div class="col-md-6">
                                <div class="related-podcast-item mb-20">
                                    <div class="related-podcast-img">
                                        <a href="{{ route('podcast.show', ['uuid' => $relatedPodcast->uuid, 'slug' => $relatedPodcast->slug]) }}">
                                            <img src="{{ $relatedPodcast->thumbnail_url }}" alt="{{ $relatedPodcast->title }}" class="img-fluid w-100"> </a>
                                    </div>
                                    <div class="related-podcast-content">
                                        <p class="my-2 text-truncate-2">
                                            <a href="{{ route('podcast.show', ['uuid' => $relatedPodcast->uuid, 'slug' => $relatedPodcast->slug]) }}" class="text-light"> {{ $relatedPodcast->title }} </a>
                                        </p>
                                        <span class="date"><i class="far fa-calendar-alt"></i>
                                            {{ Carbon::parse($relatedPodcast->created_at)->format('d M, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="episode-img">
                        <img src="{{ asset('assets/img/images/episode_img.jpg') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="movie-history-wrap">
                        <h3 class="title">About <span>{{ $podcast->title }}</span></h3>
                        <p>{!! $podcast->description !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@section('header')

	<style>
		.btn-send {
			padding: 5px;
			border: 1px solid #2a2b2c;
		}

		/* Make both columns stretch to same height */
		.tv-comments-row {
			align-items: stretch !important;
		}

		/* comments card takes full height of the column */
		.yt-comments-card {
			height: 100%;
			display: flex;
			flex-direction: column;
		}

		/* scroll area */
		.yt-comments-body {
			flex: 1;
			overflow-y: auto;
		}

		.text-light-50 {
			color: rgba(255, 255, 255, .55) !important;
		}

		.yt-comments-card {
			border: 1px solid rgba(255, 255, 255, .08);
			border-radius: 14px;
			background: rgba(10, 10, 10, 0.55);
			backdrop-filter: blur(14px);
			-webkit-backdrop-filter: blur(14px);
			box-shadow: 0 10px 30px rgba(0, 0, 0, .45);
			overflow: hidden;
		}

		.yt-comments-header,
		.yt-comments-footer {
			background: rgba(0, 0, 0, .35);
			backdrop-filter: blur(10px);
			-webkit-backdrop-filter: blur(10px);
		}

		.yt-comments-body {
			max-height: 520px;
			overflow-y: auto;
		}

		.yt-comments-body::-webkit-scrollbar {
			width: 6px;
		}

		.yt-comments-body::-webkit-scrollbar-thumb {
			background: rgba(255, 255, 255, .15);
			border-radius: 20px;
		}

		.yt-comment-input {
			background: rgba(255, 255, 255, .06) !important;
			border: 1px solid rgba(255, 255, 255, .10) !important;
			color: #fff !important;
			border-radius: 10px 0 0 !important;
			padding: 10px 12px !important;
		}

		.yt-comment-input::placeholder {
			color: rgba(255, 255, 255, .55) !important;
		}

		.yt-actions a {
			color: rgba(255, 255, 255, .55);
			text-decoration: none;
			transition: .2s;
		}

		.yt-actions a:hover {
			color: #fff;
			text-decoration: none;
		}

		#comment-list {
			max-height: 520px;
			overflow-y: auto;
		}

		/* tv wrapper uses 16:9 ratio like YouTube */
		.tv-wrap {
			position: relative;
			width: 100%;
			padding-top: 56.25%;
			/* 16:9 */
			overflow: hidden;
			border-radius: 10px;
		}

		.tv-wrap tv,
		.tv-wrap iframe,
		.tv-wrap .plyr {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}

		/* Comments card height must match tv height */
		@media (min-width: 1200px) {
			#commentsCard {
				height: 100%;
				display: flex;
				flex-direction: column;
			}

			/* Scroll only the comment list */
			#comment-list {
				flex: 1 1 auto;
				overflow-y: auto;
				min-height: 0;
			}
		}

		/* Dark translucent */
		.yt-comments-card {
			background: rgba(0, 0, 0, .55);
			backdrop-filter: blur(10px);
			border: 1px solid rgba(255, 255, 255, .08);
		}

		.sticky {
			z-index: 99;
		}
		.live-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: red;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    z-index: 10;
}

	</style>
@endsection