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
						<img src="{{ $event->event_image }}" class="w-100" alt="{{ $event->event_name }}">
						<a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video">
							<img src="{{ asset('assets/img/images/play_icon.png') }}" alt="">
						</a>
					</div>
				</div>
				<div class="col-xl-6 col-lg-8">
					<div class="movie-details-content">
						<h5>Top Event</h5>
						<h2>  
							{{ $event->event_name }} <span>Live</span>
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
							{{ $event->description }}
						</p>
						<div class="card-body p-0 row px-md-3">
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
										$currency = $country == 'KE' ? 'KES ' . $rate->cost : config('custom.BILLING.RESERVED_CURRENCY') . " " . $rate->reserved_currency_cost;
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
												href="{{ route('event.pay', ['eventId' => $event->id, 'rate_id' => $rate->id]) }}">
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
	<section class="episode-area episode-bg" data-background="{{ asset('assets/img/bg/episode_bg.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="movie-episode-wrap">
                                <div class="episode-top-wrap">
                                    <div class="section-title">
                                        <span class="sub-title">ONLINE STREAMING</span>
                                        <h2 class="title">Watch Full Episode</h2>
                                    </div>
                                    <div class="total-views-count">
                                        <p>2.7 million <i class="far fa-eye"></i></p>
                                    </div>
                                </div>
                                <div class="episode-watch-wrap">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <button class="btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                    <span class="season">Season 2</span>
                                                    <span class="video-count">5 Full Episodes</span>
                                                </button>
                                            </div>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                                                <div class="card-body">
                                                    <ul>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 1 - The World Is Purple</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 2 - Meaner Than Evil</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 3 - I Killed a Man Today</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 4 - Cowboys and Dreamers</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 5 - Freight Trains and Monsters</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <button class="btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    <span class="season">Season 1</span>
                                                    <span class="video-count">5 Full Episodes</span>
                                                </button>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample" style="">
                                                <div class="card-body">
                                                    <ul>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 1 - The World Is Purple</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span>
                                                        </li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 2 - Meaner Than Evil</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 3 - I Killed a Man Today</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span>
                                                        </li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 4 - Cowboys and Dreamers</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span>
                                                        </li>
                                                        <li><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"><i class="fas fa-play"></i> Episode 5 - Freight Trains and Monsters</a> <span class="duration"><i class="far fa-clock"></i> 28 Min</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="episode-img">
                                <img src="{{ asset('assets/img/images/episode_img.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="movie-history-wrap">
                                <h3 class="title">About <span>History</span></h3>
                                <p>{!! $event->description !!}</p>
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