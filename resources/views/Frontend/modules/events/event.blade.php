@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!-- main-area -->
@php
$country = session('country', 'US'); // Default to 'US' if not set
$thumbnail = $event->event_image ? Storage::disk(config('filesystems.default'))->url($event->event_image) : asset('frontend-assets/images/default.png');
$combinedRates = collect($eventRates ?? [])
	->concat(collect($streamRates ?? []))
	->sortBy('price')
	->values();
@endphp
<main>

	<!-- movie-details-area -->
	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
		<div class="container">
			<div class="row align-items-center position-relative">
				<div class="col-xl-4 col-lg-4">
					<div class="movie-details-img">
						<img src="{{ $thumbnail }}" class="img-fluid" alt="{{ $event->event_name }}">
						<a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video">
							<!-- <img src="{{ asset('assets/img/images/play_icon.png') }}" alt=""> -->
						</a>
					</div>
				</div>
				<div class="col-xl-6 col-lg-8">
					<div class="movie-details-content">
						<h5>Top Event</h5>
						<h2>
							{{ $event->event_name }} @if($event->has_livestream) <span class="badge badge-danger">Live</span> @endif
						</h2>
						<div class="banner-meta">
							<ul>
								<li class="quality">
									<span>Pg 18</span>
									<span>hd</span>
								</li>
								<li class="category">
									<a href="#">{{ ucfirst($event->venue) }}</a>
								</li>
								<li class="release-time">
									@php
									$startDate = Carbon::parse($event->start_time);
									$endTime = Carbon::parse($event->end_time);
									@endphp
									<span><i class="far fa-calendar-alt"></i> <b>
											{{ strtoupper($startDate->format('d M, Y')) }}
										</b></span>
									<span>
										<i class="far fa-clock"></i>
										{{ $startDate->format('h:i A') }} -
										{{ $endTime->format('h:i A') }}
									</span>
								</li>
							</ul>
						</div>
						<p class="mb-3">
							{!! $event->description !!}
						</p>
						<div class="card-body p-0 row px-md-3 mt-3">
							<table class="table mb-0 table-striped text-white">
								<thead class="table-dark">
									<tr>
										<th scope="col">Access</th>
										<th scope="col">Sub Total</th>
										<th class="text-end pe-md-3" scope="col">Buy</th>
									</tr>
								</thead>
								<tbody>
									@foreach($combinedRates as $rate) 
									@php
										$isStreamRate = $rate->type === 'content';
									@endphp
									<tr>
										<td class="align-content-center">
											{{ ucfirst($rate->name) }}
											@if($isStreamRate)
												<span class="badge badge-info ms-2">Stream</span>
											@endif
										</td>
										<td class="align-content-center">
											  {{ $rate->currency }} {{ $rate->price }}
										</td>
										<td class="align-content-center text-end">
											@if($isStreamRate)
												@if($eventStream && ($paidStreamOrder || $legacyStreamAccess))
													<a class="btn btn-sm btn-primary p-2 pl-3"
														href="{{ route('stream.show', ['uuid' => $eventStream->uuid, 'slug' => $eventStream->slug]) }}">
														Watch Stream <i class='fas fa-play'></i>
													</a>
												@elseif($event->has_livestream && $eventStream)
													<a class="btn btn-sm btn-success p-2 pl-3"
														href="{{ route('event.pay', ['eventId' => $event->uuid, 'rate_id' => $rate->id]) }}">
														Buy Stream Link <i class='fas fa-link'></i>
													</a>
												@else
													<span class="badge badge-secondary">Stream unavailable</span>
												@endif
											@else
												@if($ticket)
													<a class="btn btn-sm btn-primary p-2 pl-3"
														href="{{ route('ticket.download', ['uuid' => $ticket->uuid]) }}">
														Download Ticket <i class='fas fa-download'></i>
													</a>
												@elseif($paidOrder)
													<a class="btn btn-sm btn-primary p-2 pl-3"
														href="{{ route('event.success', ['eventId' => $event->uuid]) }}">
														View Ticket <i class='fas fa-ticket-alt'></i>
													</a>
												@else
													<a class="btn btn-sm btn-success p-2 pl-3"
														href="{{ route('event.pay', ['eventId' => $event->uuid, 'rate_id' => $rate->id]) }}">
														Buy Ticket <i class='fas fa-link'></i>
													</a>
												@endif
											@endif
										</td>
									</tr>
									@endforeach

								</tbody>
							</table>

						</div>
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
				<!-- <div class="movie-details-btn">
					<a href="{{ asset('assets/img/poster/movie_details_img.jpg') }}" class="download-btn"
						download="">Create Event <img src="fonts/download.svg" alt=""></a>
				</div> -->
			</div>
		</div>
	</section>

	@if(!empty($merchProducts) && $merchProducts->isNotEmpty())
	<section class="top-rated-movie tr-movie-bg pt-5" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
		<div class="container">
			<div class="episode-top-wrap">
				<div class="section-title">
					<span class="sub-title">Event Merchandise</span>
					<h2 class="title">Shop This Event</h2>
				</div>
			</div>
			<div class="row g-4 mt-1">
				@foreach($merchProducts as $product)
					<div class="col-lg-3 col-md-6">
						@include('Frontend.modules.shop.partials.product-card', ['product' => $product])
					</div>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	@if($events->isNotEmpty())

	<section class="top-rated-movie tr-movie-bg pb-0" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
		<div class="container">
			<div class="episode-top-wrap">
				<div class="section-title">
					<span class="sub-title">Related Events</span>
					<h2 class="title">Related Events</h2>
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
					@foreach($events as $event)
					<div class="pcar-item">
						@include('Frontend.includes.components.cards.events')
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</section>
	@endif

	@endsection
	@section('header')
	@endsection
	@section('footer')
	@endsection
