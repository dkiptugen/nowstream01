@extends('Frontend.includes.layout')

@php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$featuredEvent = $topevents->first() ?? $events->first();
$featuredEventImage = $featuredEvent && $featuredEvent->event_image
    ? Storage::disk(config('filesystems.default'))->url($featuredEvent->event_image)
    : ($top_videos->first()->thumbnail_url ?? asset('frontend-assets/images/default.png'));

$featuredDescription = $featuredEvent
    ? Str::limit(trim(strip_tags($featuredEvent->description ?? 'Live events, top TV, radio, podcasts, and on-demand video in one place.')), 170)
    : 'Live events, top TV, radio, podcasts, and on-demand video in one place.';

$heroGenres = $genres->filter()->unique()->take(8);
$heroLiveChannels = $toptvs->take(4);
$eventShelf = $topevents->take(8);
$tvShelf = $toptvs->take(12);
$radioShelf = $topradios->take(12);
$videoFeatureShelf = $top_videos->take(4);
$videoShelf = $videos->take(8);
$podcastShelf = $podcasts->take(12);
$latestPodcastShelf = $topPodcasts->take(12);

$routeForContent = function ($item) {
    return match ($item->content_group) {
        'tv' => route('tv.show', $item->slug),
        'radio' => route('radio.show', $item->slug),
        'podcast' => route('podcast.show', $item->slug),
        'video' => route('video.show', [$item->uuid, $item->slug]),
        default => '#',
    };
};

$labelForContent = function ($item) {
    return match ($item->content_group) {
        'tv' => 'Live TV',
        'radio' => 'Radio',
        'podcast' => 'Podcast',
        'video' => 'Video',
        default => 'Nowstream',
    };
};

$imageForContent = fn ($item) => $item->thumbnail_url ?: asset('frontend-assets/images/default.png');

$quickLinks = collect([
    ['title' => 'Live TV', 'meta' => 'Top channels in ' . ($country_name ?? 'your region'), 'route' => route('tvs')],
    ['title' => 'Radio', 'meta' => 'Streaming stations and talk audio', 'route' => route('radios')],
    ['title' => 'Videos', 'meta' => 'Fresh clips, replays, and on-demand', 'route' => route('videos')],
    ['title' => 'Podcasts', 'meta' => 'Interviews, stories, and series', 'route' => route('podcasts')],
    ['title' => 'Events', 'meta' => 'Major live nights and ticketed streams', 'route' => route('events')],
]);
@endphp

