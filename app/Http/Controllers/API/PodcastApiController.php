<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class PodcastApiController extends Controller
{
    private const JSON_FLAGS = JSON_INVALID_UTF8_SUBSTITUTE;

    /**
     * List Podcasts
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $podcasts = Content::where('content_group', 'podcast')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->latest()
            ->paginate($perPage);

        return response()->json(
            [
                'success' => true,
                'data' => $podcasts
            ],
            200,
            [],
            self::JSON_FLAGS
        );
    }


    /**
     * Single Podcast
     */
    public function show($slug)
    {
        $podcast = Content::where('slug', $slug)
            ->where('content_group', 'podcast')
            ->firstOrFail();

        // Increment views
        Content::where('uuid', $podcast->uuid)->increment('views');

        $episodes = Content::where('parent_id', $podcast->uuid)
            ->where('content_group', 'podcast_episode')
            ->orderByDesc('created_at')
            ->get();

        $related = Content::where('content_group', 'podcast')
            ->where('uuid', '!=', $podcast->uuid)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return response()->json(
            [
                'success' => true,
                'podcast' => $podcast,
                'episodes' => $episodes,
                'related' => $related
            ],
            200,
            [],
            self::JSON_FLAGS
        );
    }


    /**
     * Podcast Episodes
     */
    public function episodes($slug)
    {
        $podcast = Content::where('slug', $slug)
            ->where('content_group', 'podcast')
            ->firstOrFail();

        $episodes = Content::where('parent_id', $podcast->uuid)
            ->where('content_group', 'podcast_episode')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json(
            [
                'success' => true,
                'data' => $episodes
            ],
            200,
            [],
            self::JSON_FLAGS
        );
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
