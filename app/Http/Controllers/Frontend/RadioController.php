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
    $country = $request->get('country'); // optional filter

    /**
     * Build dynamic cache key
     */
    $cacheKey = "radios_page_{$page}_" . ($country ?? 'all');

    /**
     * Paginated Radios
     */
    $radios = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($perPage, $country) {

        $query = Content::where('content_group', 'radio')
            ->whereNotNull('stream_url')
            ->where('status', 1);

        if ($country) {
            $query->where('country', $country);
        }

        return $query->latest()->paginate($perPage);
    });


    /**
     * Categories (rarely change)
     */
    $categories = Cache::remember('radio_categories', now()->addHours(12), function () {
        return Category::where('type', 'radio')
            ->limit(6)
            ->get();
    });


    /**
     * Top Radios (randomized inside cache, not per request)
     */
    $topradios = Cache::remember('top_radios_home', now()->addMinutes(15), function () {
        return Content::where('content_group', 'radio')
            ->whereNotNull('stream_url')
            ->where('status', 1)
            ->orderByDesc('views')
            ->limit(100)
            ->get()
            ->shuffle()
            ->take(16)
            ->values();
    });


    /**
     * AJAX (infinite scroll)
     */
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
        'topradios'
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
