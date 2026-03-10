<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class PodcastApiController extends Controller
{

    /**
     * List Podcasts
     */
  public function index(Request $request)
{
    $perPage = $request->get('per_page', 20);

    // Get only parent podcasts (parent_id = null)
    $podcasts = Content::where('content_group', 'podcast')
        ->whereNull('parent_id')
        ->where('status', 1)
        ->latest()
        ->paginate($perPage);

    // Optionally, you can load episodes for each podcast
    $podcasts->getCollection()->transform(function ($podcast) {
        $podcast->episodes = Content::where('parent_id', $podcast->uuid)
            ->where('content_group', 'podcast_episode')
            ->orderByDesc('created_at')
            ->get();
        return $podcast;
    });

    return response()->json([
        'success' => true,
        'data' => $podcasts->items(),
        'pagination' => [
            'current_page' => $podcasts->currentPage(),
            'last_page' => $podcasts->lastPage(),
            'per_page' => $podcasts->perPage(),
            'total' => $podcasts->total(),
        ]
    ]);
}

    /**
     * Single Podcast
     */
  public function show($slug)
{
    // Fetch the parent podcast
    $podcast = Content::where('slug', $slug)
        ->where('content_group', 'podcast')
        ->firstOrFail();

    // Increment views
    Content::where('uuid', $podcast->uuid)->increment('views');

    // Fetch episodes for this podcast (parent_id = podcast uuid)
    $episodes = Content::where('parent_id', $podcast->uuid)
        ->where('content_group', 'podcast_episode')
        ->orderByDesc('created_at')
        ->get();

    // Related podcasts: top-level podcasts excluding current
    $related = Content::where('content_group', 'podcast')
        ->whereNull('parent_id')
        ->where('uuid', '!=', $podcast->uuid)
        ->inRandomOrder()
        ->limit(6)
        ->get();

    // Return combined response
    return response()->json([
        'success' => true,
        'podcast' => $podcast,
        'episodes' => $episodes,
        'related' => $related,
    ]);
}


    /**
     * Podcast Episodes
     */
    public function episodes($slug)
    {
        $podcast = Content::where('slug', $slug)
            ->where('content_group', 'podcast_episode')
            ->firstOrFail();

        $episodes = Content::where('parent_id', $podcast->uuid)
            ->where('content_group', 'podcast_episode')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $episodes
        ]);
    }


    /**
     * Record Watch History
     */
    public function recordWatchHistory(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $podcast = Content::findOrFail($request->podcast_id);

        WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $podcast->uuid,
            ],
            [
                'watched_at' => now(),
                'watch_duration' => $request->watch_duration ?? 0
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }
}