@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
<main> <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Our<span>Podcasts</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Podcasts</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-end mb-60">
                <div class="col-lg-6">
                    <div class="section-title text-center text-lg-left"> <span class="sub-title">.......</span>
                        <h2 class="title">Latest Podcasts</h2>
                    </div>
                </div>
                <div class="col-lg-6">
                </div>
            </div>
            <div class="row tr-movie-active"> @foreach($podcasts as $podcast) @include('Frontend.includes.components.cards.podcast-card') @endforeach </div>
            <div class="row"> <divclass="col-12">
                    <div class="pagination
-wrap mt-30">
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
                            <div class="section-title"> <span class="sub-title">.......</span>
                                <h2 class="title">Popular Podcasts</h2>
                            </div>
                        </div>
                        <div class="episode-list">
                            <div class="row g-2 g-md-3"> @foreach($podcasts as $podcast) <div class="col-6 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-md-4 mb-2"> @include('Frontend.includes.components.cards.podcast-card') </div> @endforeach </div> <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
    </section>
</main> @endsection @section('header') @endsection @section('footer') @endsection