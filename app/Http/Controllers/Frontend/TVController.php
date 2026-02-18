<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheHelper;

class TVController extends Controller
{
    protected $data = [];

    /**
     * TV listing page
     */
   public function index(Request $request)
{
    $perPage = 30;
    $page    = $request->get('page', 1);

    // Optional filters
    $country  = $request->get('country', 'Kenya');
    $language = $request->get('language');

    /*
    |--------------------------------------------------------------------------
    | Paginated TVs (Cache per page + filter)
    |--------------------------------------------------------------------------
    */
    $cacheKey = "tvs_{$country}_{$language}_page_{$page}";

    $tvs = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage, $country, $language) {

        $query = Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->where('status', 1)
            ->with('categories');

        if ($country) {
            $query->where('country', $country);
        }

        if ($language) {
            $query->where('language', $language);
        }

        return $query
            ->orderByDesc('views')
            ->paginate($perPage);
    });

    /*
    |--------------------------------------------------------------------------
    | AJAX (Infinite Scroll)
    |--------------------------------------------------------------------------
    */
    if ($request->ajax()) {
        return response()->json([
            'html' => view(
                'Frontend.includes.components.partials.tv-list',
                ['tvs' => $tvs]
            )->render(),
            'hasMore' => $tvs->hasMorePages()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Data (Longer Cache)
    |--------------------------------------------------------------------------
    */

    // Countries
    $tv_countries = Cache::remember('tv_countries', 3600, function () {
        return Content::where('content_group', 'tv')
            ->whereNotNull('country')
            ->distinct()
            ->pluck('country');
    });

    // Categories
    $categories = Cache::remember('tv_categories', 3600, function () {
        return Category::where('type', 'like', '%tv%')->get();
    });

    // Genres
    $genres = Cache::remember('tv_genres', now()->addHours(6), function () {

    return Content::where('content_group', 'tv')
        ->whereNotNull('genre')
        ->pluck('genre')
        ->flatMap(function ($genre) {

            // Handle different storage formats
            if (is_array($genre)) {
                return $genre;
            }

            // JSON stored as string
            if (is_string($genre) && str_starts_with($genre, '[')) {
                return json_decode($genre, true) ?? [];
            }

            // Comma-separated string
            if (is_string($genre) && str_contains($genre, ',')) {
                return array_map('trim', explode(',', $genre));
            }

            // Single value
            return [$genre];
        })
        ->filter()                 // remove null / empty
        ->map(fn ($g) => trim($g))
        ->unique()
        ->sort()
        ->values();
});


    // Top TVs (global)
    $toptvs = Cache::remember('top_tvs', 600, function () {
        return Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->where('status', 1)
            ->orderByDesc('views')
            ->with('categories')
            ->limit(40)
            ->get();
    });

    // English channels
    $english_tvs = Cache::remember('english_tvs', 600, function () {
        return Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->where('status', 1)
            ->where('language', 'en')
            ->orderByDesc('views')
            ->limit(6)
            ->get();
    });

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
     * Single TV page
     */
    public function show($slug)
    {
        try {
            // Cache TV detail
            $tv = Cache::remember("tv_{$slug}", now()->addDay(), function () use ($slug) {
                return Content::where('slug', $slug)
                    ->where('content_group', 'tv')
                    ->first();
            });

            if (!$tv)
                abort(404, 'TV not found');

            // Increment views (not cached)
            $tv->increment('views');
            $uuid = $tv->uuid ?? null;
            $comments = $tv->comments()
                ->with('user')
                ->orderBy('created_at', 'asc') // oldest first
                ->get();
            $genres = $tv->genre ?? [];
            // Related TVs (cache)
            $related = Cache::remember("tv_related_{$uuid}", now()->addDay(), function () use ($uuid, $genres) {
                return Content::where('content_group', 'tv')
                    ->where('uuid', '!=', $uuid)
                    ->whereNotNull('stream_url')
                    ->where(function ($query) use ($genres) {
                        foreach ($genres as $genre) {
                            $query->orWhereJsonContains('genre', $genre);
                        }
                    })
                    ->latest()
                    ->take(16)
                    ->get();
            });

            return view('Frontend.modules.tvs.show', compact('tv', 'related', 'comments'));
        } catch (\Exception $e) {
            abort(404, 'TV not found');
        }
    }
}
