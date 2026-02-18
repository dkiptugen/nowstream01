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
            <div class="row tr-movie-active" id="podcast-container" style="position: relative; height:auto !important;">
                @include('Frontend.includes.components.partials.podcast-list', ['podcasts' => $podcasts])
            </div>

            <div class="text-center my-4" id="loading" style="display:none;">
                <span class="text-light">Loading more podcasts...</span>
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

    const container = document.getElementById('podcast-container');
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

        fetch(`?page=${page}`, {
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