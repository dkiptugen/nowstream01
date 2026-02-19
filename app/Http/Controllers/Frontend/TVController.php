<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TVController extends Controller
{
    protected $data = [];

    /**
     * TV listing page with filters, caching, and infinite scroll
     */
public function index(Request $request)
{
    $perPage = 30;
    $page = $request->get('page', 1);
    $country  = $request->get('country', 'Kenya');
    $language = $request->get('language');

    // Normalize null language for cache key
    $langKey = $language ?? 'all';

    // Cache key depends on page and filters
    $cacheKey = "tvs_{$country}_{$langKey}_page_{$page}";

    // Paginated TVs
    $tvs = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage, $country, $language, $page) {
        $query = Content::query()
            ->where('content_group', 'tv')
            ->whereNotNull('stream_url');

        if ($country) {
            $query->where('country', $country);
        }

        if ($language) {
            $query->where('language', $language);
        }

        // Page explicitly passed (same pattern as radios)
        return $query->orderByDesc('views')
            ->paginate($perPage, ['*'], 'page', $page);
    });

    // IMPORTANT: keep filters during pagination
    $tvs->appends($request->all());


    // AJAX request
    if ($request->ajax()) {
        return response()->json([
            'html' => view(
                'Frontend.includes.components.partials.tvs-list',
                compact('tvs')
            )->render(),
            'hasMore' => $tvs->hasMorePages()
        ]);
    }


    // Static / global data
    $tv_countries = Cache::remember('tv_countries', 3600, fn() =>
        Content::where('content_group', 'tv')
            ->whereNotNull('country')
            ->distinct()
            ->pluck('country')
    );

    $categories = Cache::remember('tv_categories', 3600, fn() =>
        Category::where('type', 'like', '%tv%')->get()
    );

    $genres = Cache::remember('tv_genres', now()->addHours(6), function () {
        return Content::where('content_group', 'tv')
            ->whereNotNull('genre')
            ->pluck('genre')
            ->flatMap(fn($genre) => is_array($genre)
                ? $genre
                : (str_starts_with($genre, '[')
                    ? json_decode($genre, true)
                    : (str_contains($genre, ',')
                        ? array_map('trim', explode(',', $genre))
                        : [$genre]
                    )
                )
            )
            ->filter()
            ->map(fn($g) => trim($g))
            ->unique()
            ->sort()
            ->values();
    });

    $toptvs = Cache::remember('top_tvs', 600, fn() =>
        Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->where('status', 1)
            ->with('categories')
            ->orderByDesc('views')
            ->limit(40)
            ->get()
    );

    $english_tvs = Cache::remember('english_tvs', 600, fn() =>
        Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->where('status', 1)
            ->where('language', 'en')
            ->orderByDesc('views')
            ->limit(6)
            ->get()
    );

    return view('Frontend.modules.tvs.index', compact(
        'tvs',
        'tv_countries',
        'categories',
        'toptvs',
        'english_tvs',
        'genres',
        'country',
        'language'
    ));
}

    /**
     * Single TV page with randomized related TVs
     */
    public function show($slug)
    {
        try {
            $tv = Cache::remember("tv_{$slug}", now()->addDay(), fn() =>
                Content::where('slug', $slug)->where('content_group', 'tv')->first()
            );

            if (!$tv) abort(404, 'TV not found');

            $tv->increment('views'); // not cached
            $uuid = $tv->uuid;
            $genres = $tv->genre ?? [];

            $comments = $tv->comments()->with('user')->orderBy('created_at', 'asc')->get();

            // Randomized related TVs
            $related = Cache::remember("tv_related_{$uuid}", now()->addDay(), function () use ($uuid, $genres) {
                $query = Content::where('content_group', 'tv')
                    ->where('uuid', '!=', $uuid)
                    ->whereNotNull('stream_url');

                if (!empty($genres)) {
                    $query->where(function ($q) use ($genres) {
                        foreach ($genres as $genre) {
                            $q->orWhereJsonContains('genre', $genre);
                        }
                    });
                }

                return $query->inRandomOrder()->limit(16)->get(); // randomized
            });

            return view('Frontend.modules.tvs.show', compact('tv', 'related', 'comments'));
        } catch (\Exception $e) {
            abort(404, 'TV not found');
        }
    }
}