<style>
    body {
        background:
            radial-gradient(circle at top, rgba(20, 93, 150, 0.35), transparent 32%),
            linear-gradient(180deg, #06111d 0%, #08131b 28%, #050b11 100%) !important;
        color: #f6f8fb;
    }

    .clean-home {
        width: 100%;
        padding: 28px 0 72px;
    }

    .clean-home .container {
        width: 100%;
        max-width: none;
        padding-left: clamp(16px, 3vw, 42px);
        padding-right: clamp(16px, 3vw, 42px);
    }

    .clean-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 30px;
        min-height: 620px;
        background-color: #09131d;
        box-shadow: 0 30px 90px rgba(0, 0, 0, 0.45);
    }

    .clean-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(5, 10, 15, 0.96) 0%, rgba(5, 10, 15, 0.8) 34%, rgba(5, 10, 15, 0.24) 68%, rgba(5, 10, 15, 0.72) 100%),
            linear-gradient(180deg, rgba(8, 17, 28, 0.15) 0%, rgba(8, 17, 28, 0.78) 100%);
        z-index: 1;
    }

    .clean-hero__bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transform: scale(1.04);
        filter: saturate(1.04);
    }

    .clean-hero__content,
    .clean-hero__aside {
        position: relative;
        z-index: 2;
    }

    .clean-hero__content {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        min-height: 620px;
        padding: 56px;
    }

    .clean-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #ffd965;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
    }

    .clean-eyebrow::before {
        content: "";
        width: 36px;
        height: 2px;
        background: linear-gradient(90deg, #ffd965, rgba(255, 217, 101, 0.2));
    }

    .clean-hero__title {
        max-width: 640px;
        margin: 0;
        font-size: clamp(2.6rem, 4vw, 4.7rem);
        line-height: 0.98;
        letter-spacing: -0.04em;
        color: #ffffff;
        text-shadow: 0 18px 45px rgba(0, 0, 0, 0.34);
    }

    .clean-hero__title span {
        color: #8fd7ff;
    }

    .clean-hero__description {
        max-width: 560px;
        margin: 18px 0 0;
        color: rgba(236, 242, 249, 0.84);
        font-size: 15px;
        line-height: 1.75;
    }

    .clean-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 24px;
    }

    .clean-meta span,
    .clean-chip {
        display: inline-flex;
        align-items: center;
        min-height: 38px;
        padding: 9px 14px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        color: #f8fbff;
        font-size: 12px;
        font-weight: 600;
        backdrop-filter: blur(16px);
    }

    .clean-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 28px;
    }

    .clean-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 12px 20px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
    }

    .clean-btn:hover {
        transform: translateY(-2px);
    }

    .clean-btn--primary {
        background: linear-gradient(135deg, #ffd24f, #f7a400);
        color: #07111a;
        box-shadow: 0 16px 28px rgba(247, 164, 0, 0.26);
    }

    .clean-btn--ghost {
        border: 1px solid rgba(255, 255, 255, 0.14);
        background: rgba(7, 16, 24, 0.42);
        color: #f4f7fb;
    }

    .clean-hero__aside {
        display: flex;
        align-items: flex-end;
        height: 100%;
        padding: 32px 32px 40px 0;
    }

    .clean-panel {
        width: 100%;
        max-width: 340px;
        margin-left: auto;
        padding: 22px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        background: rgba(8, 17, 28, 0.8);
        backdrop-filter: blur(18px);
    }

    .clean-panel__title {
        margin: 0 0 18px;
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
    }

    .clean-live-list {
        display: grid;
        gap: 12px;
    }

    .clean-live-item {
        display: grid;
        grid-template-columns: 58px minmax(0, 1fr);
        gap: 12px;
        align-items: center;
        padding: 10px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.04);
    }

    .clean-live-item img {
        width: 58px;
        height: 58px;
        border-radius: 14px;
        object-fit: cover;
    }

    .clean-live-item strong,
    .clean-shelf-card__title,
    .clean-video-card__title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .clean-live-item small,
    .clean-shelf-card__meta,
    .clean-section__sub,
    .clean-link-card__meta,
    .clean-video-card__meta {
        color: rgba(231, 238, 247, 0.68);
    }

    .clean-link-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-top: 22px;
    }

    .clean-link-card {
        min-height: 108px;
        padding: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(15, 31, 45, 0.9), rgba(8, 17, 28, 0.92));
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        transition: transform 0.22s ease, border-color 0.22s ease;
    }

    .clean-link-card:hover,
    .clean-shelf-card:hover,
    .clean-video-card:hover,
    .clean-genre-card:hover {
        transform: translateY(-3px);
        border-color: rgba(143, 215, 255, 0.35);
    }

    .clean-link-card__title {
        margin: 0 0 8px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
    }

    .clean-link-card__meta {
        margin: 0;
        font-size: 13px;
        line-height: 1.6;
    }

    .clean-section {
        margin-top: 38px;
    }

    .clean-section__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .clean-section__eyebrow {
        margin: 0 0 4px;
        color: #ffd965;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .clean-section__title {
        margin: 0;
        color: #ffffff;
        font-size: clamp(1.35rem, 2vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.03em;
    }

    .clean-section__sub {
        margin: 6px 0 0;
        font-size: 14px;
    }

    .clean-section__link {
        color: #f2f6fb;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .clean-track {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: minmax(190px, 190px);
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: thin;
        scroll-behavior: smooth;
        scroll-snap-type: x proximity;
        cursor: grab;
    }

    .clean-track--event {
        grid-auto-columns: minmax(250px, 250px);
    }

    .clean-track--video {
        grid-auto-columns: minmax(320px, 320px);
    }

    .clean-shelf-card,
    .clean-video-card,
    .clean-genre-card {
        display: block;
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(12, 24, 36, 0.94), rgba(7, 15, 24, 0.98));
        overflow: hidden;
        transition: transform 0.22s ease, border-color 0.22s ease;
        scroll-snap-align: start;
    }

    .clean-track::-webkit-scrollbar {
        height: 0;
    }

    .clean-track.is-dragging {
        cursor: grabbing;
        scroll-behavior: auto;
        user-select: none;
    }

    .clean-slider {
        position: relative;
    }

    .clean-slider__controls {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .clean-slider__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        background: rgba(10, 21, 31, 0.84);
        color: #ffffff;
        font-size: 18px;
        transition: opacity 0.22s ease, transform 0.22s ease, border-color 0.22s ease;
    }

    .clean-slider__button:hover:not(:disabled) {
        transform: translateY(-2px);
        border-color: rgba(143, 215, 255, 0.35);
    }

    .clean-slider__button:disabled {
        opacity: 0.38;
        cursor: default;
    }

    .clean-slider__icon {
        line-height: 1;
    }

    .clean-shelf-card__media,
    .clean-video-card__media {
        position: relative;
        overflow: hidden;
        background: #0d1822;
    }

    .clean-shelf-card__media img,
    .clean-video-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .clean-shelf-card__media {
        aspect-ratio: 0.84;
    }

    .clean-track--event .clean-shelf-card__media {
        aspect-ratio: 0.72;
    }

    .clean-video-card__media {
        aspect-ratio: 16 / 9;
    }

    .clean-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 28px;
        padding: 7px 10px;
        border-radius: 999px;
        background: rgba(8, 17, 28, 0.82);
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .clean-badge::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ff5757;
        box-shadow: 0 0 0 5px rgba(255, 87, 87, 0.14);
    }

    .clean-shelf-card__body,
    .clean-video-card__body {
        padding: 16px;
    }

    .clean-shelf-card__title,
    .clean-video-card__title {
        margin: 0 0 8px;
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.35;
    }

    .clean-shelf-card__meta,
    .clean-video-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 12px;
        line-height: 1.5;
    }

    .clean-genre-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .clean-genre-card {
        padding: 20px;
    }

    .clean-genre-card__label {
        margin: 0;
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
    }

    .clean-genre-card__meta {
        margin: 8px 0 0;
        color: rgba(231, 238, 247, 0.68);
        font-size: 13px;
    }

    @media (max-width: 1199px) {
        .clean-hero {
            min-height: auto;
        }

        .clean-hero__content {
            min-height: auto;
            padding: 42px 34px 24px;
        }

        .clean-hero__aside {
            padding: 0 34px 34px;
        }

        .clean-link-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .clean-genre-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .clean-home {
            padding-top: 16px;
        }

        .clean-hero {
            border-radius: 24px;
        }

        .clean-hero__content {
            padding: 28px 22px 18px;
        }

        .clean-hero__aside {
            padding: 0 22px 22px;
        }

        .clean-panel {
            max-width: none;
        }

        .clean-link-grid,
        .clean-genre-grid {
            grid-template-columns: 1fr;
        }

        .clean-section__head {
            align-items: flex-start;
            flex-direction: column;
        }

        .clean-slider__controls {
            width: 100%;
            justify-content: flex-end;
        }

        .clean-track {
            grid-auto-columns: minmax(164px, 164px);
        }

        .clean-track--event {
            grid-auto-columns: minmax(210px, 210px);
        }

        .clean-track--video {
            grid-auto-columns: minmax(270px, 270px);
        }
    }
