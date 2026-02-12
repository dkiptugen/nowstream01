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
						<h2 class="title">Your Favorite  <span>Videos</span></h2>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
								<li class="breadcrumb-item"><a href="{{ route('videos') }}">Videos</a></li>
								<li class="breadcrumb-item active" aria-current="page">Favorite Videos</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- breadcrumb-area-end -->
<section> 
	<div class="row">
		@foreach ($videos as $video)
		<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
			@include('Frontend.includes.components.cards.video-card')
		</div>
		@endforeach
	</div>
	<!--end row-->
</section>

@endsection
@section('header')
@endsection
@section('footer')
@endsection