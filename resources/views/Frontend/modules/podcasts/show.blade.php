@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
<main> <!-- movie-details-area -->
    <section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-center position-relative">
                <div class="col-xl-4 col-lg-4">
                    <div class="movie-details-img"> <img src="{{ $podcast->podcast_image }}" class="img-fluid" alt="{{ $podcast->title }}"> <a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="popup-video"> <!-- <img src="{{ asset('assets/img/images/play_icon.png') }}" alt=""> --> </a> </div>
                </div>
                <div class="col-xl-6 col-lg-8">
                    <div class="movie-details-content">
                        <h5>Top Podcast</h5>
                        <h2> {{ $podcast->title }} @if($podcast->has_livestream) <span class="badge badge-danger">Live</span> @endif </h2>
                        <p class="mb-3"> {!! $podcast->description !!} </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main> 
@endsection 