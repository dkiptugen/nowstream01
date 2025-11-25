@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<section>
			<div class="row">
				<div class="col-12 col-lg-8">
					<div class="card radius-5 row mx-md-0">

						

                        <!-- Responsive video container -->
                        <div class="embed-responsive embed-responsive-16by9" style="position: relative;">
                            <video id="player" class="embed-responsive-item"  playsinline data-poster="{{ $video->thumbnail }}" controls crossorigin></video>
                            <!-- IMA Ad overlay -->
                            <div id="ad-container" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:10;"></div>
                        </div>
                        <!-- Companion ad container -->
                        <div id="companion-container" class="mt-3 mx-auto" style="max-width: 300px; height: 250px;"></div>
                        <!-- Ad container -->
                        <div id="ad-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;"></div>
                        <!-- Companion ad container -->
                        <div id="companion-container"></div>
						@php
						     $oldvid= $video;
							$vid = $video->id;
						@endphp
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
											success: function (response) {
												if (isFavorite) {
													$('#favorite-btn').html('
											<button class="btn btn-danger btn-sm" onclick="toggleFavorite(${videoId}, false)">
											Unlike Video
											</button>
										');
												} else {
													$('#favorite-btn').html('
											<button class="btn btn-outline-primary btn-sm" onclick="toggleFavorite(${videoId}, true)">
												Like
											</button>
										');
												}
											},
											error: function (xhr) {
												console.error('Error:', xhr.responseText);
											}
										});
									}
								</script>


								@php
									$channel = Channel::find($video->channel_id);
								@endphp
								@if(Auth::check())
										<div id="subscription-controls-{{ $channel->id }}">
											@if(Auth::user()->subscribedChannels->contains($channel->id))
												<div id="subscribe-btn-{{ $channel->id }}">
													<button class="btn btn-danger btn-sm"
														onclick="toggleSubscription({{ $channel->id }}, false)">Unsubscribe</button>
												</div>
											@else
												<div id="subscribe-btn-{{ $channel->id }}">
													<button class="btn btn-outline-danger btn-sm"
														onclick="toggleSubscription({{ $channel->id }}, true)">Subscribe</button>
												</div>
											@endif
										</div>
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
				@include('Frontend.includes.components.partials.video-comments')
			</div>
		</section>
		@if($relatedVideos->isNotEmpty())
			<section>
				<h5 class="mt-4 section-title mb-3">Continue Watching</h5>
				<div class="row">
					@foreach ($relatedVideos as $video)
						<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-3">
							@include('Frontend.includes.components.cards.video-card')
						</div>
					@endforeach
				</div>
				<!--end row-->
			</section>
		@endif
		@if($channels->isNotEmpty())
			<section>
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
		@section("header")
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
                width: 100%;
			}

			#my-video {
				width: 100%;
				aspect-ratio: 16 / 9 !important;
				height: auto;
			}

			#player {
				transition: all 0.3s ease-in-out;
                width: 100%;
			}

			#player.sticky {
                position: fixed;
                bottom: 0;
                right: 0;
                width: 400px;
                height: auto;
                z-index: 9999;
			}
            #companion-container {
                width: 100%;
                max-width: 300px;
                height: auto;
            }
		</style>

		@endsection
		@section("footer")
            <script src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
		<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>


		<script>
            document.addEventListener('DOMContentLoaded', () => {
                const video = document.getElementById('player');
                const player = new Plyr(video, {});

                const adContainer = document.getElementById('ad-container');
                const companionContainer = document.getElementById('companion-container');

                const displayContainer = new google.ima.AdDisplayContainer(adContainer, player.media);
                const adsLoader = new google.ima.AdsLoader(displayContainer);

                // Handle adsManager loaded
                adsLoader.addEventListener(
                    google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED,
                    (event) => {
                        const adsManager = event.getAdsManager(player.media);

                        // Pause/resume content events
                        adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED, () => player.pause());
                        adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED, () => player.play());

                        // Handle ad errors
                        adsManager.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR, (err) => {
                            console.error('AdsManager error', err);
                            player.play();
                        });

                        try {
                            adsManager.init(player.media.clientWidth, player.media.clientHeight, google.ima.ViewMode.NORMAL);
                            adsManager.start();
                        } catch (err) {
                            console.error('AdsManager start error', err);
                            player.play();
                        }
                    },
                    false
                );

                adsLoader.addEventListener(
                    google.ima.AdErrorEvent.Type.AD_ERROR,
                    (err) => {
                        console.error('AdsLoader error', err);
                        player.play();
                    },
                    false
                );

                // Function to generate dynamic VAST tag
                function generateAdTag(position) {
                    @php
                        $station = 'radio47'; // or dynamic value
                        $videoId = $video->id;
                        $pageUrl = urlencode(url()->current()); // Current page URL
                        $cmsId = '12345'; // Replace with your GAM content source ID
                        $adType = 'audio_video'; // could be 'audio', 'video', or 'audio_video'
                    @endphp

                    @php
                        $vastTag = "https://pubads.g.doubleclick.net/gampad/ads?";
                        $vastTag .= "iu=/22646621568/AudioVideoAdUnit";
                        $vastTag .= "&description_url={$pageUrl}";
                        $vastTag .= "&tfcd=1&npa=1";
                        $vastTag .= "&ad_type={$adType}";
                        $vastTag .= "&sz=640x480";
                        $vastTag .= "&ciu_szs=fluid";
                        $vastTag .= "&cmsid={$cmsId}";
                        $vastTag .= "&vid={$videoId}";
                        $vastTag .= "&gdfp_req=1";
                        $vastTag .= "&unviewed_position_start=1";
                        $vastTag .= "&output=vast";
                        $vastTag .= "&env=vp&impl=s";
                        $vastTag .= "&correlator=" . time(); // optional: use timestamp for uniqueness
                    @endphp
                    return "{{ $vastTag }}";
                }

                function requestAds(position) {
                    const adsRequest = new google.ima.AdsRequest();
                    adsRequest.adTagUrl = generateAdTag(position);

                    const rect = video.getBoundingClientRect();
                    adsRequest.linearAdSlotWidth = rect.width;
                    adsRequest.linearAdSlotHeight = rect.height;
                    adsRequest.nonLinearAdSlotWidth = rect.width;
                    adsRequest.nonLinearAdSlotHeight = rect.height / 3;

                    adsRequest.setAdWillAutoPlay(true);
                    adsRequest.setAdWillPlayMuted(false);

                    adsLoader.requestAds(adsRequest);
                }

