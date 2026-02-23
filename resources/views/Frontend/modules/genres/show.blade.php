@extends('Frontend.includes.layout')
@section('content') <!-- main-area -->
<main> <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Live <span>radios</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">radios</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
 
        </div>
    </section> <!-- breadcrumb-area-end -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Trending Radios</span>
                    <h2 class="title">Trending Radios</h2>
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
@section('header')
<style>
    .col-xl-2.col-lg-3.col-sm-6.grid-item{
        position: relative !important;
    }
</style>
@endsection
@section('footer')


<script>
    let page = 1;
    let loading = false;
    let hasMore = true;

    const container = document.getElementById('radio-container');
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