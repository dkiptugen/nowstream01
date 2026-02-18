@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')

<main>

	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
		<div class="container custom-container">
			<div class="row align-items-center position-relative g-0">
				<div class="col-xl-9 col-lg-8">
					<div id="videoWrap" class="tv-wrap">  
  <video
        id="player"
        data-stream="https://tv.a21network.ru/stream/37909/index.m3u8"
        playsinline
        controls
        poster="{{ $tv->thumbnail_url }}">
    </video>
    <div class="live-badge" style="background: transparent"><img src="{{ asset('assets/img/logo/logo.png') }}" height="20"></div>
</div>

 
 
				</div>
				@include('Frontend.includes.components.partials.video-comments', [
    'comments' => $comments,
    'commentableType' => 'tv',
    'commentableId' => $tv->uuid
])

				<div class="col-xl-7 col-lg-8 mt-4">
					<div class="movie-details-content">
						<h5>New Episodes</h5>
						@php
						$words = preg_split('/\s+/', trim(ucfirst($tv->title)));
						$half = (int) ceil(count($words) / 2);

						$firstHalf = implode(' ', array_slice($words, 0, $half));
						$secondHalf = implode(' ', array_slice($words, $half));
						@endphp

						<h2>
							{{ $firstHalf }}
							<span>{{ $secondHalf }}</span>
						</h2>

						<div class="banner-meta">
							<ul>
								<li class="quality">
									<span>Pg 18</span>
									<span>hd</span>
								</li>
								<li class="category"> 
    @foreach($tv->categories as $category)
        <a href="{{ route('genre.show', $category->slug) }}">
            {{ $category->name }}@if(!$loop->last),@endif
        </a>
    @endforeach
</li>

								<li class="release-time">
									<span><i class="far fa-calendar-alt"></i> 2021</span>
									<span><i class="far fa-clock"></i> 128 min</span>
								</li>
							</ul>
						</div>
						<p>{{ $tv->description }}</p>
						<div class="movie-details-prime">
							<ul>
								<li class="share"><a href="#"><i class="fas fa-share-alt"></i> Share</a></li>
								<li class="streaming">
									<h6>Prime tv</h6>
									<span>Streaming Channels</span>
								</li> 
							</ul>
						</div>
					</div>
				</div> 
			</div>
		</div>
	</section>
	<section class="d-none">
		<div class="row">
			<div class="col-12 col-lg-8">
				<div class="card radius-5 row mx-md-0">
 
					<div class="card-body">
						<h2 class="mb-0">
							{{$tv->title}}
						</h2>
						<p class="text-danger mb-0 mt-1">Entertainment</p>
						<small class="text-muted"><i class="lni lni-eye"></i> 1.9M Views <i
								class="lni lni-calendar"></i>
							Started Streaming 12min ago </small>
					</div>
				</div>
				<div class="card radius-5 single-tv-author box mb-3">
					<div class="">
						<div class="float-right d-flex align-items-center">

							@if(Auth::check())
							<div id="favorite-btn">
								@php
								$favorites = Auth::user()->favoritetvs ?? collect();
								@endphp

								@if($favorites->contains('uuid', $tv->uuid))
								<button class="btn btn-danger btn-sm"
									onclick="toggleFavorite('{{ $tv->uuid }}', false)">
									Unlike tv
								</button>
								@else
								<button class="btn btn-outline-primary btn-sm"
									onclick="toggleFavorite('{{ $tv->uuid }}', true)">
									Like tv
								</button>
								@endif
							</div>
							@endif

							<div class="mx-1">.</div>



						</div>

						<p><a href="#"><strong>


								</strong></a> <span title="" data-placement="top" data-toggle="tooltip"
								data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span>
						</p>
						<small>Started Streaming 12min ago</small>
					</div>
					<div class="card radius-5 box mb-3">
						<div class="card-body">
							<h6>Event:</h6>
							@php
							$event = \App\Models\Event::find($tv->event_id);
							@endphp
							<p>
								{{ $event ? $event->title : 'Unknown' }}
							</p>
							{{-- <h6>Hosts:</h6>
							<p>Nathan Drake , Victor Sullivan , Sam Drake , Elena Fisher</p>
							<h6>Performances:</h6>
							<p> Drake , Kelele Takatifu , Khaligraph Jones , Mejja</p>
							<h6>Category :</h6>
							<p>Gospel , VVIP Exclusive , Gameplay , 1080p</p>
							<h6>About :</h6> --}}
							<p>
								{!!$tv->description!!}
							</p>
							{{-- <h6>Tags :</h6>
							<p class="tags mb-0">
								<span><a href="#">Music</a></span>
								<span><a href="#">Live Concert</a></span>
								<span><a href="#">Kenyan Local</a></span>
								<span><a href="#">1080P</a></span>
								<span><a href="#">Genge</a></span>
								<span><a href="#">+ 16</a></span>
							</p> --}}
						</div>
					</div>
				</div>

			</div>
	</section>
