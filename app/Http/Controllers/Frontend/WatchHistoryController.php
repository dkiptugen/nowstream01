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
        'watch_duration' => 'nullable|numeric|min:0'
    ]);

    try {
        $content = Content::where('uuid', $request->uuid)->firstOrFail();

        $history = $service->record($content, intval($request->watch_duration));

        // Increment views
        $content->increment('views');

        $podcastViews = null;
        if ($content->content_group === 'podcast' && $content->parent_id) {
            $podcast = Content::find($content->parent_id);
            if ($podcast) $podcastViews = $podcast->increment('views');
        }

        return response()->json([
            'success' => true,
            'episode_views' => $content->views,
            'podcast_views' => $podcastViews
        ]);

    } catch (\Throwable $e) {
        \Log::error('Watch history save failed', [
            'uuid' => $request->uuid,
            'user_id' => auth()->id(),
            'error' => $e->getMessage()
        ]);

        return response()->json(['success' => false], 500);
    }
}
}