// Update ad overlay size when window resizes
                window.addEventListener('resize', () => {
                    const rect = video.getBoundingClientRect();
                    adContainer.style.width = rect.width + 'px';
                    adContainer.style.height = rect.height + 'px';
                });

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
                        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                            video.src = url;
                            video.addEventListener('loadedmetadata', () => video.play());
                        } else console.error('HLS not supported');
                    } else if (video.canPlayType(type)) {
                        video.src = url;
                        video.addEventListener('loadedmetadata', () => video.play());
                    } else console.error('Unsupported media type');
                }

                // Example video/live URL
                const mediaUrl = '{{ $oldvid->video_path }}'; // Replace with dynamic URL

                // Initialize IMA + pre-roll on first play
                player.on('play', () => {
                    displayContainer.initialize();
                    requestAds('pre');
                });

                loadMedia(mediaUrl);

                // Optional: mid-roll for VOD every 60s/300s
                const isLive = mediaUrl.endsWith('.m3u8'); // simplistic live detection
                if (!isLive) {
                    const cuePoints = [60, 300]; // seconds
                    player.on('timeupdate', () => {
                        const currentTime = Math.floor(player.currentTime);
                        if (cuePoints.includes(currentTime) && !player.adsPlaying) {
                            requestAds('mid');
                        }
                    });
                } else {
                    // Live mid-roll example: every 15 min
                    setInterval(() => {
                        requestAds('mid');
                    }, 15 * 60 * 1000);
                }
            });
		</script>
		<script>
			$(document).ready(function () {
				$(document).on('submit', '#comment-form', function (e) {
					e.preventDefault();
					var frm = $(this);
					var formData = new FormData(this);  // Use FormData to handle file uploads
					$.ajax({
						type: 'POST',
						url: frm.attr('action'),
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						data: formData,
						contentType: false,  // Prevent jQuery from setting the Content-Type
						processData: false,
						success: function (Mess) {
							frm.trigger('reset');
						},
						error: function (xhr, status, errorThrown) {
							console.log(errorThrown);
						}
					});
				});
			});

			Pusher.logToConsole = true;
			var pusher = new Pusher("cfc4e18a5372052374ee", {
				cluster: 'mt1',
				encrypted: true,
				authEndpoint: '/pusher/auth',
			});

			var channel = pusher.subscribe('video_comment.{{$vid}}');
			channel.bind('new_comment', function (data) {
				console.log(data);
				var comment = '<div class="card-body d-flex py-2 border-top px-2 mx-0 w-100 justify-content-between comment-item">' +
					'<div class="d-flex">' +
					' <div class="align-self-center text-center">' +
					' <img src="' + data.user_img + '" height="50" class="w-100 d-block w-100 aspect1" alt="">' +
					'</div>' +
					'<div class="mx-1 mx-md-2">' +
					'<div class="media-body">' +
					'<h6 class="my-0">' +
					data.user_name +
					' </h6>' +
					'<p class="mb-0">' +
					data.comment +
					'</p>' +
					'</div>' +
					' </div>' +
					'</div>' +
					' <small class="text-muted float-end time-comm">' +
					data.comment_time +
					' </small>' +
					'</div>';
				$('#commentlist').append(comment);
				const commentTop = document.querySelector('.comment-top');
				commentTop.scrollTop = commentTop.scrollHeight;

			});
		</script>
		<script>
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
		</script>

		@endsection