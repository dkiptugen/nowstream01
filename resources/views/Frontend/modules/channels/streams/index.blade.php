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
						<h2 class="title">Our <span>Streams</span></h2>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Streams</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- breadcrumb-area-end -->
	 
    <section class="top-rated-movie tr-movie-bg pb-0" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> 
					<span class="sub-title">Trending Streams</span>
                    <h2 class="title">Trending Streams</h2>
                </div>
            </div>
        </div>

        <div class="pcar-wrapper">

            <!-- Outside container overlays -->
            <div class="pcar-overlay pcar-overlay-left"></div>
            <div class="pcar-overlay pcar-overlay-right"></div>

            <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="5" data-tablet="3"
                data-mobile="1">

                <div class="pcar-track">
                    @foreach($topstreams as $stream)
                    <div class="pcar-item">
					@include('Frontend.includes.components.cards.stream-card')
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
	<!-- movie-area -->
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
						<h2 class="title">Latest Streams</h2>
					</div>
				</div>
				<div class="col-lg-6">
				</div>
			</div>
			<div class="row tr-movie-active">
				@foreach($streams as $stream)  
				<div class="col-xl-3 col-lg-4 col-sm-6 grid-item grid-sizer">
					@include('Frontend.includes.components.cards.stream-card')
				</div>
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
	<!-- movie-area-end -->
	@endsection
	@section('header')
	@endsection
	@section('footer')
	@endsection