@extends('Frontend.includes.layout')
@section('content') 
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<div class="">
			<div class="single-channel-image">
				<img class="img-fluid h300" alt src="{{ $channel->cover_image }}">
				<div class="channel-profile">
					<img class="channel-profile-img" alt src="{{ $channel->thumbnail }}">
					<div class="social hidden-xs">
						Social &nbsp;
						<a class="fb" href="#">Facebook</a>
						<a class="tw" href="#">Twitter</a>
						<a class="gp" href="#">Google</a>
					</div>
				</div>

			</div>
			<div class="card shadow-0 px-3">
				<nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-between">
					<b class="channel">
						{{ $channel->name }}
						<span title data-placement="top" data-toggle="tooltip" data-original-title="Verified">
							<i class="fas fa-check-circle text-success"></i>
						</span>
					</b>

					<ul class="navbar-nav">
						<li class="nav-item active">
							<div class="float-right d-flex">
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
						</li>
					</ul>
					<!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto">
					<li class="nav-item active">
						<a class="nav-link" href="#">Videos 
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Playlist</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Channels</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Discussion</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">About</a>
					</li> 
				</ul>
			</div> -->
				</nav>
			</div>
		</div>
		</section>

		@if($videos->isNotEmpty())
			<section>
				<h5 class="mt-4 section-title mb-3">Channel Streams</h5>
				<div class="row">
					@foreach($streams as $stream)
						<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
							@include('Frontend.includes.components.cards.stream-card')
						</div>
					@endforeach
				</div>
				<!--end row-->
			</section>
		@endif
		@if($videos->isNotEmpty())
			<section>
				<h5 class="mt-4 section-title mb-3">Channel Videos</h5>
				<div class="row">
					@foreach($videos as $video)
						<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
							@include('Frontend.includes.components.cards.video-card')
						</div>
					@endforeach
				</div>
				<!--end row-->
			</section>
		@endif

		@if($channels->isNotEmpty())
			<section>
				<h5 class="mt-4 section-title mb-3">Other Channels</h5>
				<div class="row">
					@foreach ($channels as $channel)
						<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-3">
							@include('Frontend.includes.components.cards.channels')
						</div>
					@endforeach
				</div>
				<!--end row-->
			</section>
		@endif

		@endsection
		@section('header')
		@endsection
		@section('footer')
		@endsection