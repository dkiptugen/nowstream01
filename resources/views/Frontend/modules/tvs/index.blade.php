@extends('Frontend.includes.layout')

@section('content')
<main class="tv-index">
    <section class="tv-hero">
        <div class="container">
            <div class="tv-hero__inner">
                <div>
                    <p class="tv-hero__eyebrow">Live TV</p>
                    <h1 class="tv-hero__title">Go straight to the channels people are already watching.</h1>
                    <p class="tv-hero__copy">Fast discovery for live TV with genre shortcuts, trending stations, and a cleaner channel grid.</p>
                </div>
                <div class="tv-hero__chips">
                    @foreach($genres->filter()->unique()->take(8) as $genre)
                        @php $slug = Str::slug($genre); @endphp
                        @if(!empty($slug))
                            <a href="{{ route('genre.tvs', ['genre' => $slug]) }}" class="tv-hero__chip">{{ ucfirst(trim($genre)) }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($toptvs->isNotEmpty())
        <section class="tv-section">
            <div class="container">
                <div class="tv-section__head">
                    <div>
                        <p class="tv-section__eyebrow">Trending</p>
                        <h2 class="tv-section__title">Top live channels</h2>
                    </div>
                    <a href="#tv-grid" class="tv-section__link">Browse all</a>
                </div>

                <div class="pcar-wrapper">
                    <div class="pcar-overlay pcar-overlay-left"></div>
                    <div class="pcar-overlay pcar-overlay-right"></div>

                    <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="8" data-tablet="3" data-mobile="2">
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
    @endif

    @if($english_tvs->isNotEmpty())
        <section class="tv-section">
            <div class="container">
                <div class="tv-section__head">
                    <div>
                        <p class="tv-section__eyebrow">Popular</p>
                        <h2 class="tv-section__title">English channels</h2>
                    </div>
                </div>

                <div class="row g-3 g-lg-4">
                    @foreach($english_tvs as $tv)
                        @include('Frontend.includes.components.cards.tv-card', ['tv' => $tv])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="tv-section">
        <div class="container">
            <div class="tv-section__head">
                <div>
                    <p class="tv-section__eyebrow">Browse</p>
                    <h2 class="tv-section__title">All channels</h2>
                </div>
            </div>

            <div
                class="row g-3 g-lg-4"
                id="tv-container"
                data-next-page-url="{{ $tvs->nextPageUrl() }}"
                data-loading-label="Loading more TVs..."
                data-idle-label="More channels coming up"
                data-complete-label="All live channels loaded"
                data-error-label="Could not load more TVs right now"
            >
                @include('Frontend.includes.components.partials.tv-items', ['tvs' => $tvs])
            </div>

            <div class="text-center my-4 infinite-scroll-loader" id="tv-loading" @if(!$tvs->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="tv-loading-status">
                    {{ $tvs->hasMorePages() ? 'More channels coming up' : 'All live channels loaded' }}
                </span>
            </div>
        </div>
    </section>
</main>
@endsection

@section('header')
<style>
    .tv-index {
        padding-top: 112px;
        padding-bottom: 64px;
        background:
            radial-gradient(circle at top, rgba(24, 92, 145, 0.18), transparent 28%),
            linear-gradient(180deg, #06111d 0%, #08131b 30%, #050b11 100%);
    }

    .tv-hero,
    .tv-section {
        padding-top: 24px;
    }

    .tv-hero__inner {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        padding: 28px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        background: linear-gradient(180deg, rgba(9, 19, 29, 0.94), rgba(7, 15, 24, 0.9));
        box-shadow: 0 28px 64px rgba(0, 0, 0, 0.22);
    }

    .tv-hero__eyebrow,
    .tv-section__eyebrow {
        margin: 0 0 8px;
        color: #8fd7ff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .tv-hero__title,
    .tv-section__title {
        margin: 0;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: -0.03em;
    }

    .tv-hero__title {
        max-width: 760px;
        font-size: clamp(2rem, 4vw, 3.5rem);
        line-height: 1.02;
    }

    .tv-hero__copy {
        max-width: 620px;
        margin: 14px 0 0;
        color: rgba(231, 238, 247, 0.72);
        font-size: 15px;
        line-height: 1.65;
    }

    .tv-hero__chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
        max-width: 360px;
    }

    .tv-hero__chip {
        display: inline-flex;
        align-items: center;
        min-height: 36px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        color: #f5f8fb;
        font-size: 12px;
        font-weight: 700;
    }

    .tv-section__head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .tv-section__title {
        font-size: clamp(1.35rem, 2vw, 2rem);
    }

    .tv-section__link {
        color: #f5f8fb;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .infinite-scroll-loader {
        display: grid;
        place-items: center;
        gap: 12px;
        min-height: 88px;
    }

    .infinite-scroll-loader[hidden] {
        display: none !important;
    }

    .infinite-scroll-dot {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-top-color: #ffd24f;
        animation: infiniteScrollSpin 0.9s linear infinite;
    }

    .infinite-scroll-loader:not(.is-loading) .infinite-scroll-dot {
        animation-play-state: paused;
        opacity: 0.45;
    }

    .infinite-scroll-copy {
        color: rgba(255, 255, 255, 0.72);
        font-size: 13px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    @keyframes infiniteScrollSpin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 991px) {
        .tv-hero__inner,
        .tv-section__head {
            flex-direction: column;
            align-items: flex-start;
        }

        .tv-hero__chips {
            max-width: none;
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .tv-index {
            padding-top: 96px;
            padding-bottom: 52px;
        }

        .tv-hero__inner {
            padding: 20px;
            border-radius: 22px;
        }

        .tv-hero__copy {
            font-size: 14px;
            line-height: 1.55;
        }

        .tv-hero__chips {
            gap: 8px;
        }

        .tv-hero__chip {
            min-height: 32px;
            padding: 6px 10px;
            font-size: 11px;
        }

        .tv-section {
            padding-top: 18px;
        }

        .tv-section__head {
            margin-bottom: 14px;
        }
    }
</style>
@endsection

@section('footer')
@include('Frontend.includes.components.partials.infinite-scroll', [
    'containerId' => 'tv-container',
    'loaderId' => 'tv-loading',
    'statusId' => 'tv-loading-status',
])
@endsection
