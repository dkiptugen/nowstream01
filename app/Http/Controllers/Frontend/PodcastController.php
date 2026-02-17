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
        $page = $request->get('page', 1); // for caching pagination

        // Cache podcasts pagination per page
        $podcasts = Cache::remember("podcasts_page_{$page}", now()->addMinutes(10), function () use ($perPage) {
            return Content::where('content_group', 'podcast')
                ->whereNull('parent_id')
                ->paginate($perPage);
        });

        // Handle AJAX request (infinite scroll)
        if ($request->ajax()) {
            return view('Frontend.includes.podcast-list', compact('podcasts'))->render();
        }

        // Normal page load — cache other data
        $channels = Cache::remember('channels_global', 600, function () {
            return $this->get_channels();
        });

        $videos = Cache::remember('videos_global', 600, function () {
            return $this->get_videos(6);
        });

        $categories = Cache::remember('podcast_categories', 3600, function () {
            return Category::where('type', 'podcast')->limit(6)->get();
        });

        $topPodcasts = Cache::remember('top_podcasts_global', 600, function () {
            return Content::where('content_group', 'podcast')
                ->whereNull('parent_id')
                ->orderByDesc('views')
                ->limit(16)
                ->get();
        });

        return view('Frontend.modules.podcasts.index', compact(
            'podcasts', 'channels', 'videos', 'categories', 'topPodcasts'
        ));
    }

    /**
     * Load more podcasts (AJAX / infinite scroll)
     */
    public function loadMore(Request $request)
    {
        $perPage = 6;
        $page = $request->get('page', 1);

        $podcasts = Cache::remember("podcasts_page_{$page}", now()->addMinutes(10), function () use ($perPage) {
            return Content::where('content_group', 'podcast')
                ->whereNull('parent_id')
                ->paginate($perPage);
        });

        // Return partial view for AJAX
        if ($request->ajax()) {
            return view('Frontend.includes.podcast-list', compact('podcasts'))->render();
        }

        // Normal page load
        $categories = Cache::remember('podcast_categories', 3600, function () {
            return Category::where('type', 'podcast')->limit(6)->get();
        });

        return view('Frontend.modules.podcasts.index', compact(
            'podcasts', 'categories'
        ));
    }

    /**
     * Single podcast view
     */
    public function show($slug)
    {
        try {
            // Cache podcast detail
            $podcast = Cache::remember("podcast_{$slug}", now()->addDay(), function () use ( $slug) {
                return Content::where('slug', $slug)
                    ->where('content_group', 'podcast')
                    ->first();
            });
            $uuid = $podcast->uuid ?? null;

            if (!$podcast) abort(404, 'Podcast not found');

            // Increment view count (do not cache increment)
            $podcast->increment('views');

            $comments = $podcast->comments()
                ->with('user')
                ->orderBy('created_at', 'asc') // oldest first
                ->get();

            // Episodes (cache per podcast)
            $episodes = Cache::remember("podcast_episodes_{$uuid}", now()->addMinutes(30), function () use ($podcast) {
                return Content::where('parent_id', $podcast->uuid)
                    ->where('content_group', 'podcast_episode')
                    ->get();
            });

            $podcast->episodes_count = $episodes->count();
            $podcast->episodes = $episodes;

            // Related podcasts (cache)
            $related = Cache::remember("podcast_related_{$uuid}", now()->addDay(), function () use ($uuid) {
                return Content::where('content_group', 'podcast')
                    ->where('uuid', '!=', $uuid)
                    ->latest()
                    ->take(6)
                    ->get();
            });

            // Sidebar videos
            $videos = Cache::remember('videos_global', 600, function () {
                return $this->get_videos(6);
            });

            return view('Frontend.modules.podcasts.show', compact(
                'podcast', 'related', 'videos', 'episodes', 'comments'
            ));

        } catch (\Exception $e) {
            abort(404, 'Podcast not found');
        }
    }
}