</style>

@section('content')
<div class="clean-home">
    <div class="container">
        <section class="clean-hero">
            <div class="clean-hero__bg" style="background-image: url('{{ $featuredEventImage }}');"></div>
            <div class="row no-gutters">
                <div class="col-xl-8">
                    <div class="clean-hero__content">
                        <div class="clean-eyebrow">Nowstream Home</div>

                        @if($featuredEvent)
                            @php
                                $titleWords = preg_split('/\s+/', trim($featuredEvent->event_name));
                                $splitPoint = (int) ceil(count($titleWords) / 2);
                                $titleStart = implode(' ', array_slice($titleWords, 0, $splitPoint));
                                $titleEnd = implode(' ', array_slice($titleWords, $splitPoint));
                            @endphp
                            <h1 class="clean-hero__title">
                                {{ $titleStart }}
                                @if($titleEnd)
                                    <span>{{ $titleEnd }}</span>
                                @endif
                            </h1>

                            <p class="clean-hero__description">{{ $featuredDescription }}</p>

                            <div class="clean-meta">
                                <span>Featured event</span>
                                @if($featuredEvent->start_time)
                                    <span>{{ Carbon::parse($featuredEvent->start_time)->format('M d, Y') }}</span>
                                @endif
                                @if($featuredEvent->venue)
                                    <span>{{ $featuredEvent->venue }}</span>
                                @endif
                                <span>{{ $topevents->count() }} event picks</span>
                            </div>

                            <div class="clean-actions">
                                <a href="{{ route('event.show', $featuredEvent->slug) }}" class="clean-btn clean-btn--primary">Watch Event</a>
                                <a href="{{ route('tvs') }}" class="clean-btn clean-btn--ghost">Browse Live TV</a>
                            </div>
                        @else
                            <h1 class="clean-hero__title">Live TV, radio, <span>events, and video</span></h1>
                            <p class="clean-hero__description">{{ $featuredDescription }}</p>
                            <div class="clean-actions">
                                <a href="{{ route('tvs') }}" class="clean-btn clean-btn--primary">Browse Live TV</a>
                                <a href="{{ route('videos') }}" class="clean-btn clean-btn--ghost">Watch Videos</a>
                            </div>
                        @endif

                        @if($heroGenres->isNotEmpty())
                            <div class="clean-meta">
                                @foreach($heroGenres as $genre)
                                    <a href="{{ route('genre.tvs', ['genre' => Str::slug($genre)]) }}" class="clean-chip">{{ ucfirst($genre) }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="clean-hero__aside">
                        <div class="clean-panel">
                            <h2 class="clean-panel__title">Live Now in {{ $country_name ?? 'your region' }}</h2>

                            <div class="clean-live-list">
                                @forelse($heroLiveChannels as $item)
                                    <a href="{{ route('tv.show', $item->slug) }}" class="clean-live-item">
                                        <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy">
                                        <div>
                                            <strong>{{ ucfirst($item->title) }}</strong>
                                            <small>Live TV</small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="clean-live-item">
                                        <img src="{{ asset('frontend-assets/images/default.png') }}" alt="Nowstream" loading="lazy">
                                        <div>
                                            <strong>Content is loading</strong>
                                            <small>Check back for fresh live picks</small>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="clean-link-grid">
            @foreach($quickLinks as $link)
                <a href="{{ $link['route'] }}" class="clean-link-card">
                    <h2 class="clean-link-card__title">{{ $link['title'] }}</h2>
                    <p class="clean-link-card__meta">{{ $link['meta'] }}</p>
                </a>
            @endforeach
        </div>

        <section class="clean-section clean-slider" data-slider="events">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Pay Per View</p>
                    <h2 class="clean-section__title">Trending Events</h2>
                    <p class="clean-section__sub">The biggest live nights, all in one clean row.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="events" aria-label="Previous trending events">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="events" aria-label="Next trending events">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                    <a href="{{ route('events') }}" class="clean-section__link">View All</a>
                </div>
            </div>

            <div class="clean-track clean-track--event" data-slider-track="events">
                @foreach($eventShelf as $event)
                    @php
                        $eventImage = $event->event_image
                            ? Storage::disk(config('filesystems.default'))->url($event->event_image)
                            : asset('frontend-assets/images/default.png');
                        $ticket = optional($event->eventRates)->sortBy('price')->first();
                    @endphp
                    <a href="{{ route('event.show', $event->slug) }}" class="clean-shelf-card">
                        <div class="clean-shelf-card__media">
                            <img src="{{ $eventImage }}" alt="{{ $event->event_name }}" loading="lazy">
                            <span class="clean-badge">Live</span>
                        </div>
                        <div class="clean-shelf-card__body">
                            <h3 class="clean-shelf-card__title">{{ $event->event_name }}</h3>
                            <div class="clean-shelf-card__meta">
                                @if($event->start_time)
                                    <span>{{ Carbon::parse($event->start_time)->format('M d') }}</span>
                                @endif
                                @if($event->venue)
                                    <span>{{ $event->venue }}</span>
                                @endif
                                <span>{{ $ticket ? 'From KES ' . number_format($ticket->price) : 'Free' }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        @if($videoFeatureShelf->isNotEmpty())
            <section class="clean-section clean-slider" data-slider="featured-videos">
                <div class="clean-section__head">
                    <div>
                        <p class="clean-section__eyebrow">Watch Now</p>
                        <h2 class="clean-section__title">Trending Videos</h2>
                        <p class="clean-section__sub">A sharper, lighter presentation for your on-demand highlights.</p>
                    </div>
                    <div class="clean-slider__controls">
                        <button type="button" class="clean-slider__button" data-slider-prev="featured-videos" aria-label="Previous trending videos">
                            <span class="clean-slider__icon">&larr;</span>
                        </button>
                        <button type="button" class="clean-slider__button" data-slider-next="featured-videos" aria-label="Next trending videos">
                            <span class="clean-slider__icon">&rarr;</span>
                        </button>
                        <a href="{{ route('videos') }}" class="clean-section__link">View All</a>
                    </div>
                </div>

                <div class="clean-track clean-track--video" data-slider-track="featured-videos">
                    @foreach($videoFeatureShelf as $video)
                        <a href="{{ route('video.show', [$video->uuid, $video->slug]) }}" class="clean-video-card">
                            <div class="clean-video-card__media">
                                <img src="{{ $imageForContent($video) }}" alt="{{ $video->title }}" loading="lazy">
                                <span class="clean-badge">{{ $labelForContent($video) }}</span>
                            </div>
                            <div class="clean-video-card__body">
                                <h3 class="clean-video-card__title">{{ ucfirst($video->title) }}</h3>
                                <div class="clean-video-card__meta">
                                    <span>{{ number_format($video->views ?? 0) }} views</span>
                                    @if($video->duration)
                                        <span>{{ $video->duration }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="clean-section clean-slider" data-slider="tv">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Country Picks</p>
                    <h2 class="clean-section__title">Top TV in {{ $country_name ?? 'your region' }}</h2>
                    <p class="clean-section__sub">Fast access to the channels people are already watching.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="tv" aria-label="Previous live TV items">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="tv" aria-label="Next live TV items">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                    <a href="{{ route('tvs') }}" class="clean-section__link">View All</a>
                </div>
            </div>

            <div class="clean-track" data-slider-track="tv">
                @foreach($tvShelf as $item)
                    <a href="{{ $routeForContent($item) }}" class="clean-shelf-card">
                        <div class="clean-shelf-card__media">
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="clean-badge">Live</span>
                        </div>
                        <div class="clean-shelf-card__body">
                            <h3 class="clean-shelf-card__title">{{ ucfirst($item->title) }}</h3>
                            <div class="clean-shelf-card__meta">
                                <span>{{ $labelForContent($item) }}</span>
                                @if($item->country)
                                    <span>{{ $item->country }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="clean-section clean-slider" data-slider="radio">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Listen Live</p>
                    <h2 class="clean-section__title">Trending Radios</h2>
                    <p class="clean-section__sub">Live audio stations presented with the same visual order as video.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="radio" aria-label="Previous radio items">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="radio" aria-label="Next radio items">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                    <a href="{{ route('radios') }}" class="clean-section__link">View All</a>
                </div>
            </div>

            <div class="clean-track" data-slider-track="radio">
                @foreach($radioShelf as $item)
                    <a href="{{ $routeForContent($item) }}" class="clean-shelf-card">
                        <div class="clean-shelf-card__media">
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="clean-badge">{{ $labelForContent($item) }}</span>
                        </div>
                        <div class="clean-shelf-card__body">
                            <h3 class="clean-shelf-card__title">{{ ucfirst($item->title) }}</h3>
                            <div class="clean-shelf-card__meta">
                                @if($item->country)
                                    <span>{{ $item->country }}</span>
                                @endif
                                <span>Stream ready</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="clean-section clean-slider" data-slider="latest-videos">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Fresh Drops</p>
                    <h2 class="clean-section__title">Latest Videos</h2>
                    <p class="clean-section__sub">Recently added clips and uploads, without the heavy old homepage chrome.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="latest-videos" aria-label="Previous latest videos">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="latest-videos" aria-label="Next latest videos">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                    <a href="{{ route('videos') }}" class="clean-section__link">View All</a>
                </div>
            </div>

            <div class="clean-track" data-slider-track="latest-videos">
                @foreach($videoShelf as $item)
                    <a href="{{ route('video.show', [$item->uuid, $item->slug]) }}" class="clean-shelf-card">
                        <div class="clean-shelf-card__media">
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="clean-badge">{{ $labelForContent($item) }}</span>
                        </div>
                        <div class="clean-shelf-card__body">
                            <h3 class="clean-shelf-card__title">{{ ucfirst($item->title) }}</h3>
                            <div class="clean-shelf-card__meta">
                                <span>{{ number_format($item->views ?? 0) }} views</span>
                                @if($item->country)
                                    <span>{{ $item->country }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="clean-section clean-slider" data-slider="genres">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Explore</p>
                    <h2 class="clean-section__title">Browse By Genre</h2>
                    <p class="clean-section__sub">A cleaner way into the long tail of your TV catalog.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="genres" aria-label="Previous genres">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="genres" aria-label="Next genres">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                </div>
            </div>

            <div class="clean-track" data-slider-track="genres" style="grid-auto-columns: minmax(240px, 240px);">
                @foreach($heroGenres as $genre)
                    <a href="{{ route('genre.tvs', ['genre' => Str::slug($genre)]) }}" class="clean-genre-card">
                        <h3 class="clean-genre-card__label">{{ ucfirst($genre) }}</h3>
                        <p class="clean-genre-card__meta">Open TV channels tagged under this genre.</p>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="clean-section clean-slider" data-slider="podcasts">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Listen Later</p>
                    <h2 class="clean-section__title">Trending Podcasts</h2>
                    <p class="clean-section__sub">Long-form listening gets its own cleaner shelf instead of feeling buried.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="podcasts" aria-label="Previous podcasts">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="podcasts" aria-label="Next podcasts">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                    <a href="{{ route('podcasts') }}" class="clean-section__link">View All</a>
                </div>
            </div>

            <div class="clean-track" data-slider-track="podcasts">
                @foreach($podcastShelf as $item)
                    <a href="{{ $routeForContent($item) }}" class="clean-shelf-card">
                        <div class="clean-shelf-card__media">
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="clean-badge">{{ $labelForContent($item) }}</span>
                        </div>
                        <div class="clean-shelf-card__body">
                            <h3 class="clean-shelf-card__title">{{ ucfirst($item->title) }}</h3>
                            <div class="clean-shelf-card__meta">
                                @if($item->author)
                                    <span>{{ $item->author }}</span>
                                @endif
                                <span>On demand</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="clean-section clean-slider" data-slider="latest-podcasts">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Just Added</p>
                    <h2 class="clean-section__title">Latest Podcasts</h2>
                    <p class="clean-section__sub">Recent podcast additions with the same shelf rhythm as the rest of the home.</p>
                </div>
                <div class="clean-slider__controls">
                    <button type="button" class="clean-slider__button" data-slider-prev="latest-podcasts" aria-label="Previous latest podcasts">
                        <span class="clean-slider__icon">&larr;</span>
                    </button>
                    <button type="button" class="clean-slider__button" data-slider-next="latest-podcasts" aria-label="Next latest podcasts">
                        <span class="clean-slider__icon">&rarr;</span>
                    </button>
                    <a href="{{ route('podcasts') }}" class="clean-section__link">View All</a>
                </div>
            </div>

            <div class="clean-track" data-slider-track="latest-podcasts">
                @foreach($latestPodcastShelf as $item)
                    <a href="{{ $routeForContent($item) }}" class="clean-shelf-card">
                        <div class="clean-shelf-card__media">
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="clean-badge">{{ $labelForContent($item) }}</span>
                        </div>
                        <div class="clean-shelf-card__body">
                            <h3 class="clean-shelf-card__title">{{ ucfirst($item->title) }}</h3>
                            <div class="clean-shelf-card__meta">
                                <span>{{ number_format($item->views ?? 0) }} listens</span>
                                @if($item->country)
                                    <span>{{ $item->country }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sliders = document.querySelectorAll('[data-slider]');

        const getStepSize = (track) => {
            const firstCard = track.firstElementChild;

            if (!firstCard) {
                return track.clientWidth * 0.9;
            }

            const trackStyle = window.getComputedStyle(track);
            const gap = parseFloat(trackStyle.columnGap || trackStyle.gap || 0);

            return firstCard.getBoundingClientRect().width + gap;
        };

        sliders.forEach((slider) => {
            const sliderName = slider.getAttribute('data-slider');
            const track = slider.querySelector(`[data-slider-track="${sliderName}"]`);
            const prevButton = slider.querySelector(`[data-slider-prev="${sliderName}"]`);
            const nextButton = slider.querySelector(`[data-slider-next="${sliderName}"]`);

            if (!track || !prevButton || !nextButton) {
                return;
            }

            const updateButtons = () => {
                const maxScrollLeft = Math.max(track.scrollWidth - track.clientWidth - 4, 0);
                prevButton.disabled = track.scrollLeft <= 4;
                nextButton.disabled = track.scrollLeft >= maxScrollLeft;
            };

            const scrollTrack = (direction) => {
                track.scrollBy({
                    left: getStepSize(track) * direction * 2,
                    behavior: 'smooth',
                });
            };

            let isDragging = false;
            let startX = 0;
            let startScrollLeft = 0;

            prevButton.addEventListener('click', () => scrollTrack(-1));
            nextButton.addEventListener('click', () => scrollTrack(1));
            track.addEventListener('scroll', updateButtons, { passive: true });

            track.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'mouse' && event.button !== 0) {
                    return;
                }

                isDragging = true;
                startX = event.clientX;
                startScrollLeft = track.scrollLeft;
                track.classList.add('is-dragging');
                track.setPointerCapture(event.pointerId);
            });

            track.addEventListener('pointermove', (event) => {
                if (!isDragging) {
                    return;
                }

                const deltaX = event.clientX - startX;
                track.scrollLeft = startScrollLeft - deltaX;
            });

            const stopDragging = (event) => {
                if (!isDragging) {
                    return;
                }

                isDragging = false;
                track.classList.remove('is-dragging');

                if (event && typeof track.releasePointerCapture === 'function') {
                    try {
                        track.releasePointerCapture(event.pointerId);
                    } catch (error) {
                        // Ignore pointer capture release errors.
                    }
                }

                updateButtons();
            };

            track.addEventListener('pointerup', stopDragging);
            track.addEventListener('pointercancel', stopDragging);
            track.addEventListener('pointerleave', (event) => {
                if (event.pointerType === 'mouse') {
                    stopDragging(event);
                }
            });

            window.addEventListener('resize', updateButtons);
            updateButtons();
        });
    });
</script>
@endsection
