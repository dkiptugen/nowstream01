@extends('Frontend.includes.layout')

@section('content')
<main>
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">Genre: {{ ucfirst($genre) }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($genre) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            @if($contents->isEmpty())
                <div class="text-center text-light-50 py-4">No content found for this genre.</div>
            @else
                <div class="row">
                    @foreach($contents as $item)
                        @if($item->content_group == 'tv')
                            @include('Frontend.includes.components.cards.tv-card', ['tv' => $item])
                        @elseif($item->content_group == 'podcast')
                            @include('Frontend.includes.components.cards.podcast-card', ['podcast' => $item])
                        @elseif($item->content_group == 'radio')
                        <div class="col-lg-6 col-md-4">
                                                        @include('Frontend.includes.components.cards.radio-card', ['radio' => $item])
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $contents->links() }} {{-- pagination links --}}
                </div>
            @endif
        </div>
    </section>
       <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Trending Radios</span>
                    <h2 class="title">Trending Radios</h2>
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
                   
                </div>
            </div>
        </div>
        <div class="container mt-md-5">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest radios</span>
                    <h2 class="title">Latest radios</h2>
                </div>
            </div>

            <div class="row tr-movie-active h-100" id="radio-container" style="position: relative; height:auto !important;">
                @include('Frontend.includes.components.partials.radio-items', ['radios' => $contents])
            </div>

            <div class="text-center my-4" id="loading" style="display:none;">
                <span class="text-light">Loading more radios...</span>
            </div>

        </div>
        </div>
    </section>
</main>
@endsection
