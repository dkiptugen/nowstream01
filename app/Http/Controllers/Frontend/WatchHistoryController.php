<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
use App\Services\WatchHistoryService;
use Illuminate\Container\Attributes\Log;

class WatchHistoryController extends Controller
{
public function store(Request $request, WatchHistoryService $service)
{
    $request->validate([
        'uuid' => 'required|exists:contents,uuid',
        'watch_duration' => 'nullable|numeric|min:0',
    ]);

    try {
        $content = Content::where('uuid', $request->uuid)->firstOrFail();

        // Save watch progress
        $history = $service->record($content, intval($request->watch_duration));

        if (!$history) {
            Log::warning('PodcastController: failed to save watch history.', [
                'uuid' => $request->uuid,
                'user_id' => Auth::id()
            ]);
        }

        // Increment episode views
        $content->increment('views');
        Log::info('PodcastController: episode views incremented.', [
            'uuid' => $request->uuid,
            'views' => $content->views
        ]);

        // If it's a podcast episode, increment parent podcast views too
        $podcastViews = null;
        if ($content->content_group === 'podcast' && $content->parent_id) {
            $podcast = Content::find($content->parent_id);
            if ($podcast) {
                $podcast->increment('views');
                $podcastViews = $podcast->views;
                Log::info('PodcastController: parent podcast views incremented.', [
                    'parent_id' => $content->parent_id,
                    'views' => $podcastViews
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'episode_views' => $content->views,
            'podcast_views' => $podcastViews,
            'content_group' => $content->content_group
        ]);

    } catch (\Throwable $e) {
        Log::error('PodcastController: failed to store watch history.', [
            'uuid' => $request->uuid,
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to save watch history.'
        ], 500);
    }
}
}