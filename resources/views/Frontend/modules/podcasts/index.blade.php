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
                <div class="section-title"> <span class="sub-title">Latest Podcasts</span>
                    <h2 class="title">Latest Podcasts</h2>
                </div>
            </div>
            <div class="row tr-movie-active">

                @foreach($topPodcasts as $podcast)
                @include('Frontend.includes.components.cards.podcast-card')
                @endforeach
            </div>
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest Podcasts</span>
                    <h2 class="title">Latest Podcasts</h2>
                </div>
            </div>
            <div class="row tr-movie-active">
<div class="row tr-movie-active h-100" id="podcast-container" style="position: relative; height:auto !important;">
                    @include('Frontend.includes.components.partials.podcast-list', ['podcasts' => $podcasts])
                </div>

                <div class="text-center my-4" id="loading" style="display:none;">
                    <span class="text-light">Loading more podcasts...</span>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
@section('header')
@endsection
@section('footer')

<script>
let page = 1;
let loading = false;
let hasMore = true;

window.addEventListener('scroll', function () {

    if (loading || !hasMore) return;

    const scrollPosition = window.innerHeight + window.scrollY;
    const triggerPoint = document.body.offsetHeight - 200;

    if (scrollPosition >= triggerPoint) {
        loadMore();
    }
});

function loadMore() {
    loading = true;
    page++;

    document.getElementById('loading').style.display = 'block';

    fetch(`?page=${page}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {

        // If no more items returned → stop
        if (html.trim() === '') {
            hasMore = false;
            document.getElementById('loading').innerText = 'No more podcasts';
            return;
        }

        document
            .getElementById('podcast-container')
            .insertAdjacentHTML('beforeend', html);

        loading = false;
        document.getElementById('loading').style.display = 'none';
    })
    .catch(() => {
        loading = false;
        document.getElementById('loading').style.display = 'none';
    });
}
</script>

@endsection