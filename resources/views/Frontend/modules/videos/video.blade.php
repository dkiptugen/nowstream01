@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')

<main>

	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
		<div class="container custom-container">
			<div class="row align-items-center position-relative g-0">
				<div class="col-xl-9 col-lg-8">
					<div id="videoWrap" class="video-wrap">
  <video
                            id="player"
                            playsinline
                            data-poster="{{ $video->thumbnail_url }}">
                        </video>
					</div>

					@php
					$oldvid= $video;
					$vid = $video->id;
					@endphp
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

							<script>
								function toggleSubscription(channelId, subscribe) {
									const url = subscribe ?
										'{{ route("channels.subscribe", ":id") }}'.replace(':id', channelId) :
										'{{ route("channels.unsubscribe", ":id") }}'.replace(':id', channelId);

									$.ajax({
										url: url,
										type: 'POST',
										data: {
											_token: '{{ csrf_token() }}'
										},
										success: function(response) {
											// Toggle the button dynamically
											const btn = $(`#subscription-controls-${channelId} button`);
											if (subscribe) {
												btn.removeClass('btn-outline-primary').addClass('btn-danger');
												btn.text('Unsubscribe');
												btn.attr('onclick', `toggleSubscription(${channelId}, false)`);
											} else {
												btn.removeClass('btn-danger').addClass('btn-outline-primary');
												btn.text('Subscribe');
												btn.attr('onclick', `toggleSubscription(${channelId}, true)`);
											}
										},
										error: function(xhr) {
											console.error('Subscription error:', xhr.responseText);
										}
									});
								}
							</script>
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
	@section('header')
	<script>
$(document).ready(function() {

    // Scroll comments container to bottom
    function scrollCommentsToBottom() {
        const list = $('#commentlist');
        if (!list.length) return;
        list.stop().animate({ scrollTop: list[0].scrollHeight }, 300);
    }

    // Initial scroll on page load
    scrollCommentsToBottom();

    // Handle comment form submission
    $('#comment-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const url = form.attr('action');
        const btn = $('#comment-submit-btn');
        const commentInput = $('#comment-input');
        const commentText = commentInput.val().trim();

        if (!commentText) return;

        btn.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(res) {

                // User data
                const name = "{{ auth()->check() ? auth()->user()->name : 'Guest' }}";
                const avatar = "{{ auth()->check() ? (auth()->user()->image ? asset(auth()->user()->image) : '') : '' }}";

                // If no avatar, show initials
                let avatarHtml = avatar
                    ? `<img src="${avatar}" class="mr-3 rounded-circle" style="width:42px;height:42px;object-fit:cover;" alt="avatar">`
                    : `<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3"
                            style="width:42px;height:42px;font-weight:bold;">
                            {{ auth()->check() ? collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w,0,1)))->join('') : 'G' }}
                       </div>`;

                // Escape comment text
                const safeText = $('<div>').text(commentText).html();

                // Build new comment
                const newComment = `
                    <div class="media py-3 border-bottom border-dark">
                        ${avatarHtml}
                        <div class="media-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap">
                                    <strong class="mr-2 text-white" style="font-size:14px;">${name}</strong>
                                    <small class="text-light-50" style="font-size:12px;">just now</small>
                                </div>
                            </div>
                            <div class="mt-1 text-light" style="font-size:14px; line-height:1.4;">${safeText}</div>
                            <div class="mt-2 d-flex align-items-center yt-actions" style="font-size:13px;">
                                <a href="javascript:void(0)" class="mr-3"><i class="fa fa-thumbs-up"></i> Like</a>
                                <a href="javascript:void(0)" class="mr-3"><i class="fa fa-thumbs-down"></i> Dislike</a>
                                <a href="javascript:void(0)"><i class="fa fa-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                `;

                // Append comment
                $('#commentlist').append(newComment);

                // Update comment count
                const countEl = $('#comment-count');
                countEl.text(parseInt(countEl.text() || 0) + 1);

                // Clear input
                commentInput.val('');

                // Scroll to bottom
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

	@endsection