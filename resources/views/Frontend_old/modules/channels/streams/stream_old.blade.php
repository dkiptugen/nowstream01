@extends('Frontend.includes.layout')
@section('content')
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
<section>
	<div class="d-md-none  row">
		<div class="card radius-5 p-0">
		<video id="player" class="fixed-top" controls crossorigin playsinline>
        <source type="application/x-mpegURL" src="https://5f7ebd8ed1895.streamlock.net/live/livestream_720.stream/playlist.m3u8" />
    </video>
			<div class="card-body">
				<h2 class="mb-0">
					{{$stream->title}}
				</h2>
				<p class="text-danger mb-0 mt-1">Entertainment</p>
				<small class="text-muted">
					<i class="lni lni-eye"></i> {{ $stream->viewers }} Viewers
					<i class="lni lni-calendar"></i>
					Started Streaming
					{{ $stream->created_at->diffForHumans() }}
				</small>
			</div>
			<div class="d-md-none">
				<h5 class="mx-2">Comments</h5>
				@include('Frontend.includes.components.partials.comments')
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-lg-8">
			<div class="card radius-5 d-md-block d-none">
				<!-- <div class="plyr__stream-embed" id="player">
					<iframe
						src="https://www.youtube.com/watch?v=3RA-MR3T0v8?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
						allowfullscreen allowtransparency allow="autoplay" autoplay>
					</iframe>
				</div> -->
				<!-- <video id="player" controls width="640" height="360"></video> -->

				<div class="card-body">
					<h2 class="mb-0">
						{{$stream->title}}
					</h2>
					<p class="text-danger mb-0 mt-1">Entertainment</p>
					<small class="text-muted">
						<i class="lni lni-eye"></i> {{ $stream->viewers }} Viewers
						<i class="lni lni-calendar"></i>
						Started Streaming
						{{ $stream->created_at->diffForHumans() }}
					</small>
				</div>
			</div>
			<div class="card radius-5 single-video-author box mb-3">
				<div class="">
					<div class="float-right d-flex">
						<button class="btn btn-danger btn-sm mx-2" type="button">
							<strong>HD</strong></button>


						@php
							$channel = \App\Models\Channel::find($stream->channel_id);
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
						@endif
						</div>
					</div>
					<img class="ratio1" src="{{ $channel ? $channel->thumbnail : 'Unknown' }}" alt="">
					<p><a href="{{ url("/channel/{$channel->id}/{$channel->name}") }}"><strong>
								{{ $channel ? $channel->name : 'Unknown' }}
							</strong></a> <span title="" data-placement="top" data-toggle="tooltip"
							data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span></p>
					<small>Started Streaming 12min ago</small>
				</div>
			</div>
			<div class="card radius-5 box mb-3">
				<div class="card-body">
					<h6>Event:</h6>
					@php
						$event = \App\Models\Event::find($stream->event_id);
					@endphp
					<p>
						{{ $event ? $event->title : 'Unknown' }}
					</p>
					<h6>Hosts:</h6>
					<p>Nathan Drake , Victor Sullivan , Sam Drake , Elena Fisher</p>
					<h6>Performances:</h6>
					<p> Drake , Kelele Takatifu , Khaligraph Jones , Mejja</p>
					<h6>Category :</h6>
					<p>Gospel , VVIP Exclusive , Gameplay , 1080p</p>
					<h6>About :</h6>
					<p>
						{!!$stream->description!!}
					</p>
					<h6>Tags :</h6>
					<p class="tags mb-0">
						<span><a href="#">Music</a></span>
						<span><a href="#">Live Concert</a></span>
						<span><a href="#">Kenyan Local</a></span>
						<span><a href="#">1080P</a></span>
						<span><a href="#">Genge</a></span>
						<span><a href="#">+ 16</a></span>
					</p>
				</div>
			</div>
		</div>
		<div class="d-md-block d-none col-12 col-lg-4">
			@include('Frontend.includes.components.partials.comments')
		</div>
	</div>
</section>

@if($streams->isNotEmpty())
<section>
	 <h5 class="mt-4 section-title mb-3">Continue Watching</h5>
	<div class="row">
		@foreach ($streams as $stream)
			<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-3">
				@include('Frontend.includes.components.cards.stream-card')
			</div>
		@endforeach
	</div>
	<!--end row-->
</section>
@endif

@if($channels->isNotEmpty())
<section>
	 <h5 class="mt-4 section-title mb-3">Popular Channels</h5>
	<div class="d-flex scrolling">
		@foreach ($channels as $channel)
			<div class="col-12 col-lg-2 me-3">
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
	@media (min-width:769px) {
		.page-footer {
			position: relative;
		}
	}

	@media (max-width:768px) {
		.page-wrapper {
			margin-top: 30px !important;
		}

		.comment-top {
			height: 40vh;
			overflow-y: scroll;
		}

		.comment-top {
			min-height: 30vh;
			overflow-y: scroll;
			height: auto;
		}
	}
</style>
@endsection
@section('footer')  
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/plyr@3.6.12/dist/plyr.polyfilled.min.js"></script>
<script>
	$(document).ready(function () {
		// Function to fetch and update comments
		function fetchAndUpdateComments() {
			const commentTop = $('.comment-top');
			const isScrolledToBottom = commentTop[0].scrollHeight - commentTop.outerHeight() <= commentTop.scrollTop() + 1;

			// Fetch and update comments
			$.ajax({
				url: "{{ route('comment.fetch', ['commentableType' => 'stream', 'commentableId' => $stream->id]) }}",
				method: 'GET',
				success: function (response) {
					$('#comment-list').html(response);

					// Scroll to bottom if the user was previously scrolled to the bottom
					if (isScrolledToBottom) {
						commentTop.scrollTop(commentTop[0].scrollHeight);
					}
				},
				error: function (xhr) {
					console.error('Error fetching comments:', xhr.responseText);
				}
			});
		}

		// Call the function initially and every 3 seconds
		fetchAndUpdateComments();
		setInterval(fetchAndUpdateComments, 3000); // Refresh every 3 seconds

		// Submit comment form
		$('#comment-form').on('submit', function (e) {
			e.preventDefault();

			const formData = $(this).serialize();
			const url = $(this).attr('action');

			$.ajax({
				type: 'POST',
				url: url,
				data: formData,
				success: function (response) {
					if (response.success) {
						// If the comment is successfully posted, fetch comments immediately
						fetchAndUpdateComments();
						$('#comment-form')[0].reset();
					} else {
						console.error('Error:', response.error);
					}
				},
				error: function (xhr) {
					console.error('Error:', xhr.responseText);
				}
			});
		});

		// Continuous update of comments using setInterval
		setInterval(function () {
			$("#commentlist").load(window.location.href + " #commentlist", function () {
				const commentTop = $('.comment-top');
				commentTop.scrollTop(commentTop[0].scrollHeight);
			});
		}, 3000); // Refresh every 3 seconds
	});
	/*$(document).ready(function () {
                       // Function to fetch and update comments
                       function fetchAndUpdateComments() {
                           const commentTop = $('.comment-top');
                           const isScrolledToBottom = commentTop[0].scrollHeight - commentTop.outerHeight() <= commentTop.scrollTop() + 1;

                           // Fetch and update comments
                           $.ajax({
                               url: "{{ route('comment.fetch', ['commentableType' => 'stream', 'commentableId' => $stream->id]) }}",
                        method: 'GET',
                        success: function (response) {
                            $('#comment-list').html(response);

                            // Scroll to bottom if the user was previously scrolled to the bottom
                            if (isScrolledToBottom) {
                                commentTop.scrollTop(commentTop[0].scrollHeight);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error fetching comments:', xhr.responseText);
                        }
                    });
                }

                // Call the function initially and every 3 seconds
                fetchAndUpdateComments();
                setInterval(fetchAndUpdateComments, 3000); // Refresh every 3 seconds

                // Submit comment form
                $('#comment-form').on('submit', function (e) {
                    e.preventDefault();

                    const formData = $(this).serialize();
                    const url = $(this).attr('action');

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: formData,
                        success: function (response) {
                            if (response.success) {
                                // If the comment is successfully posted, fetch comments immediately
                                fetchAndUpdateComments();
                                $('#comment-form')[0].reset();
                            } else {
                                console.error('Error:', response.error);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr.responseText);
                        }
                    });
                });*/

	// Continuous update of comments using setInterval



	// });
</script>

<!-- <script>
	const video = document.getElementById('player');
	const videoSrc = 'https://stream.livestreamz.xyz/hls/CwRLMAbS.m3u8';


	if (Hls.isSupported()) {
		const hls = new Hls();
		hls.loadSource(videoSrc);
		hls.attachMedia(video);
		hls.on(Hls.Events.MANIFEST_PARSED, function () {
			const player = new Plyr(player);
			video.play();
		});
	} else if (video.canPlayType('application/vnd.apple.mpegurl')) {
		video.src = videoSrc;
		const player = new Plyr(player);
		video.addEventListener('loadedmetadata', function () {
			video.play();
		});
	} else {
		console.error('This browser does not support HLS.');
	}
</script> -->

<script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.querySelector('#player');
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(video.querySelector('source').src);
                hls.attachMedia(video);
                window.hls = hls;
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = video.querySelector('source').src;
            }
            const player = new Plyr(video, {
                captions: { active: true, update: true, language: 'en' }
            });
            window.player = player;
        });
    </script>
@endsection