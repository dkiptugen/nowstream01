@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')

<main>

	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
		<div class="container custom-container">
			<div class="row align-items-center position-relative g-0">
				<div class="col-xl-9 col-lg-8">
					<div id="videoWrap" class="video-wrap">
<video id="player"
       data-src="{{ Storage::url($video->content_path) }}"
       data-title="{{ $video->title }}"
       data-thumb="{{ $video->thumbnail_url }}">
</video>

					 <div class="live-badge" style="background: transparent"><img src="{{ asset('assets/img/logo/logo.png') }}" height="20"></div>
</div>

					 
				</div>
				@include('Frontend.includes.components.partials.video-comments', [
    'comments' => $comments,
    'commentableType' => 'video',
    'commentableId' => $video->uuid
])

				<div class="col-xl-7 col-lg-8 mt-4">
					<div class="movie-details-content">
						<h5>New Episodes</h5>
						@php
						$words = preg_split('/\s+/', trim(ucfirst($video->title)));
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
									<a href="#">Romance,</a>
									<a href="#">Drama</a>
								</li>
								<li class="release-time">
									<span><i class="far fa-calendar-alt"></i> 2021</span>
									<span><i class="far fa-clock"></i> 128 min</span>
								</li>
							</ul>
						</div>
						<p>{{ $video->description }}</p>
						<div class="movie-details-prime">
							<ul>
								<li class="share"><a href="#"><i class="fas fa-share-alt"></i> Share</a></li>
								<li class="streaming">
									<h6>Prime Video</h6>
									<span>Streaming Channels</span>
								</li>
								<li class="watch"><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="btn popup-video"><i class="fas fa-play"></i> Watch Now</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="movie-details-btn">
					<a href="img/poster/movie_details_img.jpg" class="download-btn" download="">Download <img src="fonts/download.svg" alt=""></a>
				</div>
			</div>
		</div>
	</section>
	<section class="d-none">
		<div class="row">
			<div class="col-12 col-lg-8">
				<div class="card radius-5 row mx-md-0">

					<video id="player" controls playsinline data-poster="{{ $video->thumbnail }}"></video>
 
					<div class="card-body">
						<h2 class="mb-0">
							{{$video->title}}
						</h2>
						<p class="text-danger mb-0 mt-1">Entertainment</p>
						<small class="text-muted"><i class="lni lni-eye"></i> 1.9M Views <i
								class="lni lni-calendar"></i>
							Started Streaming 12min ago </small>
					</div>
				</div>
				<div class="card radius-5 single-video-author box mb-3">
					<div class="">
						<div class="float-right d-flex align-items-center">

							@if(Auth::check())
							<div id="favorite-btn">
								@if(Auth::user()->favoriteVideos->contains($video->id))
								<button class="btn btn-danger btn-sm"
									onclick="toggleFavorite({{ $video->id }}, false)">
									Unlike Video
								</button>
								@else
								<button class="btn btn-outline-primary btn-sm"
									onclick="toggleFavorite({{ $video->id }}, true)">
									Like Video
								</button>
								@endif
							</div>
							@endif
							<div class="mx-1">.</div>

							<script>
								function toggleFavorite(videoId, isFavorite) {
									const url = isFavorite ?
										'{{ route("video.favorite", ":id") }}'.replace(':id', videoId) :
										'{{ route("video.unfavorite", ":id") }}'.replace(':id', videoId);

									$.ajax({
										url: url,
										type: 'POST',
										data: {
											_token: '{{ csrf_token() }}',
										},
										success: function(response) {
											if (isFavorite) {
												$('#favorite-btn').html(`
											<button class="btn btn-danger btn-sm" onclick="toggleFavorite(${videoId}, false)">
											Unlike Video
											</button>
										`);
											} else {
												$('#favorite-btn').html(`
											<button class="btn btn-outline-primary btn-sm" onclick="toggleFavorite(${videoId}, true)">
												Like
											</button>
										`);
											}
										},
										error: function(xhr) {
											console.error('Error:', xhr.responseText);
										}
									});
								}
							</script>


							@php
							$channel = Channel::find($video->channel_id);
							$user = auth()->user();
							$isSubscribed = $user ? $user->subscribedChannels->contains($channel->id) : false;
							@endphp

							@if($user)
							<div id="subscription-controls-{{ $channel->id }}">
								<button
									class="btn btn-sm {{ $isSubscribed ? 'btn-danger' : 'btn-outline-primary' }}"
									onclick="toggleSubscription({{ $channel->id }}, {{ $isSubscribed ? 'false' : 'true' }})">
									{{ $isSubscribed ? 'Unsubscribe' : 'Subscribe' }}
								</button>
							</div>
 
							@endif

						</div>
						<img class="ratio1" src="{{ $channel ? $channel->thumbnail : 'Unknown' }}" alt="">
						<p><a href="#"><strong>

									{{ $channel ? $channel->name : 'Unknown' }}
								</strong></a> <span title="" data-placement="top" data-toggle="tooltip"
								data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span>
						</p>
						<small>Started Streaming 12min ago</small>
					</div>
					<div class="card radius-5 box mb-3">
						<div class="card-body">
							<h6>Event:</h6>
							@php
							$event = \App\Models\Event::find($video->event_id);
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
								{!!$video->description!!}
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
	@if($relatedVideos->isNotEmpty())
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
						<h2 class="title">Continue <span>Watching</span></h2>
					</div>
				</div>
				<div class="col-lg-6">
				</div>
			</div>
			<div class="row tr-movie-active">
				@foreach($relatedVideos as $video)
				@include('Frontend.includes.components.cards.video-card')
				@endforeach
			</div>
		</div>
	</section>
	@endif


	@if($channels->isNotEmpty())
	<section class="d-none">
		<h5 class="mb-3">Popular Channels</h5>
		<div class="d-flex scrolling">
			@foreach ($channels as $channel)
			<div class="col-6 col-lg-2 me-3 mb-3">
				@include('Frontend.includes.components.cards.channels')
			</div>

			@endforeach
		</div>
		<!--end row-->
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

		.plyr--video {
			padding: 0;
		}

		#my-video {
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
		.video-comments-row {
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

		/* Video wrapper uses 16:9 ratio like YouTube */
		.video-wrap {
			position: relative;
			width: 100%;
			padding-top: 56.25%;
			/* 16:9 */
			overflow: hidden;
			border-radius: 10px;
		}

		.video-wrap video,
		.video-wrap iframe,
		.video-wrap .plyr {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}

		/* Comments card height must match video height */
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
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('player');
            const player = new Plyr(video, {});
            // Determine media type
            function getMediaType(url) {
                const ext = url.split('.').pop().toLowerCase();
                if (ext === 'm3u8') return 'hls';
                if (ext === 'mp4') return 'video/mp4';
                if (ext === 'mp3') return 'audio/mp3';
                if (ext === 'mov') return 'video/quicktime';
                return '';
            }

            // Load media dynamically
            function loadMedia(url) {
                const type = getMediaType(url);

                if (type === 'hls') {
                    if (Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(url);
                        hls.attachMedia(video);
                        hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());
                    }
                    else if (video.canPlayType('application/vnd.apple.mpegurl'))
                    {
                        video.src = url;
                        video.addEventListener('loadedmetadata', () => video.play());
                    }
                    else console.error('HLS not supported');
                }
                else if (video.canPlayType(type))
                {
                    video.src = url;
                    video.addEventListener('loadedmetadata', () => video.play());
                }
                else console.error('Unsupported media type');
            }

            // Example video/live URL
            const mediaUrl = '{{ Storage::url($video->content_path) }}'; // Replace with dynamic URL

            loadMedia(mediaUrl);


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


	@endsection