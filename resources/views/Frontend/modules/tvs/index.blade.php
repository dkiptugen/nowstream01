@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
    <main> <!-- breadcrumb-area -->
        <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-content">
                            <h2 class="title">Live <span>Tvs</span></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Tvs</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="ucm-nav-wrap">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($categories as $category)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="{{ $category->slug }}-tab" data-toggle="tab"
                                    href="{{ $category->slug }}" role="tab" aria-controls="{{ $category->slug }}"
                                    aria-selected="false">
                                    {{ ucfirst($category->name) }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </section>
        <section class="top-rated-movie tr-movie-bg pb-0" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
            <div class="container">
                <div class="episode-top-wrap">
                    <div class="section-title"> <span class="sub-title">Trending TVs</span>
                        <h2 class="title">Trending TVs</h2>
                    </div>
                </div>
            </div>

            <div class="pcar-wrapper">

                <!-- Outside container overlays -->
                <div class="pcar-overlay pcar-overlay-left"></div>
                <div class="pcar-overlay pcar-overlay-right"></div>

                <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="11" data-tablet="3"
                    data-mobile="1">

                    <div class="pcar-track">
                        @foreach($toptvs as $item)
                            <div class="pcar-item">
                                @include('Frontend.includes.components.cards.slider-card')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>



        <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
            <div class="container">
                <div class="episode-top-wrap">
                    <div class="section-title"> <span class="sub-title">Trending TVs</span>
                        <h2 class="title">Trending TVs</h2>
                    </div>
                </div>
                <div class="row tr-movie-active">

                    @foreach($toptvs as $tv)
                        @include('Frontend.includes.components.cards.tv-card')
                    @endforeach
                </div>
                <div class="row tr-movie-active">

                    @foreach($english_tvs as $tv)
                        @include('Frontend.includes.components.cards.tv-card')
                    @endforeach
                </div>
                <div class="episode-top-wrap">
                    <div class="section-title"> <span class="sub-title">Latest TVs</span>
                        <h2 class="title">Latest TVs</h2>
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
@push('styles')
@endpush
@push('scripts')

@endpush