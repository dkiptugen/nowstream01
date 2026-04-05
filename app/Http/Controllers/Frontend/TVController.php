<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

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
                'hasMore' => $tvs->hasMorePages(),
                'nextPageUrl' => $tvs->nextPageUrl(),
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

            $genreViews = [];

            Content::where('content_group', 'tv')
                ->whereNotNull('genre')
                ->select('genre', 'views')
                ->chunk(500, function ($contents) use (&$genreViews) {

                    foreach ($contents as $content) {

                        $genres = $content->genre;

                        // Normalize genre formats
                        if (is_array($genres)) {
                            $list = $genres;
                        } else {
                            $genres = trim($genres, '"');

                            if (str_starts_with($genres, '[')) {
                                $decoded = json_decode($genres, true);
                                $list = is_array($decoded) ? $decoded : [];
                            } elseif (str_contains($genres, ',')) {
                                $list = array_map('trim', explode(',', $genres));
                            } else {
                                $list = [$genres];
                            }
                        }

                        foreach ($list as $g) {
                            $g = trim($g);
                            if (!$g) continue;

                            $genreViews[$g] = ($genreViews[$g] ?? 0) + (int) $content->views;
                        }
                    }
                });

            // Sort by total views (desc)
            arsort($genreViews);

            // Return only genre names
            return collect($genreViews)->keys()->take(12)->values();
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

            $tv->increment('views'); 
            $this->recordWatchHistory($tv);
            $uuid = $tv->uuid;
            $genres = $tv->genre ?? [];

            $comments = $tv->comments()->with('user')->orderBy('created_at', 'asc')->get();
            $streamProxyUrl = URL::temporarySignedRoute('stream.view', now()->addMinutes(30), [
                'streamId' => $tv->id,
            ]);

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

            return view('Frontend.modules.tvs.show', compact('tv', 'related', 'comments', 'streamProxyUrl'));
        } catch (\Exception $e) {
            abort(404, 'TV not found');
        }
    }
    protected function recordWatchHistory($tv)
    {
        $user = Auth::user();
        if ($user && $tv) {
            WatchHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'content_id' => $tv->uuid,
                ],
                [
                    'watched_at' => now(),
                ]
            );
        }
    }
    public function recordWatchHistoryAjax(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $tv = Content::where('content_group', 'tv')->findOrFail($request->input('tv_id'));

            WatchHistory::updateOrCreate(
                [
                    'user_id'  => $user->id,
                    'content_id' => $tv->uuid,
                ],
                [
                    'watched_at'     => now(),
                    'watch_duration' => $request->input('watch_duration', 0),
                ]
            );

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }
}
