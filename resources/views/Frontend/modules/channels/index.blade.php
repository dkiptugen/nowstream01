@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!-- main-area -->
<main>
 
	<!-- breadcrumb-area -->
	<section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img')}}/bg/breadcrumb_bg.jpg">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="breadcrumb-content">
						<h2 class="title">Our <span>Channels</span></h2>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Channels</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- breadcrumb-area-end -->
	<section class="episode-area episode-bg" data-background="{{ asset('assets/img/bg/episode_bg.jpg') }}">
	<div class="container">
		<h5 class="mb-3">Popular Channels</h5>
	<div class="row g-2 g-md-3">
		@foreach($channels as $channel)
		<div class="col-6 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-md-4 mb-2">
			@include('Frontend.includes.components.cards.channels')
		</div> 
		@endforeach
	</div>
	<!--end row-->
	</div>
</section>

@endsection
@section('header')
@endsection
@section('footer')
@endsection