@php
    $categories = $categories ?? [];
@endphp
@extends('Frontend.includes.layout')
@section('content')
<main>
    <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Our <span>Categories</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Categories</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->    
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container"> 
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">All Categories</span>
                    <h2 class="title">All Categories</h2>
                </div> 
            </div>
            <div class="row tr-movie-active">
                @foreach($categories as $category)
                <div class="col-xl-3 col-lg-4 col-sm-6 grid-item grid-sizer">
                    <div class="movie-item  mb-60 shadow-sm bg-dark">
                        <div class="movie-poster mb-0">
                            <a href="{{ route('category.show', ['slug' => $category->slug]) }}">
                                <img src="{{$category->image_url ?? asset('frontend-assets/images/default.png')}}"
                                    class="w-100 d-block w-100" alt="...">
                            </a>
                        </div>
                        <div class="movie-content">
                            <h5 class="title"><a href="{{ route('category.show', ['slug' => $category->slug]) }}">{{ ucfirst($category->name) }}</a></h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection