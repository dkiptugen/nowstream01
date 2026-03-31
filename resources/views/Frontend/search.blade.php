@extends('Frontend.includes.layout')

@section('content')
<style>
    .search-page {
        padding: 7rem 0 4rem;
        min-height: 70vh;
    }

    .search-shell {
        max-width: 1180px;
    }

    .search-hero,
    .search-group,
    .search-empty {
        background: rgba(15, 23, 42, 0.9);
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 24px;
        box-shadow: 0 24px 70px rgba(2, 6, 23, 0.35);
    }

    .search-input {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 16px;
        padding: 1rem 1.1rem;
        background: rgba(15, 23, 42, 0.8);
        color: #fff;
    }

    .search-btn {
        border: 0;
        border-radius: 16px;
        padding: 1rem 1.4rem;
        background: #ef4444;
        color: #fff;
        font-weight: 700;
    }

    .search-kicker {
        color: #f87171;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-size: 0.78rem;
        margin-bottom: 0.75rem;
    }

    .search-title {
        color: #fff;
        font-size: clamp(2rem, 4vw, 3.2rem);
        margin-bottom: 0.75rem;
    }

    .search-muted {
        color: #cbd5e1;
    }

    .search-count {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        background: rgba(248, 113, 113, 0.12);
        color: #fecaca;
        font-size: 0.92rem;
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
        padding: 1rem;
        border-radius: 18px;
        background: rgba(30, 41, 59, 0.58);
        border: 1px solid rgba(148, 163, 184, 0.12);
        color: inherit;
        text-decoration: none;
        transition: transform 0.18s ease, border-color 0.18s ease;
    }

    .search-card:hover {
        transform: translateY(-3px);
        border-color: rgba(248, 113, 113, 0.42);
        text-decoration: none;
    }

    .search-card__media {
        width: 100%;
        aspect-ratio: 16 / 10;
        border-radius: 14px;
        object-fit: cover;
        background: linear-gradient(135deg, #1e293b, #334155);
        margin-bottom: 0.9rem;
    }

    .search-card__type {
        display: inline-block;
        margin-bottom: 0.65rem;
        padding: 0.32rem 0.65rem;
        border-radius: 999px;
        background: rgba(239, 68, 68, 0.12);
        color: #fca5a5;
        font-size: 0.76rem;
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

    .search-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .search-tab {
        border: 1px solid rgba(148, 163, 184, 0.14);
        border-radius: 999px;
        padding: 0.7rem 1rem;
        background: rgba(15, 23, 42, 0.72);
        color: #cbd5e1;
        font-weight: 600;
    }

    .search-tab.active,
    .search-tab:hover,
    .search-tab:focus {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
    }

    .search-tab-pane {
        display: none;
    }

    .search-tab-pane.active {
        display: block;
    }

    @media (max-width: 767.98px) {
        .search-page {
            padding-top: 6rem;
        }

        .search-group__header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<section class="search-page">
    <div class="container search-shell">
        <div class="search-hero p-4 p-lg-5 mb-4">
            <p class="search-kicker">Search</p>
            <h1 class="search-title">Find anything across Nowstream.</h1>
            <p class="search-muted mb-4">Events, live streams, videos, podcasts, radio, TV, merchandise and channels in one place.</p>

            <form action="{{ route('search') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-9">
                    <label for="search-query" class="search-muted d-block mb-2">Search term</label>
                    <input
                        id="search-query"
                        type="text"
                        name="query"
                        class="search-input"
                        value="{{ $query }}"
                        placeholder="Search events, videos, podcasts, merch..."
                    >
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="search-btn w-100">Search</button>
                </div>
            </form>

            @if($query !== '')
                <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
                    <span class="search-count">{{ number_format($totalResults) }} results</span>
                    <span class="search-muted">Results for "{{ $query }}"</span>
                </div>
            @endif
        </div>

        @if($query === '')
            <div class="search-empty p-4 p-lg-5">
                <h2 class="text-white h4 mb-2">Start with a title, artist, event or keyword.</h2>
                <p class="search-muted mb-0">The search page groups results by content type so it is easier to scan what the app already has.</p>
            </div>
        @elseif($sections->isEmpty())
            <div class="search-empty p-4 p-lg-5">
                <h2 class="text-white h4 mb-2">No results found.</h2>
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
                        <div class="search-group p-4">
                        <div class="search-group__header">
                            <h2 class="search-group__title">{{ $section['title'] }}</h2>
                            <span class="search-count">{{ $section['count'] }} found</span>
                        </div>

                        <div class="row g-4">
                            @foreach($section['items'] as $item)
                                <div class="col-12 col-md-6 col-xl-3">
                                    <a href="{{ $item['url'] }}" class="search-card">
                                        @if(!empty($item['image']))
                                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="search-card__media">
                                        @else
                                            <div class="search-card__media"></div>
                                        @endif

                                        <span class="search-card__type">{{ $item['type'] }}</span>
                                        <h3 class="search-card__title">{{ $item['title'] }}</h3>

                                        @if(!empty($item['description']))
                                            <p class="search-card__description">{{ \Illuminate\Support\Str::limit(strip_tags($item['description']), 110) }}</p>
                                        @endif

                                        @if(!empty($item['meta']))
                                            <p class="search-card__meta">{{ $item['meta'] }}</p>
                                        @endif
                                    </a>
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
