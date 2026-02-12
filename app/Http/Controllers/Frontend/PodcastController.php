<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheHelper;

class PodcastController extends Controller
{
    use CacheHelper;

    protected $data = [];

    /**
     * Podcast listing page
     */
    public function index()
    {
        // Latest podcasts (paginated style alternative)
        $this->data['podcasts'] = $this->get_podcasts(12);

        // Featured / recent videos for sidebar
        $this->data['videos'] = $this->get_videos(6);

        // Channels (optional if used on page)
        $this->data['channels'] = $this->get_channels();

        return view('Frontend.modules.podcasts.index', $this->data);
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
