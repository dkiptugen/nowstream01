@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')

@section('content')
<main class="events-index">
    <section class="events-hero">
        <div class="container">
            <div class="events-hero__inner">
                <div>
                    <p class="events-hero__eyebrow">Live Events</p>
                    <h1 class="events-hero__title">Discover what is happening now and what is coming up next.</h1>
                    <p class="events-hero__copy">A cleaner way to browse major live nights, tickets, and trending shows without digging through clutter.</p>
                </div>
                <div class="events-hero__stats">
                    <div class="events-hero__stat">
                        <strong>{{ number_format($topevents->count()) }}</strong>
                        <span>Trending picks</span>
                    </div>
                    <div class="events-hero__stat">
                        <strong>{{ number_format($events->total()) }}</strong>
                        <span>Events listed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($topevents->isNotEmpty())
        <section class="events-section events-section--featured">
            <div class="container">
                <div class="events-section__head">
                    <div>
                        <p class="events-section__eyebrow">Trending</p>
                        <h2 class="events-section__title">Featured events</h2>
                    </div>
                    <a href="#events-grid" class="events-section__link">Browse all</a>
                </div>

                <div class="pcar-wrapper">
                    <div class="pcar-overlay pcar-overlay-left"></div>
                    <div class="pcar-overlay pcar-overlay-right"></div>

                    <div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="4" data-tablet="2" data-mobile="1">
                        <div class="pcar-track">
                            @foreach($topevents as $event)
                                <div class="pcar-item">
                                    @include('Frontend.includes.components.cards.events')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="events-section">
        <div class="container">
            <div class="events-section__head">
                <div>
                    <p class="events-section__eyebrow">Browse</p>
                    <h2 class="events-section__title">All events</h2>
                </div>
                @if (session('success'))
                    <span class="events-section__notice">You do not have an active subscription. Pick an event below.</span>
                @endif
            </div>

            <div
                class="row g-3 g-lg-4"
                id="event-container"
                data-next-page-url="{{ $events->nextPageUrl() }}"
                data-loading-label="Loading more events..."
                data-idle-label="More events on the way"
                data-complete-label="All events loaded"
                data-error-label="Could not load more events right now"
            >
                @include('Frontend.includes.components.partials.event-items', ['events' => $events])
            </div>

            <div class="text-center my-4 infinite-scroll-loader" id="event-loading" @if(!$events->hasMorePages()) hidden @endif>
                <span class="infinite-scroll-dot" aria-hidden="true"></span>
                <span class="infinite-scroll-copy" id="event-loading-status">
                    {{ $events->hasMorePages() ? 'More events on the way' : 'All events loaded' }}
                </span>
            </div>
        </div>
    </section>
</main>
@endsection

@section('header')
<style>
    .events-index {
        padding-top: 112px;
        padding-bottom: 64px;
        background:
            radial-gradient(circle at top, rgba(24, 92, 145, 0.18), transparent 28%),
            linear-gradient(180deg, #06111d 0%, #08131b 30%, #050b11 100%);
    }

    .events-hero,
    .events-section {
        padding-top: 24px;
    }

    .events-hero__inner,
    .events-section__head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
    }

    .events-hero__inner {
        padding: 28px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        background: linear-gradient(180deg, rgba(9, 19, 29, 0.94), rgba(7, 15, 24, 0.9));
        box-shadow: 0 28px 64px rgba(0, 0, 0, 0.22);
    }

    .events-hero__eyebrow,
    .events-section__eyebrow {
        margin: 0 0 8px;
        color: #8fd7ff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .events-hero__title,
    .events-section__title {
        margin: 0;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: -0.03em;
    }

    .events-hero__title {
        max-width: 760px;
        font-size: clamp(2rem, 4vw, 3.5rem);
        line-height: 1.02;
    }

    .events-hero__copy {
        max-width: 620px;
        margin: 14px 0 0;
        color: rgba(231, 238, 247, 0.72);
        font-size: 15px;
        line-height: 1.65;
    }

    .events-hero__stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(120px, 1fr));
        gap: 12px;
        width: min(100%, 320px);
    }

    .events-hero__stat {
        padding: 16px 18px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .events-hero__stat strong {
        display: block;
        color: #ffffff;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .events-hero__stat span,
    .events-section__notice {
        color: rgba(231, 238, 247, 0.68);
        font-size: 13px;
    }

    .events-section__head {
        margin-bottom: 18px;
    }

    .events-section__title {
        font-size: clamp(1.35rem, 2vw, 2rem);
    }

    .events-section__link {
        color: #f5f8fb;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .events-section__notice {
        display: inline-flex;
        align-items: center;
        min-height: 36px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.05);
    }

    .nowstream-event-card {
        height: 100%;
    }

    .nowstream-event-card__image {
        width: 100%;
        display: block;
        border-radius: 18px;
        object-fit: cover;
    }

    .nowstream-event-card__body {
        padding-right: 4px;
    }

    .nowstream-event-card__date,
    .nowstream-event-card__time small,
    .nowstream-event-card__meta .duration,
    .nowstream-event-card__meta .quality {
        color: rgba(231, 238, 247, 0.72);
    }

    .nowstream-event-card__title {
        margin: 8px 0 6px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .nowstream-event-card__title a {
        color: inherit;
    }

    .nowstream-event-card__meta ul {
        display: grid;
        gap: 8px;
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
        .events-hero__inner,
        .events-section__head {
            flex-direction: column;
            align-items: flex-start;
        }

        .events-hero__stats {
            width: 100%;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .events-index {
            padding-top: 96px;
            padding-bottom: 52px;
        }

        .events-hero__inner {
            padding: 20px;
            border-radius: 22px;
        }

        .events-hero__copy {
            font-size: 14px;
            line-height: 1.55;
        }

        .events-hero__stats {
            gap: 10px;
        }

        .events-hero__stat {
            padding: 14px;
            border-radius: 16px;
        }

        .events-section {
            padding-top: 18px;
        }

        .events-section__head {
            margin-bottom: 14px;
        }

        .nowstream-event-card__title {
            font-size: 0.92rem;
        }

        .nowstream-event-card__time small,
        .nowstream-event-card__meta .duration,
        .nowstream-event-card__meta .quality {
            font-size: 11px;
        }
    }
</style>
@endsection

@section('footer')
@include('Frontend.includes.components.partials.infinite-scroll', [
    'containerId' => 'event-container',
    'loaderId' => 'event-loading',
    'statusId' => 'event-loading-status',
])
@endsection
