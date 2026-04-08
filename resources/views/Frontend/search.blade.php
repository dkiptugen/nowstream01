@extends('Frontend.includes.layout')

@section('content')
<style>
    .search-page {
        padding: 112px 0 64px;
        min-height: 70vh;
        background:
            radial-gradient(circle at top, rgba(24, 92, 145, 0.16), transparent 28%),
            linear-gradient(180deg, #06111d 0%, #08131b 30%, #050b11 100%);
    }

    .search-shell {
        max-width: 1240px;
    }

    .search-hero,
    .search-group,
    .search-empty {
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        background: linear-gradient(180deg, rgba(9, 19, 29, 0.94), rgba(7, 15, 24, 0.9));
        box-shadow: 0 28px 64px rgba(0, 0, 0, 0.22);
    }

    .search-hero {
        padding: 28px;
    }

    .search-kicker {
        margin: 0 0 8px;
        color: #8fd7ff;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .search-title {
        color: #fff;
        font-size: clamp(2rem, 4vw, 3.3rem);
        line-height: 1.02;
        letter-spacing: -0.03em;
        margin-bottom: 0.85rem;
    }

    .search-muted {
        color: rgba(231, 238, 247, 0.72);
    }

    .search-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 1.4rem;
    }

    .search-field {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1 1 auto;
        min-height: 58px;
        padding: 0 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.05);
    }

    .search-field i {
        color: #8fd7ff;
        font-size: 20px;
    }

    .search-input {
        width: 100%;
        border: 0;
        background: transparent;
        color: #fff;
        padding: 0;
        font-size: 15px;
    }

    .search-input:focus {
        outline: none;
    }

    .search-btn {
        border: 0;
        border-radius: 18px;
        min-height: 58px;
        padding: 0 1.35rem;
        background: linear-gradient(135deg, #ffd24f, #f7a400);
        color: #09131d;
        font-weight: 800;
        font-size: 0.82rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .search-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }

    .search-count,
    .search-summary__query {
        display: inline-flex;
        align-items: center;
        min-height: 36px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.9rem;
    }

    .search-count {
        background: rgba(143, 215, 255, 0.12);
        color: #d7f0ff;
    }

    .search-summary__query {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(231, 238, 247, 0.72);
    }

    .search-empty {
        padding: 28px;
    }

    .search-empty h2 {
        color: #ffffff;
        font-size: 1.3rem;
        margin-bottom: 8px;
    }

    .search-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.2rem;
    }

    .search-tab {
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 999px;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        color: #cbd5e1;
        font-weight: 700;
    }

    .search-tab.active,
    .search-tab:hover,
    .search-tab:focus {
        background: rgba(255, 210, 79, 0.14);
        border-color: rgba(255, 210, 79, 0.3);
        color: #fff;
    }

    .search-tab-pane {
        display: none;
    }

    .search-tab-pane.active {
        display: block;
    }

    .search-group {
        padding: 24px;
    }

    .search-group__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .search-group__title {
        color: #fff;
        font-size: 1.35rem;
        margin: 0;
    }

    .search-card {
        display: block;
        height: 100%;
        padding: 0.9rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: inherit;
        text-decoration: none;
        transition: transform 0.18s ease, border-color 0.18s ease;
    }

    .search-card:hover {
        transform: translateY(-3px);
        border-color: rgba(143, 215, 255, 0.3);
        text-decoration: none;
    }

    .search-card__media {
        width: 100%;
        aspect-ratio: 16 / 10;
        border-radius: 16px;
        object-fit: cover;
        background: linear-gradient(135deg, #1e293b, #334155);
        margin-bottom: 0.9rem;
    }

    .search-card__type {
        display: inline-block;
        margin-bottom: 0.6rem;
        padding: 0.32rem 0.65rem;
        border-radius: 999px;
        background: rgba(143, 215, 255, 0.12);
        color: #d7f0ff;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .search-card__title {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.45rem;
    }

    .search-card__description,
    .search-card__meta {
        color: #cbd5e1;
        font-size: 0.92rem;
        margin: 0;
    }

    .search-card__meta {
        margin-top: 0.6rem;
        color: #94a3b8;
    }

    @media (max-width: 767.98px) {
        .search-page {
            padding-top: 96px;
            padding-bottom: 52px;
        }

        .search-hero,
        .search-group,
        .search-empty {
            border-radius: 14px;
        }

        .search-hero,
        .search-group,
        .search-empty {
            padding: 14px;
        }

        .search-kicker {
            margin-bottom: 6px;
            font-size: 0.62rem;
        }

        .search-title {
            font-size: 1.35rem;
            line-height: 1.1;
            margin-bottom: 0.45rem;
        }

        .search-muted {
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .search-toolbar,
        .search-group__header {
            flex-direction: column;
            align-items: stretch;
        }

        .search-toolbar {
            gap: 10px;
            margin-top: 1rem;
        }

        .search-field {
            min-height: 48px;
            padding: 0 12px;
            border-radius: 12px;
        }

        .search-field i {
            font-size: 17px;
        }

        .search-input {
            font-size: 14px;
        }

        .search-btn {
            width: 100%;
            min-height: 48px;
            border-radius: 12px;
            font-size: 0.75rem;
        }

        .search-summary {
            gap: 8px;
            margin-top: 12px;
        }

        .search-count,
        .search-summary__query {
            min-height: 30px;
            padding: 6px 10px;
            font-size: 0.75rem;
        }

        .search-tabs {
            gap: 0.45rem;
            margin-bottom: 0.9rem;
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 2px;
            scrollbar-width: none;
        }

        .search-tabs::-webkit-scrollbar {
            display: none;
        }

        .search-tab {
            flex: 0 0 auto;
            padding: 0.55rem 0.8rem;
            font-size: 0.78rem;
        }

        .search-card {
            display: grid;
            grid-template-columns: 72px minmax(0, 1fr);
            align-items: center;
            gap: 10px;
            padding: 0.55rem;
            border-radius: 12px;
        }

        .search-card__media {
            aspect-ratio: 1 / 1;
            margin-bottom: 0;
            border-radius: 10px;
        }

        .search-card__type {
            margin-bottom: 0.35rem;
            padding: 0;
            background: transparent;
            color: #8fd7ff;
            font-size: 0.62rem;
            letter-spacing: 0.12em;
        }

        .search-card__title {
            font-size: 0.86rem;
            line-height: 1.3;
            margin-bottom: 0.2rem;
        }

        .search-card__description,
        .search-card__meta {
            font-size: 0.75rem;
            line-height: 1.35;
        }

        .search-card__description {
            display: none;
        }

        .search-card__meta {
            margin-top: 0.25rem;
        }

        .search-group__header {
            gap: 8px;
            margin-bottom: 0.9rem;
        }

        .search-group__title,
        .search-empty h2 {
            font-size: 1rem;
        }

        .search-empty p {
            font-size: 0.85rem;
        }

        .row.g-3.g-lg-4 {
            --bs-gutter-x: 0.75rem;
            --bs-gutter-y: 0.75rem;
        }

        .row.g-3.g-lg-4 > [class*="col-"] {
            width: 100%;
        }
    }
</style>

<section class="search-page">
    <div class="container search-shell">
        <div class="search-hero mb-4">
            <p class="search-kicker">Search</p>
            <h1 class="search-title">Find anything across Nowstream.</h1>
            <p class="search-muted mb-0">Events, live streams, videos, podcasts, radio, TV, merchandise and channels in one place.</p>

            <form action="{{ route('search') }}" method="GET" class="search-toolbar">
                <label class="search-field" for="search-query">
                    <i class="bx bx-search-alt-2"></i>
                    <input
                        id="search-query"
                        type="text"
                        name="query"
                        class="search-input"
                        value="{{ $query }}"
                        placeholder="Search events, videos, podcasts, merch..."
                    >
                </label>
                <button type="submit" class="search-btn">Search</button>
            </form>

            @if($query !== '')
                <div class="search-summary">
                    <span class="search-count">{{ number_format($totalResults) }} results</span>
                    <span class="search-summary__query">For "{{ $query }}"</span>
                </div>
            @endif
        </div>

        @if($query === '')
            <div class="search-empty">
                <h2>Start with a title, artist, event or keyword.</h2>
                <p class="search-muted mb-0">Results are grouped by content type so it is easier to scan what the app already has.</p>
            </div>
        @elseif($sections->isEmpty())
            <div class="search-empty">
                <h2>No results found.</h2>
                <p class="search-muted mb-0">Try a different title, a shorter phrase, or a broader keyword.</p>
            </div>
        @else
            <div class="search-tabs" role="tablist" aria-label="Search result types">
                @foreach($sections as $index => $section)
                    @php
                        $tabId = 'search-tab-' . \Illuminate\Support\Str::slug($section['title']);
                        $paneId = 'search-pane-' . \Illuminate\Support\Str::slug($section['title']);
                    @endphp
                    <button
                        type="button"
                        id="{{ $tabId }}"
                        class="search-tab {{ $index === 0 ? 'active' : '' }}"
                        data-search-tab-target="#{{ $paneId }}"
                        role="tab"
                        aria-controls="{{ $paneId }}"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                    >
                        {{ $section['title'] }} ({{ $section['count'] }})
                    </button>
                @endforeach
            </div>

            <div class="search-tab-content">
                @foreach($sections as $index => $section)
                    @php
                        $paneId = 'search-pane-' . \Illuminate\Support\Str::slug($section['title']);
                    @endphp
                    <div
                        id="{{ $paneId }}"
                        class="search-tab-pane {{ $index === 0 ? 'active' : '' }}"
                        role="tabpanel"
                        aria-labelledby="search-tab-{{ \Illuminate\Support\Str::slug($section['title']) }}"
                    >
                        <div class="search-group">
                            <div class="search-group__header">
                                <h2 class="search-group__title">{{ $section['title'] }}</h2>
                                <span class="search-count">{{ $section['count'] }} found</span>
                            </div>

                            <div class="row g-3 g-lg-4">
                                @foreach($section['items'] as $item)
                                    <div class="col-6 col-md-4 col-xl-3">
                                        @if(isset($item['product']) && $item['product'] instanceof \App\Models\Product)
                                            @include('Frontend.modules.shop.partials.product-card', ['product' => $item['product']])
                                        @else
                                            <a href="{{ $item['url'] }}" class="search-card">
                                                @if(!empty($item['image']))
                                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="search-card__media">
                                                @else
                                                    <div class="search-card__media"></div>
                                                @endif

                                                <span class="search-card__type">{{ $item['type'] }}</span>
                                                <h3 class="search-card__title">{{ $item['title'] }}</h3>

                                                @if(!empty($item['description']))
                                                    <p class="search-card__description">{{ \Illuminate\Support\Str::limit(strip_tags($item['description']), 90) }}</p>
                                                @endif

                                                @if(!empty($item['meta']))
                                                    <p class="search-card__meta">{{ $item['meta'] }}</p>
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if($sections->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = Array.from(document.querySelectorAll('[data-search-tab-target]'));
        const panes = Array.from(document.querySelectorAll('.search-tab-pane'));

        if (!tabs.length || !panes.length) {
            return;
        }

        const activateTab = (tab) => {
            const target = document.querySelector(tab.getAttribute('data-search-tab-target'));

            if (!target) {
                return;
            }

            tabs.forEach((item) => {
                item.classList.remove('active');
                item.setAttribute('aria-selected', 'false');
            });

            panes.forEach((pane) => pane.classList.remove('active'));

            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            target.classList.add('active');
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => activateTab(tab));
        });
    });
</script>
@endif
@endsection