@if($related->isNotEmpty())
		<section class="movie-area movie-bg" data-background="{{ asset('assets/img')}}/bg/movie_bg.jpg">
		    <div class="container">
                <div class="episode-top-wrap">
                    <div class="section-title"> <span class="sub-title">Related Tvs</span>
                        <h2 class="title">Trending Tvs</h2>
                    </div>
                </div>
            </div>

            <div class="pcar-wrapper">

                <!-- Outside container overlays -->
                <div class="pcar-overlay pcar-overlay-left"></div>
                <div class="pcar-overlay pcar-overlay-right"></div>

                <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="11" data-tablet="3"
                    data-mobile="1">

                    <div class="pcar-track">
                        @foreach($related as $item)
                            <div class="pcar-item">
                                @include('Frontend.includes.components.cards.slider-card')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
	</section>
		@endif

	@endsection
	@section('header')
	<style>
		@media (min-width: 769px) {
			.page-footer {
				display: none;
			}
		}

		@media (max-width: 768px) {
			.page-wrapper {
				margin-top: 30px !important;
			}

			.comment-top {
				height: 40vh;
				overflow-y: scroll;
			}

			.comment-top {
				max-height: 30vh;
				overflow-y: scroll;
				height: auto;
			}
		}

		.plyr--tv {
			padding: 0;
		}

		#my-tv {
			width: 100%;
			aspect-ratio: 16 / 9 !important;
			height: auto;
		}

		#player {
			transition: all 0.3s ease-in-out;
		}

		#player.sticky {
			position: fixed;
			bottom: 0;
			right: 0;
			width: 400px;
			z-index: 9999;
			height: max-content;
		}
	</style>

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
	@section('footer')
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const video = document.getElementById('player');
    if (!video) return;

    const streamUrl = video.dataset.stream;
    if (!streamUrl) return;

    const player = new Plyr(video, {});

    let hls = null;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 10;
    const reconnectDelay = 3000;

    function getMediaType(url) {
        const ext = url.split('.').pop().toLowerCase();
        if (ext === 'm3u8') return 'hls';
        if (ext === 'mp4') return 'video/mp4';
        return '';
    }

    function autoplayWithSound() {
        video.muted = false;
        video.volume = 1;

        video.play().catch(() => {
            // Browser blocked autoplay with sound
            video.muted = true;
            video.play().catch(() => {});
        });
    }

    function destroyHls() {
        if (hls) {
            hls.destroy();
            hls = null;
        }
    }

    function reconnectStream() {
        if (reconnectAttempts >= maxReconnectAttempts) {
            console.error('Max reconnect attempts reached');
            return;
        }

        reconnectAttempts++;
        console.log('Reconnecting stream...', reconnectAttempts);

        setTimeout(() => {
            loadStream(streamUrl);
        }, reconnectDelay);
    }

    function loadStream(url) {
        const type = getMediaType(url);

        // Reset video
        video.pause();
        video.removeAttribute('src');
        video.load();

        destroyHls();

        if (type === 'hls') {

            if (Hls.isSupported()) {

                hls = new Hls({
                    maxBufferLength: 30,
                    maxMaxBufferLength: 60,
                    enableWorker: true,
                    lowLatencyMode: true
                });

                hls.loadSource(url);
                hls.attachMedia(video);

                hls.on(Hls.Events.MANIFEST_PARSED, function () {
                    reconnectAttempts = 0;
                    autoplayWithSound();
                });

                hls.on(Hls.Events.ERROR, function (event, data) {

                    if (data.fatal) {
                        console.error('HLS fatal error:', data.type);

                        switch (data.type) {
                            case Hls.ErrorTypes.NETWORK_ERROR:
                                reconnectStream();
                                break;

                            case Hls.ErrorTypes.MEDIA_ERROR:
                                hls.recoverMediaError();
                                break;

                            default:
                                reconnectStream();
                                break;
                        }
                    }
                });

            }
            else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = url;
                video.addEventListener('loadedmetadata', autoplayWithSound, { once: true });
            }

        } else {
            video.src = url;
            video.addEventListener('loadedmetadata', autoplayWithSound, { once: true });
        }
    }

    /* ===============================
       Network Recovery
    =============================== */
    window.addEventListener('offline', function () {
        console.warn('Network lost');
    });

    window.addEventListener('online', function () {
        console.log('Network restored — reconnecting');
        reconnectAttempts = 0;
        loadStream(streamUrl);
    });

    /* ===============================
       Visibility Optimization
    =============================== */
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden && video.paused) {
            video.play().catch(() => {});
        }
    });

    /* ===============================
       Start
    =============================== */
    loadStream(streamUrl);

});
</script>


	<script>
		$(document).ready(function() {

			function scrollCommentsToBottom() {
				let list = $('#comment-list');
				if (!list.length) return;
				list.scrollTop(list[0].scrollHeight);
			}

			// scroll on load
			scrollCommentsToBottom();

			// submit comment
			$('#comment-form').on('submit', function(e) {
				e.preventDefault();

				let form = $(this);
				let url = form.attr('action');
				let btn = $('#comment-submit-btn');

				let commentInput = $('#comment-input');
				let commentText = commentInput.val().trim();

				if (!commentText) return;

				btn.prop('disabled', true);

				$.ajax({
					url: url,
					type: "POST",
					data: form.serialize(),
					success: function(res) {

						// if you use broadcast()->toOthers() then we must append manually for sender
						let name = "{{ auth()->check() ? auth()->user()->name : '' }}";
						let avatar = "{{ auth()->check() ? (auth()->user()->image ?? asset('avatar.png')) : asset('avatar.png') }}";

						let safeText = $('<div>').text(commentText).html();

						let newComment = `
                    <div class="media py-3 border-bottom border-dark">
                        <img src="${avatar}" class="mr-3 rounded-circle"
                             style="width:42px;height:42px;object-fit:cover;" alt="avatar">

                        <div class="media-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap">
                                    <strong class="mr-2 text-white" style="font-size: 14px;">
                                        ${name}
                                    </strong>
                                    <small class="text-light-50" style="font-size: 12px;">
                                        just now
                                    </small>
                                </div>
                            </div>

                            <div class="mt-1 text-light" style="font-size: 14px; line-height: 1.4;">
                                ${safeText}
                            </div>

                            <div class="mt-2 d-flex align-items-center yt-actions" style="font-size: 13px;">
                                <a href="javascript:void(0)" class="mr-3"><i class="fa fa-thumbs-up"></i> Like</a>
                                <a href="javascript:void(0)" class="mr-3"><i class="fa fa-thumbs-down"></i> Dislike</a>
                                <a href="javascript:void(0)"><i class="fa fa-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                `;

						// IMPORTANT: append to bottom (latest at bottom)
						$('#commentlist').append(newComment);

						// update count
						let countEl = $('#comment-count');
						countEl.text(parseInt(countEl.text() || 0) + 1);

						// clear input
						commentInput.val('');

						// scroll bottom
						scrollCommentsToBottom();
					},
					error: function() {
						alert('Failed to post comment. Please try again.');
					},
					complete: function() {
						btn.prop('disabled', false);
					}
				});
			});

		});
	</script>

	<!-- <script>
			document.addEventListener('DOMContentLoaded', function () {
				const player = document.getElementById('player');
				const playerOffsetTop = player.offsetTop;

				window.addEventListener('scroll', function () {
					if (window.scrollY > playerOffsetTop) {
						player.classList.add('sticky');
					} else {
						player.classList.remove('sticky');
					}
				});
			});
		</script> -->

	<script>
		function syncCommentsHeight() {
			if (window.innerWidth < 1200) return;

			let videoWrap = document.getElementById('videoWrap');
			let commentsCard = document.getElementById('commentsCard');

			if (!videoWrap || !commentsCard) return;

			commentsCard.style.height = videoWrap.offsetHeight + "px";
		}

		$(document).ready(function() {
			syncCommentsHeight();
			$(window).on('resize', syncCommentsHeight);

			// delay to allow Plyr render
			setTimeout(syncCommentsHeight, 300);
			setTimeout(syncCommentsHeight, 1000);
		});
	</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainVideo = document.getElementById('player');
    if (!mainVideo) return;

    const stream = mainVideo.dataset.stream;

    if (stream && stream.includes('.m3u8')) {
        if (window.Hls && Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(stream);
            hls.attachMedia(mainVideo);
        } 
        // Safari (native HLS)
        else if (mainVideo.canPlayType('application/vnd.apple.mpegurl')) {
            mainVideo.src = stream;
        }
    }
});
</script>

	@endsection