@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
<main> <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Our <span>tvs</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">tvs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

                <div class="ucm-nav-wrap">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($categories as $category)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="{{ $category->slug }}-tab" data-toggle="tab" href="#{{ $category->slug }}" role="tab" aria-controls="{{ $category->slug }}" aria-selected="false">
                                {{ ucfirst($category->name) }}
                            </a>
                        </li>
                        @endforeach

                    </ul>
                </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
    <div class="container"> 
        <div class="episode-top-wrap">
                            <div class="section-title"> <span class="sub-title">Latest tvs</span>
                                <h2 class="title">Latest tvs</h2>
                            </div> 
        </div>
        <div class="row tr-movie-active">

            @foreach($toptvs as $tv)
            @include('Frontend.includes.components.cards.tv-card')
            @endforeach
        </div>
        <div class="episode-top-wrap">
                            <div class="section-title"> <span class="sub-title">Trending tvs</span>
                                <h2 class="title">Trending tvs</h2>
                            </div>
                        </div>
        <div class="row tr-movie-active">

            @foreach($tvs as $tv)
            @include('Frontend.includes.components.cards.tv-card')
            @endforeach
        </div>
    </div>
</section>
  
</main> 
@endsection 
@section('header') 
@endsection 
@section('footer') 
@endsection