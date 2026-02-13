@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!-- main-area -->
<main>

	<!-- movie-details-area -->
	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
		<div class="container">
			<div class="row align-items-center position-relative">
				<div class="col-xl-4 col-lg-4">
					<div class="movie-details-img">
                <img src="{{ $event->event_image }}" class="img-fluid" alt="{{ $event->event_name }}">
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
										<th scope="col">Ticket</th>
										<th scope="col">Sub Total</th>
										<th class="text-end pe-md-3" scope="col">Buy</th>
									</tr>
								</thead>
								<tbody>
									@foreach($rates as $rate)
									@php
									// Determine currency based on country
									$currency = $country == 'KE'
									? 'KES ' . $rate->price
									: config('custom.BILLING.RESERVED_CURRENCY') . " " . $rate->reserved_currency_cost;
									@endphp
									<tr>
										<td class="align-content-center">
											{{ ucfirst($rate->name) }}
										</td>
										<td class="align-content-center">
											{{ $currency }}
										</td>
										<td class="align-content-center text-end">
											<a class="btn btn-sm btn-success p-2 pl-3"
												href="{{ route('event.pay', ['eventId' => $event->uuid, 'rate_id' => $rate->id]) }}">
												Buy Link <i class='fas fa-link'></i>
											</a>
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
						<h2 class="title">Related <span>Events</span></h2>
					</div>
				</div>
				<div class="col-lg-6">
				</div>
			</div>
			<div class="row tr-movie-active">
				@foreach($events as $event)
				@include('Frontend.includes.components.cards.events')
				@endforeach
			</div>
			<div class="row">
				<div class="col-12">
					<div class="pagination-wrap mt-30">
						<nav>
							<ul>
								<li class="active"><a href="#">1</a></li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#">Next</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>

	@endsection
	@section('header')
	@endsection
	@section('footer')
	@endsection