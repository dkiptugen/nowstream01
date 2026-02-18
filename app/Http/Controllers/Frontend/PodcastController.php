<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheHelper;

class PodcastController extends Controller
{
    use CacheHelper;

    protected $data = [];

    /**
     * Podcast listing page
     */
    public function index(Request $request)
{
    $perPage = 18;
    $page = (int) $request->get('page', 1);

    /**
     * Stable random seed (changes every 10 minutes)
     * Prevents reshuffling during scroll
     */
    $seed = now()->format('YmdHi'); // time-based seed

    $cacheKey = "podcasts_page_{$page}_seed_{$seed}";

    // Cached paginated podcasts
    $podcasts = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage) {

        return Content::query()
            ->where('content_group', 'podcast')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->inRandomOrder() // randomized but cached
            ->paginate($perPage);
    });

    /**
     * AJAX (Infinite Scroll)
     */
    if ($request->ajax()) {
        return response()->json([
            'html' => view(
                'Frontend.includes.components.partials.podcast-list',
                compact('podcasts')
            )->render(),
            'hasMore' => $podcasts->hasMorePages()
        ]);
    }

    /**
     * Normal Page Load (Cached global blocks)
     */
    $channels = Cache::remember('channels_global', 600, function () {
        return $this->get_channels();
    });

    $videos = Cache::remember('videos_global', 600, function () {
        return $this->get_videos(6);
    });

    $categories = Cache::remember('podcast_categories', 3600, function () {
        return Category::where('type', 'podcast')
            ->limit(6)
            ->get();
    });

    $topPodcasts = Cache::remember('top_podcasts_global', 600, function () {
        return Content::where('content_group', 'podcast')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderByDesc('views')
            ->limit(16)
            ->get();
    });

    return view('Frontend.modules.podcasts.index', compact(
        'podcasts',
        'channels',
        'videos',
        'categories',
        'topPodcasts'
    ));
}


   
    /**
     * Single podcast view
     */
   public function show($slug)
{
    try {
        /**
         * 1. Podcast (cache core model)
         */
        $podcast = Cache::remember(
            "podcast_detail_{$slug}",
            now()->addHours(12),
            function () use ($slug) {
                return Content::where('slug', $slug)
                    ->where('content_group', 'podcast')
                    ->first();
            }
        );

        if (!$podcast) {
            abort(404, 'Podcast not found');
        }

        $uuid = $podcast->uuid;

        /**
         * Increment views (not cached)
         */
        Content::where('uuid', $podcast->uuid)->increment('views');


        /**
         * 2. Episodes (cache)
         */
        $episodes = Cache::remember(
            "podcast_episodes_{$uuid}",
            now()->addMinutes(20),
            function () use ($uuid) {
                return Content::where('parent_id', $uuid)
                    ->where('content_group', 'podcast_episode')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );

        $podcast->episodes = $episodes;
        $podcast->episodes_count = $episodes->count();


        /**
         * 3. Comments (short cache – frequently changing)
         */
        $comments = Cache::remember(
            "podcast_comments_{$uuid}",
            now()->addMinutes(5),
            function () use ($podcast) {
                return $podcast->comments()
                    ->with('user')
                    ->latest()
                    ->get();
            }
        );


        /**
         * 4. Related Podcasts Pool (cache bigger set)
         *    Then RANDOMIZE per request
         */
        $relatedPool = Cache::remember(
            "podcast_related_pool",
            now()->addHours(6),
            function () use ($uuid) {
                return Content::where('content_group', 'podcast')
                    ->where('uuid', '!=', $uuid)
                    ->latest()
                    ->take(30)   // bigger pool
                    ->get();
            }
        );

        // Randomize on each request (fast, in-memory)
        $related = $relatedPool->shuffle()->take(6);


        /**
         * 5. Sidebar Videos (global cache)
         */
        $videos = Cache::remember(
            'videos_global_sidebar',
            now()->addMinutes(10),
            function () {
                return $this->get_videos(6);
            }
        );


        return view('Frontend.modules.podcasts.show', compact(
            'podcast',
            'related',
            'videos',
            'episodes',
            'comments'
        ));

    } catch (\Exception $e) {
        abort(404, 'Podcast not found');
    }
}

}
