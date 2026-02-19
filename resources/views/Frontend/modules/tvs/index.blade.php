@extends('Frontend.includes.layout')
@section('content')
<main>
    <!-- Breadcrumb -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Live <span>TVs</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">TVs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- TV Genres -->
            <div class="ucm-nav-wrap">
                <ul class="nav nav-tabs" id="genreTabs" role="tablist">
                    @foreach($genres->filter()->unique() as $genre)
                        @php
                            $slug = Str::slug($genre);
                            $label = ucfirst(trim($genre));
                        @endphp
                        @if(!empty($slug))
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="{{ route('genre.show', ['genre' => $slug]) }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <!-- Top Trending TVs Slider -->
    <section class="top-rated-movie tr-movie-bg pb-0" data-background="{{ asset('assets/img/bg/tr_movies_bg.jpg') }}">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title">
                    <span class="sub-title">Trending TVs</span>
                    <h2 class="title">Trending TVs</h2>
                </div>
            </div>

            <div class="pcar-wrapper">
                <div class="pcar-overlay pcar-overlay-left"></div>
                <div class="pcar-overlay pcar-overlay-right"></div>

                <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="11" data-tablet="3" data-mobile="1">
                    <div class="pcar-track">
                        @foreach($toptvs as $tv)
                            <div class="pcar-item">
                                @include('Frontend.includes.components.cards.slider-card', ['item' => $tv])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- English Channels -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img/bg/tr_movies_bg.jpg') }}">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title">
                    <span class="sub-title">English Channels</span>
                    <h2 class="title">English Channels</h2>
                </div>
            </div>

            <div class="row tr-movie-active">
                @foreach($english_tvs as $tv)
                    @include('Frontend.includes.components.cards.tv-card', ['tv' => $tv])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Latest TVs with Infinite Scroll -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img/bg/tr_movies_bg.jpg') }}">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title">
                    <span class="sub-title">Latest TVs</span>
                    <h2 class="title">Latest TVs</h2>
                </div>
            </div>

            <div class="row tr-movie-active h-100" id="tv-container" style="position: relative; height:auto !important;">
@include('Frontend.includes.components.partials.tvs-list', ['tvs' => $tvs])
                </div>

                <div class="text-center my-4" id="loading" style="display:none;">
                    <span class="text-light">Loading more tvs...</span>
                </div>
        </div>
    </section>
</main>
@endsection

@section('footer')
<script>
    let page = 1;
    let loading = false;
    let hasMore = true;

    const container = document.getElementById('tv-container');
    const loader = document.getElementById('loading');

    window.addEventListener('scroll', () => {
        if (loading || !hasMore) return;

        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 400) {
            loadMore();
        }
    });

    function loadMore() {
        loading = true;
        loader.style.display = 'block';
        page++;

     const params = new URLSearchParams(window.location.search);
params.set('page', page);

fetch(`?${params.toString()}`, {

                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.html) {
                    container.insertAdjacentHTML('beforeend', data.html);
                }

                hasMore = data.hasMore;
                loading = false;
                loader.style.display = hasMore ? 'block' : 'none';
            })
            .catch(() => {
                loading = false;
                hasMore = false;
                loader.style.display = 'none';
            });
    }
</script>
@endsection
