<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class RadioController extends Controller
{
    protected $data = [];

    /**
     * Radio listing page
     */

    public function index(Request $request)
    {
        $perPage = 30;
        $page = $request->get('page', 1);

        /**
         * Radios pagination (page cache)
         */
        $radios = Cache::remember(
            "radios_page_{$page}",
            now()->addMinutes(10),
            function () use ($perPage, $page) {
                return Content::where('content_group', 'radio')
                    ->whereNotNull('stream_url')
                    ->where('status', 1)
                    ->latest()
                    ->paginate($perPage, ['*'], 'page', $page); // FIX
            }
        );

        $radios->appends($request->all());


        /**
         * Categories (long cache)
         */
        $categories = Cache::remember(
            'radio_categories',
            now()->addHours(6),
            function () {
                return Category::where('type', 'radio')
                    ->limit(6)
                    ->get();
            }
        ); 

$genres = Cache::remember('radio_genres', now()->addHours(6), function () {

    $genreViews = [];

    Content::where('content_group', 'radio')
        ->whereNotNull('genre')
        ->select('genre', 'views')
        ->chunk(500, function ($contents) use (&$genreViews) {

            foreach ($contents as $content) {
                $genres = $content->genre;

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

    // Sort by total views descending
    arsort($genreViews);

    // Return only the top 20 genres
    return collect($genreViews)->keys()->take(20)->values();
});
        /**
         * Top Radios Pool
         */
        $topRadiosPool = Cache::remember(
            'top_radios_pool',
            now()->addMinutes(10),
            function () {
                return Content::where('content_group', 'radio')
                    ->whereNotNull('stream_url')
                    ->where('status', 1)
                    ->orderByDesc('views')
                    ->limit(50)
                    ->get();
            }
        );

        $topradios = $topRadiosPool->shuffle()->take(16);

        // AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'Frontend.includes.components.partials.radio-items',
                    compact('radios')
                )->render(),
                'hasMore' => $radios->hasMorePages()
            ]);
        }

        return view('Frontend.modules.radios.index', compact(
            'radios',
            'categories',
            'topradios',
            'genres'
        ));
    }

    public function show($slug)
    {
        try {

            /**
             * Radio detail (cache)
             */
            $radio = Cache::remember(
                "radio_detail_{$slug}",
                now()->addHours(12),
                function () use ($slug) {
                    return Content::where('slug', $slug)
                        ->where('content_group', 'radio')
                        ->where('status', 1)
                        ->first();
                }
            );

            if (!$radio) {
                abort(404, 'Radio not found');
            }

            $uuid = $radio->uuid;


            /**
             * Increment views (avoid cached model mutation)
             */
            Content::where('id', $radio->id)->increment('views');


            /**
             * Comments (short cache)
             */
            $comments = Cache::remember(
                "radio_comments_{$uuid}",
                now()->addMinutes(5),
                function () use ($radio) {
                    return $radio->comments()
                        ->with('user')
                        ->oldest()
                        ->get();
                }
            );


            /**
             * Genres (cache)
             */
            $genres = Cache::remember(
                "radio_genres_{$uuid}",
                now()->addHours(6),
                function () use ($radio) {
                    return collect($radio->genre ?? [])
                        ->filter()
                        ->unique()
                        ->values();
                }
            );


            /**
             * Related Radios Pool (global cache)
             * Then randomize per request
             */
            $relatedPool = Cache::remember(
                'radio_related_pool',
                now()->addHours(6),
                function () {
                    return Content::where('content_group', 'radio')
                        ->whereNotNull('stream_url')
                        ->where('status', 1)
                        ->latest()
                        ->limit(60)   // large pool
                        ->get();
                }
            );

            $related = $relatedPool
                ->where('uuid', '!=', $uuid)
                ->shuffle()
                ->take(16)
                ->values();


            return view('Frontend.modules.radios.show', compact(
                'radio',
                'related',
                'comments',
                'genres'
            ));
        } catch (\Exception $e) {
            abort(404, 'Radio not found');
        }
    }
}
