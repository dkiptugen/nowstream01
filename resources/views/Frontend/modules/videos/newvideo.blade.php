@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<section>
			<div class="row">
				<div class="col-12 col-lg-8">
					<div class="card radius-5">

						<video id="player" playsinline controls data-poster="{!! $video->image_path !!}">
							<source src="{!! url($video->video_path) !!}" type="video/mp4" />
						</video>
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
							<div class="float-end d-flex align-items-center">

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
								</strong></a> <span title="" data-bs-placement="top" data-bs-toggle="tooltip"
								data-bs-title="Verified"><i class="fas fa-check-circle text-success"></i></span>
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
							<h6>About :</h6>
							<p>
								{!!$video->description!!}
							</p>
							<h6>Tags :</h6>
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
						<div class="col-12 col-lg-2 me-3 mb-3">
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
			/*@media (min-width:769px) {*/
			/*	.page-footer {*/
			/*		position: relative;*/
			/*	}*/
			/*}*/

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

			#my-video {
				width: 100%;
				aspect-ratio: 16 / 9 !important;
				height: auto;
			}
		</style>
		@endsection
		@section('footer')
		<script>
			$(document).ready(function () {
				// Function to fetch and update comments
				function fetchAndUpdateComments() {
					const commentTop = $('.comment-top');
					const isScrolledToBottom = commentTop[0].scrollHeight - commentTop.outerHeight() <= commentTop.scrollTop() + 1;

					// Fetch and update comments
					$.ajax({
						url: "{{ route('comment.fetch', ['commentableType' => 'video', 'commentableId' => $video->id]) }}",
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
		</script>

		@endsection
