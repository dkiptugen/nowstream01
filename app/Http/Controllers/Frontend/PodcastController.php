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
    $perPage = 6;

    $podcasts = Content::where('type', 'podcast')
        ->whereNull('parent_id')
        ->paginate($perPage);

    // Handle AJAX request (infinite scroll)
    if ($request->ajax()) {
        return view('Frontend.includes.podcast-list', compact('podcasts'))->render();
    }

    // Normal page load
    $channels = $this->get_channels();
    $videos = $this->get_videos(6);
    $categories = Category::where('type', 'podcast')->limit(6)->get();
    $topPodcasts = Content::where('type', 'podcast')
    ->whereNull('parent_id')
    ->orderBy('views', 'desc')
    ->limit(6)
    ->get();

    return view('Frontend.modules.podcasts.index', compact('podcasts', 'channels', 'videos', 'categories','topPodcasts'));
}
 
public function loadMore(Request $request)
{
    $perPage = 6;
    
    $podcasts = Content::where('type', 'podcast')
            ->whereNull('parent_id') 
            ->paginate($perPage);

        // If AJAX request, return partial only
        if ($request->ajax()) {
            return view('Frontend.includes.podcast-list', compact('podcasts'))->render();
        }

        // Normal page load
        $channels = []; // optional
        $videos = [];   // optional
        $categories = Category::where('type', 'podcast')->limit(6)->get();

    return view('Frontend.modules.podcasts.index', compact('podcasts', 'channels', 'videos', 'categories'));
}
    /**
     * Single podcast view
     */
    public function show($uuid, $slug)
    {
        try {
            $podcast = Cache::remember("podcast_{$uuid}_{$slug}", now()->addDay(), function () use ($uuid, $slug) {
                return Content::where('uuid', $uuid)
                    ->where('slug', $slug)
                    ->where('content_group', 'podcast')
                    ->first();
            });
            $podcast->increment('views'); // Increment view count
            $episodes = Content::where('parent_id', $podcast->uuid)->where('content_group', 'podcast_episode')->get();
            $podcast->episodes_count = $episodes->count(); 
            $podcast->episodes = $episodes; 
            if (!$podcast) {
                abort(404, 'Podcast not found');
            }

            // Related podcasts (exclude current)
            $related = Cache::remember("podcast_related_{$uuid}", now()->addDay(), function () use ($uuid) {
                return Content::where('content_group', 'podcast')
                    ->where('uuid', '!=', $uuid)
                    ->latest()
                    ->take(6)
                    ->get();
            });

            // Sidebar videos
            $videos = $this->get_videos(6);

            return view('Frontend.modules.podcasts.show', [
                'podcast'  => $podcast,
                'related'  => $related,
                'videos'   => $videos,
                'episodes' => $episodes,
            ]);
        } catch (\Exception $e) {
            abort(404, 'Podcast not found');
        }
    }
}
