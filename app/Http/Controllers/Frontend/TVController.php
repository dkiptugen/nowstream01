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


        // Cache key depends on page and filters
        $cacheKey = "tvs_page_{$page}";

        // Paginated TVs
        $tvs = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage, $page) {
            $query = Content::query()
                ->where('content_group', 'tv')
                ->whereNotNull('stream_url');


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
                    'Frontend.includes.components.partials.tv-items',
                    compact('tvs')
                )->render(),
                'hasMore' => $tvs->hasMorePages()
            ]);
        }


        // Static / global data
        $tv_countries = Cache::remember(
            'tv_countries',
            3600,
            fn() =>
            Content::where('content_group', 'tv')
                ->whereNotNull('country')
                ->distinct()
                ->pluck('country')
        );

        $categories = Cache::remember(
            'tv_categories',
            3600,
            fn() =>
            Category::where('type', 'like', '%tv%')->get()
        );

       $genres = Cache::remember('tv_genres', now()->addHours(6), function () {

    $allGenres = Content::where('content_group', 'tv')
        ->whereNotNull('genre')
        ->pluck('genre')
        ->flatMap(function ($genre) {

            if (is_array($genre)) {
                return $genre;
            }

            $genre = trim($genre, '"');

            if (str_starts_with($genre, '[')) {
                $decoded = json_decode($genre, true);
                return is_array($decoded) ? $decoded : [];
            }

            if (str_contains($genre, ',')) {
                return array_map('trim', explode(',', $genre));
            }

            return [$genre];
        })
        ->filter()
        ->map(fn ($g) => trim($g));

    // Count occurrences
    return $allGenres
        ->countBy()            // <-- key part
        ->sortDesc()           // highest count first
        ->keys()               // return only genre names
        ->values();
});

        $toptvs = Cache::remember(
            'top_tvs',
            600,
            fn() =>
            Content::where('content_group', 'tv')
                ->whereNotNull('stream_url')
                ->where('status', 1)
                ->with('categories')
                ->orderByDesc('views')
                ->limit(40)
                ->get()
        );

        $english_tvs = Cache::remember(
            'english_tvs',
            600,
            fn() =>
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
        ));
    }

    /**
     * Single TV page with randomized related TVs
     */
    public function show($slug)
    {
        try {
            $tv = Cache::remember(
                "tv_{$slug}",
                now()->addDay(),
                fn() =>
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
