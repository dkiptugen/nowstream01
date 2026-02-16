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
                                    href="#{{ $category->slug }}" role="tab" aria-controls="{{ $category->slug }}"
                                    aria-selected="false">
                                    {{ ucfirst($category->name) }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </section> <!-- breadcrumb-area-end -->
        <div class="custom-carousel" 
     data-autoplay="true" 
     data-interval="4000">

    <button class="carousel-btn prev">&#10094;</button>

    <div class="carousel-viewport">
        <div class="carousel-track">

            <div class="carousel-item">
                uygfuy
            </div>

        </div>
    </div>

    <button class="carousel-btn next">&#10095;</button>
</div>


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
<style>
    .custom-carousel {
        position: relative;
        width: 100%;
    }

    .carousel-viewport {
        overflow: hidden;
        width: 100%;
    }

    .carousel-track {
        display: flex;
        transition: transform 0.4s ease;
        will-change: transform;
    }

    .carousel-item {
        flex: 0 0 auto;
        position: relative;
        padding: 5px;
    }

    /* Navigation buttons */
    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        background: rgba(0, 0, 0, 0.6);
        border: none;
        color: #fff;
        font-size: 24px;
        padding: 8px 12px;
        cursor: pointer;
    }

    .carousel-btn.prev {
        left: 0;
    }

    .carousel-btn.next {
        right: 0;
    }

    /* Edge overlay effect */
    .carousel-item::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0);
        transition: background 0.3s ease;
        pointer-events: none;
    }

    .carousel-item.edge-overlay::after {
        background: rgba(0, 0, 0, 0.35);
    }

    /* Disable overlay on tablet/mobile */
    @media (max-width: 991px) {
        .carousel-item.edge-overlay::after {
            background: transparent;
        }
    }
</style>
<script>
    document.querySelectorAll('.custom-carousel').forEach(carousel => {

        const track = carousel.querySelector('.carousel-track');
        const items = Array.from(track.children);
        const prevBtn = carousel.querySelector('.prev');
        const nextBtn = carousel.querySelector('.next');

        let index = 0;
        let itemsPerView = 4;
        let autoplay = carousel.dataset.autoplay === "true";
        let intervalTime = parseInt(carousel.dataset.interval) || 4000;
        let autoplayInterval;

        /* Responsive items */
        function getItemsPerView() {
            const w = window.innerWidth;
            if (w < 576) return 1;
            if (w < 768) return 2;
            if (w < 992) return 3;
            return 5;
        }

        /* Apply width */
        function updateLayout() {
            itemsPerView = getItemsPerView();
            const width = 100 / itemsPerView;

            items.forEach(item => {
                item.style.width = width + "%";
            });

            moveTo(index);
            updateEdgeOverlay();
        }

        /* Move carousel */
        function moveTo(i) {
            const maxIndex = Math.max(0, items.length - itemsPerView);
            index = Math.max(0, Math.min(i, maxIndex));

            const translateX = -(index * (100 / itemsPerView));
            track.style.transform = `translateX(${translateX}%)`;

            updateEdgeOverlay();
        }

        /* Edge overlay: last TWO items on each side (desktop only) */
        function updateEdgeOverlay() {
            items.forEach(item => item.classList.remove('edge-overlay'));

            if (window.innerWidth < 992) return;

            const start = index;
            const end = index + itemsPerView - 1;

            // Left side (first two visible)
            if (items[start]) items[start].classList.add('edge-overlay');
            if (items[start + 1]) items[start + 1].classList.add('edge-overlay');

            // Right side (last two visible)
            if (items[end]) items[end].classList.add('edge-overlay');
            if (items[end - 1]) items[end - 1].classList.add('edge-overlay');
        }

        /* Buttons */
        prevBtn.addEventListener('click', () => moveTo(index - 1));
        nextBtn.addEventListener('click', () => moveTo(index + 1));

        /* Swipe support */
        let startX = 0;
        track.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
        });

        track.addEventListener('touchend', e => {
            let endX = e.changedTouches[0].clientX;
            if (startX - endX > 50) moveTo(index + 1);
            if (endX - startX > 50) moveTo(index - 1);
        });

        /* Autoplay */
        function startAutoplay() {
            if (!autoplay) return;
            autoplayInterval = setInterval(() => {
                const maxIndex = items.length - itemsPerView;
                if (index >= maxIndex) {
                    moveTo(0);
                } else {
                    moveTo(index + 1);
                }
            }, intervalTime);
        }

        function stopAutoplay() {
            clearInterval(autoplayInterval);
        }

        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);

        /* Init */
        window.addEventListener('resize', updateLayout);
        updateLayout();
        startAutoplay();
    });
</script>

@section('header')
@endsection
@section('footer')
@endsection