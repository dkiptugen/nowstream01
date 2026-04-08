@extends('Frontend.includes.layout')

@php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$heroEvents = $heroEvents ?? (($topevents ?? collect())->take(3)->values());
if ($heroEvents->isEmpty() && isset($events)) {
    $heroEvents = collect($events)->take(3)->values();
}

$heroGenres = $heroGenres ?? collect($genres ?? [])->filter()->unique()->take(8)->values();
$heroLiveChannels = $heroLiveChannels ?? collect($toptvs ?? [])->shuffle()->take(4)->values();
$eventShelf = $eventShelf ?? collect($topevents ?? [])->take(8)->values();
$tvShelf = $tvShelf ?? collect($toptvs ?? [])->take(12)->values();
$radioShelf = $radioShelf ?? collect($topradios ?? [])->take(12)->values();
$videoFeatureShelf = $videoFeatureShelf ?? collect($top_videos ?? [])->take(4)->values();
$videoShelf = $videoShelf ?? collect($videos ?? [])->take(8)->values();
$podcastShelf = $podcastShelf ?? collect($podcasts ?? [])->take(12)->values();
$latestPodcastShelf = $latestPodcastShelf ?? collect($topPodcasts ?? [])->take(12)->values();
$quickLinks = collect($quickLinks ?? [
    ['title' => 'Live TV', 'meta' => 'Channels', 'icon' => 'bx bx-tv', 'route' => route('tvs')],
    ['title' => 'Radio', 'meta' => 'Live audio', 'icon' => 'bx bx-broadcast', 'route' => route('radios')],
    ['title' => 'Videos', 'meta' => 'Watch now', 'icon' => 'bx bx-camera-movie', 'route' => route('videos')],
    ['title' => 'Podcasts', 'meta' => 'Listen', 'icon' => 'bx bx-microphone-alt', 'route' => route('podcasts')],
    ['title' => 'Events', 'meta' => 'Tickets', 'icon' => 'bx bx-calendar-event', 'route' => route('events')],
]);

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
        padding: 0 0 72px;
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
        top: 0;
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 0;
        min-height: 720px;
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

    .clean-hero__bg,
    .clean-hero__slide {
        position: absolute;
        inset: 0;
    }

    .clean-hero__bg {
        z-index: 0;
    }

    .clean-hero__slide {
        background-size: cover;
        background-position: center;
        transform: scale(1.04);
        filter: saturate(1.04);
        opacity: 0;
        transition: opacity 0.9s ease;
    }

    .clean-hero__slide.is-active {
        opacity: 1;
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
        min-height: 720px;
        padding: 88px 56px 72px;
    }

    .clean-hero__carousel {
        position: relative;
        min-height: 720px;
    }

    .clean-hero__copy {
        position: absolute;
        inset: 80px auto 72px 56px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        max-width: 640px;
        width: min(100%, 640px);
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.55s ease, transform 0.55s ease;
        pointer-events: none;
    }

    .clean-hero__copy.is-active {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .clean-hero__controls {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 22px;
    }

    .clean-hero__dot {
        width: 10px;
        height: 10px;
        padding: 0;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.35);
        transition: transform 0.22s ease, background-color 0.22s ease, width 0.22s ease;
    }

    .clean-hero__dot.is-active {
        width: 28px;
        background: #ffd24f;
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
        font-size: clamp(2.8rem, 4.2vw, 4.9rem);
        line-height: 1.02;
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
        line-height: 1.8;
        overflow-wrap: anywhere;
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
        padding: 32px 32px 40px 12px;
    }

    .clean-panel {
        width: 100%;
        max-width: 320px;
        margin-left: auto;
        padding: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(7, 15, 24, 0.86), rgba(7, 15, 24, 0.74));
        backdrop-filter: blur(16px);
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.22);
    }

    .clean-panel__title {
        margin: 0 0 14px;
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.3;
    }

    .clean-panel__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #8fd7ff;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .clean-panel__eyebrow::before {
        content: "";
        width: 18px;
        height: 1px;
        background: rgba(143, 215, 255, 0.5);
    }

    .clean-live-list {
        display: grid;
        gap: 10px;
    }

    .clean-live-item {
        display: grid;
        grid-template-columns: 48px minmax(0, 1fr);
        gap: 10px;
        align-items: center;
        padding: 8px 10px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.045);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .clean-live-item img {
        width: 48px;
        height: 48px;
        border-radius: 12px;
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
        gap: 12px;
        margin-top: 18px;
        content-visibility: auto;
        contain-intrinsic-size: 1px 180px;
    }

    .clean-link-card {
        min-height: 84px;
        padding: 14px 15px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        background: linear-gradient(180deg, rgba(15, 31, 45, 0.9), rgba(8, 17, 28, 0.92));
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        transition: transform 0.22s ease, border-color 0.22s ease;
    }

    .clean-link-card__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        margin-bottom: 10px;
        border-radius: 12px;
        background: rgba(143, 215, 255, 0.12);
        color: #8fd7ff;
        font-size: 18px;
    }

    .clean-link-card:hover,
    .clean-shelf-card:hover,
    .clean-video-card:hover,
    .clean-genre-card:hover {
        transform: translateY(-3px);
        border-color: rgba(143, 215, 255, 0.35);
    }

    .clean-link-card__title {
        margin: 0 0 4px;
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .clean-link-card__meta {
        margin: 0;
        font-size: 12px;
        line-height: 1.45;
    }

    .clean-panel--mobile {
        display: none;
        margin-top: 14px;
    }

    .clean-section {
        margin-top: 38px;
        content-visibility: auto;
        contain-intrinsic-size: 1px 760px;
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
        white-space: nowrap;
    }

    .clean-track {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: minmax(190px, 190px);
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: none;
        -ms-overflow-style: none;
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
            min-height: 640px;
        }

        .clean-hero__content {
            min-height: 640px;
            padding: 56px 34px 44px;
        }

        .clean-hero__carousel {
            min-height: 640px;
        }

        .clean-hero__copy {
            inset: 54px auto 44px 34px;
            max-width: calc(100% - 68px);
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

    @media (max-width: 991px) {
        .clean-home {
            padding-bottom: 56px;
        }

        .clean-home .container {
            padding-left: 18px;
            padding-right: 18px;
        }

        .clean-hero::before {
            background:
                linear-gradient(180deg, rgba(5, 10, 15, 0.9) 0%, rgba(5, 10, 15, 0.62) 42%, rgba(5, 10, 15, 0.9) 100%),
                linear-gradient(180deg, rgba(8, 17, 28, 0.15) 0%, rgba(8, 17, 28, 0.78) 100%);
        }

        .clean-hero__content {
            min-height: 620px;
            padding: 52px 34px 40px;
        }

        .clean-hero__copy {
            inset: 48px 34px 40px 34px;
            width: auto;
            max-width: none;
        }

        .clean-hero__title {
            font-size: clamp(2.3rem, 8vw, 4rem);
        }

        .clean-actions {
            gap: 12px;
        }

        .clean-btn {
            flex: 1 1 220px;
        }

        .clean-hero__aside {
            padding: 0 34px 34px;
        }

        .clean-panel {
            max-width: none;
        }

        .clean-link-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .clean-section {
            margin-top: 30px;
        }

        .clean-section__head {
            align-items: flex-start;
            flex-direction: column;
        }

        .clean-slider__controls {
            width: 100%;
            justify-content: space-between;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 767px) {
        .clean-home {
            padding-top: 0;
        }

        .clean-hero {
            border-radius: 0;
            min-height: 580px;
        }

        .clean-hero__content {
            min-height: 580px;
            padding: 34px 18px 34px;
        }

        .clean-hero__copy {
            inset: 34px 18px 34px 18px;
            max-width: none;
        }

        .clean-hero__aside {
            display: none;
        }

        .clean-panel {
            max-width: none;
            padding: 16px;
            border-radius: 18px;
        }

        .clean-panel--mobile {
            display: block;
        }

        .clean-link-grid,
        .clean-genre-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .clean-eyebrow {
            margin-bottom: 10px;
            letter-spacing: 0.14em;
            font-size: 10px;
        }

        .clean-hero__title {
            font-size: clamp(2.2rem, 10vw, 3.2rem);
            max-width: 100%;
        }

        .clean-hero__description {
            margin-top: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 13px;
            line-height: 1.5;
        }

        .clean-meta {
            gap: 8px;
            margin-top: 16px;
        }

        .clean-meta span:nth-child(n+4),
        .clean-meta .clean-chip:nth-child(n+5) {
            display: none;
        }

        .clean-meta span,
        .clean-chip {
            min-height: 34px;
            padding: 8px 12px;
            font-size: 11px;
        }

        .clean-actions {
            margin-top: 18px;
        }

        .clean-btn {
            width: 100%;
            min-height: 46px;
        }

        .clean-live-item {
            grid-template-columns: 42px minmax(0, 1fr);
            gap: 8px;
        }

        .clean-live-item img {
            width: 42px;
            height: 42px;
            border-radius: 10px;
        }

        .clean-link-card,
        .clean-shelf-card,
        .clean-video-card,
        .clean-genre-card {
            border-radius: 18px;
        }

        .clean-link-card {
            min-height: auto;
            padding: 12px;
        }

        .clean-link-card__icon {
            width: 30px;
            height: 30px;
            margin-bottom: 8px;
            border-radius: 10px;
            font-size: 16px;
        }

        .clean-link-card__title {
            font-size: 14px;
        }

        .clean-link-card__meta {
            font-size: 11px;
        }

        .clean-section {
            margin-top: 24px;
        }

        .clean-section__eyebrow,
        .clean-section__sub {
            display: none;
        }

        .clean-section__title {
            font-size: 1.2rem;
        }

        .clean-section__head {
            margin-bottom: 12px;
        }

        .clean-slider__button {
            width: 38px;
            height: 38px;
        }

        .clean-slider__controls {
            justify-content: space-between;
        }

        .clean-track {
            grid-auto-columns: minmax(58vw, 58vw);
            gap: 12px;
        }

        .clean-track--event {
            grid-auto-columns: minmax(64vw, 64vw);
        }

        .clean-track--video {
            grid-auto-columns: minmax(72vw, 72vw);
        }

        .clean-shelf-card__body,
        .clean-video-card__body,
        .clean-genre-card {
            padding: 12px;
        }

        .clean-shelf-card__title,
        .clean-video-card__title {
            font-size: 14px;
        }

        .clean-shelf-card__meta,
        .clean-video-card__meta {
            font-size: 11px;
            gap: 8px;
        }
    }

    @media (max-width: 380px) {
        .clean-link-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@section('content')
<div class="clean-home">
    <div class="container">
        <section class="clean-hero">
            <div class="clean-hero__bg" data-hero-slider>
                @forelse($heroEvents as $index => $heroEvent)
                    @php
                        $heroEventImage = $heroEvent->event_image
                            ? Storage::disk(config('filesystems.default'))->url($heroEvent->event_image)
                            : ($top_videos->first()->thumbnail_url ?? asset('frontend-assets/images/default.png'));
                    @endphp
                    <div
                        class="clean-hero__slide {{ $index === 0 ? 'is-active' : '' }}"
                        data-hero-slide
                        style="background-image: url('{{ $heroEventImage }}');"
                    ></div>
                @empty
                    <div
                        class="clean-hero__slide is-active"
                        data-hero-slide
                        style="background-image: url('{{ $top_videos->first()->thumbnail_url ?? asset('frontend-assets/images/default.png') }}');"
                    ></div>
                @endforelse
            </div>
            <div class="row g-0">
                <div class="col-xl-8">
                    <div class="clean-hero__content">
                        <div class="clean-hero__carousel">
                            @forelse($heroEvents as $index => $heroEvent)
                                @php
                                    $titleWords = preg_split('/\s+/', trim($heroEvent->event_name));
                                    $splitPoint = (int) ceil(count($titleWords) / 2);
                                    $titleStart = implode(' ', array_slice($titleWords, 0, $splitPoint));
                                    $titleEnd = implode(' ', array_slice($titleWords, $splitPoint));
                                    $heroDescription = Str::limit(trim(strip_tags($heroEvent->description ?? 'Watch the biggest live moments and jump straight into what is on.')), 92);
                                @endphp
                                <div class="clean-hero__copy {{ $index === 0 ? 'is-active' : '' }}" data-hero-copy>
                                    <div class="clean-eyebrow">Now Playing</div>
                                    <h1 class="clean-hero__title">
                                        {{ $titleStart }}
                                        @if($titleEnd)
                                            <span>{{ $titleEnd }}</span>
                                        @endif
                                    </h1>

                                    <p class="clean-hero__description">{{ $heroDescription }}</p>

                                    <div class="clean-meta">
                                        <span>Featured</span>
                                        @if($heroEvent->start_time)
                                            <span>{{ Carbon::parse($heroEvent->start_time)->format('M d') }}</span>
                                        @endif
                                        @if($heroEvent->venue)
                                            <span>{{ $heroEvent->venue }}</span>
                                        @endif
                                        <span>{{ $topevents->count() }} picks</span>
                                    </div>

                                    <div class="clean-actions">
                                        <a href="{{ route('event.show', $heroEvent->slug) }}" class="clean-btn clean-btn--primary">Play</a>
                                        <a href="{{ route('tvs') }}" class="clean-btn clean-btn--ghost">Browse</a>
                                    </div>

                                    <div class="clean-hero__controls">
                                        @foreach($heroEvents as $dotIndex => $dotEvent)
                                            <button
                                                type="button"
                                                class="clean-hero__dot {{ $dotIndex === $index ? 'is-active' : '' }}"
                                                data-hero-dot="{{ $dotIndex }}"
                                                aria-label="Show hero event {{ $dotIndex + 1 }}"
                                            ></button>
                                        @endforeach
                                    </div>

                                    @if($heroGenres->isNotEmpty())
                                        <div class="clean-meta">
                                            @foreach($heroGenres as $genre)
                                                <a href="{{ route('genre.tvs', ['genre' => Str::slug($genre)]) }}" class="clean-chip">{{ ucfirst($genre) }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="clean-hero__copy is-active" data-hero-copy>
                                    <div class="clean-eyebrow">Now Playing</div>
                                    <h1 class="clean-hero__title">Live TV, radio, <span>events, and video</span></h1>
                                    <p class="clean-hero__description">Find something to watch fast.</p>
                                    <div class="clean-actions">
                                        <a href="{{ route('tvs') }}" class="clean-btn clean-btn--primary">Browse</a>
                                        <a href="{{ route('videos') }}" class="clean-btn clean-btn--ghost">Videos</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="clean-hero__aside">
                        <div class="clean-panel">
                            <div class="clean-panel__eyebrow">Live Now</div>
                            <h2 class="clean-panel__title">{{ $country_name ?? 'Your region' }}</h2>

                            <div class="clean-live-list">
                                @forelse($heroLiveChannels as $item)
                                    <a href="{{ route('tv.show', $item->slug) }}" class="clean-live-item">
                                        <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
                                        <div>
                                            <strong>{{ ucfirst($item->title) }}</strong>
                                            <small>Live TV</small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="clean-live-item">
                                        <img src="{{ asset('frontend-assets/images/default.png') }}" alt="Nowstream" loading="lazy" decoding="async" fetchpriority="low">
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

        <section class="clean-panel clean-panel--mobile">
            <div class="clean-panel__eyebrow">Live Now</div>
            <h2 class="clean-panel__title">{{ $country_name ?? 'Your region' }}</h2>

            <div class="clean-live-list">
                @forelse($heroLiveChannels as $item)
                    <a href="{{ route('tv.show', $item->slug) }}" class="clean-live-item">
                        <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
                        <div>
                            <strong>{{ ucfirst($item->title) }}</strong>
                            <small>Live TV</small>
                        </div>
                    </a>
                @empty
                    <div class="clean-live-item">
                        <img src="{{ asset('frontend-assets/images/default.png') }}" alt="Nowstream" loading="lazy" decoding="async" fetchpriority="low">
                        <div>
                            <strong>Content is loading</strong>
                            <small>Check back for fresh live picks</small>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <div class="clean-link-grid">
            @foreach($quickLinks as $link)
                <a href="{{ $link['route'] }}" class="clean-link-card">
                    @if(!empty($link['icon']))
                        <span class="clean-link-card__icon"><i class="{{ $link['icon'] }}"></i></span>
                    @endif
                    <h2 class="clean-link-card__title">{{ $link['title'] }}</h2>
                    <p class="clean-link-card__meta">{{ $link['meta'] }}</p>
                </a>
            @endforeach
        </div>

        <section class="clean-section clean-slider" data-slider="events">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">Live</p>
                    <h2 class="clean-section__title">Events</h2>
                    <p class="clean-section__sub">Big live nights.</p>
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
                            <img src="{{ $eventImage }}" alt="{{ $event->event_name }}" loading="lazy" decoding="async" fetchpriority="low">
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
                        <p class="clean-section__eyebrow">Watch</p>
                        <h2 class="clean-section__title">Videos</h2>
                        <p class="clean-section__sub">Top picks.</p>
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
                                <img src="{{ $imageForContent($video) }}" alt="{{ $video->title }}" loading="lazy" decoding="async" fetchpriority="low">
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
                    <p class="clean-section__eyebrow">Live</p>
                    <h2 class="clean-section__title">TV in {{ $country_name ?? 'your region' }}</h2>
                    <p class="clean-section__sub">Popular channels.</p>
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
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
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
                    <p class="clean-section__eyebrow">Listen</p>
                    <h2 class="clean-section__title">Radio</h2>
                    <p class="clean-section__sub">Live stations.</p>
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
                    <div
                        class="clean-shelf-card"
                        role="button"
                        tabindex="0"
                        onclick="playSingleAudio('{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('stream.view', now()->addMinutes(30), ['streamId' => $item->uuid]) }}', '{{ addslashes($item->title) }}', 'Live radio', '{{ $imageForContent($item) }}', '{{ $item->uuid }}')"
                        onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); playSingleAudio('{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('stream.view', now()->addMinutes(30), ['streamId' => $item->uuid]) }}', '{{ addslashes($item->title) }}', 'Live radio', '{{ $imageForContent($item) }}', '{{ $item->uuid }}'); }"
                    >
                        <div class="clean-shelf-card__media">
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
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
                    </div>
                @endforeach
            </div>
        </section>

        <section class="clean-section clean-slider" data-slider="latest-videos">
            <div class="clean-section__head">
                <div>
                    <p class="clean-section__eyebrow">New</p>
                    <h2 class="clean-section__title">Latest Videos</h2>
                    <p class="clean-section__sub">Just added.</p>
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
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
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
                    <p class="clean-section__eyebrow">Browse</p>
                    <h2 class="clean-section__title">Genres</h2>
                    <p class="clean-section__sub">Jump in fast.</p>
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
                    <p class="clean-section__eyebrow">Listen</p>
                    <h2 class="clean-section__title">Podcasts</h2>
                    <p class="clean-section__sub">Top shows.</p>
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
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
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
                    <p class="clean-section__eyebrow">New</p>
                    <h2 class="clean-section__title">Latest Podcasts</h2>
                    <p class="clean-section__sub">Recently added.</p>
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
                            <img src="{{ $imageForContent($item) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
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
        const heroSlides = Array.from(document.querySelectorAll('[data-hero-slide]'));
        const heroCopies = Array.from(document.querySelectorAll('[data-hero-copy]'));

        if (heroSlides.length > 1 && heroCopies.length === heroSlides.length) {
            let activeHeroIndex = 0;
            let heroTimer = null;

            const setHeroSlide = (index) => {
                activeHeroIndex = index;

                heroSlides.forEach((slide, slideIndex) => {
                    slide.classList.toggle('is-active', slideIndex === index);
                });

                heroCopies.forEach((copy, copyIndex) => {
                    copy.classList.toggle('is-active', copyIndex === index);

                    copy.querySelectorAll('[data-hero-dot]').forEach((dot) => {
                        dot.classList.toggle('is-active', Number(dot.getAttribute('data-hero-dot')) === index);
                    });
                });
            };

            const startHeroAutoplay = () => {
                heroTimer = window.setInterval(() => {
                    const nextIndex = (activeHeroIndex + 1) % heroSlides.length;
                    setHeroSlide(nextIndex);
                }, 5000);
            };

            const restartHeroAutoplay = () => {
                if (heroTimer) {
                    window.clearInterval(heroTimer);
                }

                startHeroAutoplay();
            };

            document.querySelectorAll('[data-hero-dot]').forEach((dot) => {
                dot.addEventListener('click', () => {
                    const index = Number(dot.getAttribute('data-hero-dot'));
                    setHeroSlide(index);
                    restartHeroAutoplay();
                });
            });

            setHeroSlide(0);
            startHeroAutoplay();
        }

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
            let hasDragged = false;
            let startX = 0;
            let startScrollLeft = 0;
            let activePointerId = null;

            prevButton.addEventListener('click', () => scrollTrack(-1));
            nextButton.addEventListener('click', () => scrollTrack(1));
            track.addEventListener('scroll', updateButtons, { passive: true });

            track.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'mouse' && event.button !== 0) {
                    return;
                }

                isDragging = true;
                hasDragged = false;
                activePointerId = event.pointerId;
                startX = event.clientX;
                startScrollLeft = track.scrollLeft;
            });

            track.addEventListener('pointermove', (event) => {
                if (!isDragging || event.pointerId !== activePointerId) {
                    return;
                }

                const deltaX = event.clientX - startX;

                 if (!hasDragged && Math.abs(deltaX) > 6) {
                    hasDragged = true;
                    track.classList.add('is-dragging');
                    track.setPointerCapture(event.pointerId);
                }

                if (!hasDragged) {
                    return;
                }

                track.scrollLeft = startScrollLeft - deltaX;
            });

            const stopDragging = (event) => {
                if (!isDragging || (event && event.pointerId !== activePointerId)) {
                    return;
                }

                isDragging = false;
                track.classList.remove('is-dragging');
                activePointerId = null;

                if (hasDragged && event && typeof track.releasePointerCapture === 'function') {
                    try {
                        track.releasePointerCapture(event.pointerId);
                    } catch (error) {
                        // Ignore pointer capture release errors.
                    }
                }

                updateButtons();
            };

            track.addEventListener('click', (event) => {
                if (!hasDragged) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();
                hasDragged = false;
            }, true);

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
