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
    public function index()
    {
        // TV countries (distinct)
        $tv_countries = Cache::remember('tv_countries', 3600, function () {
            return Content::where('content_group', 'tv')
                ->pluck('country')
                ->unique();
        });

        // TVs for Kenya (or default country)
        $tvs = Cache::remember('tvs_kenya', 600, function () {
            return Content::where('content_group', 'tv')
                ->whereNotNull('stream_url')
                ->where('country', 'Kenya')
                ->with('categories')
                ->take(30)
                ->get();
        });
        // for categories of tv
 

        // Categories where type "type" => "["podcast"]"
        // Categories where type contains "tv"
       $categories = Cache::remember('tv_categories', 3600, function () {
    return Category::where('type', 'like', '%tv%')->get();
});
// genres
$genres = Cache::remember('tv_genres', 3600, function () {
    return Content::where('content_group', 'tv')
        ->whereNotNull('genre')
        ->pluck('genre')
        ->flatten()
        ->unique();
});

        // Top TVs
        $toptvs = Cache::remember('top_tvs', 600, function () {
            return Content::where('content_group', 'tv')
                ->whereNotNull('stream_url')
                ->orderByDesc('views')
                ->with('categories')
                ->limit(39)
                ->get();
        });

        // English channels
        $english_tvs = Cache::remember('english_tvs', 600, function () {
            return Content::where('content_group', 'tv')
                ->whereNotNull('stream_url')
                ->where('language', 'en')
                ->orderByDesc('views')
                ->limit(6)
                ->get();
        });

        $this->data = compact('tv_countries', 'tvs', 'categories', 'toptvs', 'english_tvs', 'genres');

        return view('Frontend.modules.tvs.index', $this->data);
    }

    /**
     * Single TV page
     */
    public function show($uuid, $slug)
    {
        try {
            // Cache TV detail
            $tv = Cache::remember("tv_{$uuid}_{$slug}", now()->addDay(), function () use ($uuid, $slug) {
                return Content::where('uuid', $uuid)
                    ->where('slug', $slug)
                    ->where('content_group', 'tv')
                    ->first();
            });

            if (!$tv)
                abort(404, 'TV not found');

            // Increment views (not cached)
            $tv->increment('views');

            $comments = $tv->comments()
                ->with('user')
                ->orderBy('created_at', 'asc') // oldest first
                ->get();
            // Related TVs (cache)
            $related = Cache::remember("tv_related_{$uuid}", now()->addDay(), function () use ($uuid) {
                return Content::where('content_group', 'tv')
                    ->where('uuid', '!=', $uuid)
                    ->whereNotNull('stream_url')
                    ->latest()
                    ->take(6)
                    ->get();
            });

            return view('Frontend.modules.tvs.show', compact('tv', 'related', 'comments'));
        } catch (\Exception $e) {
            abort(404, 'TV not found');
        }
    }
}
