<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
use App\Services\WatchHistoryService;

class WatchHistoryController extends Controller
{
   public function store(Request $request, WatchHistoryService $service)
{
    $request->validate([
        'uuid' => 'required|exists:contents,uuid',
        'watch_duration' => 'nullable|numeric|min:0',
    ]);

    $content = Content::where('uuid', $request->uuid)->firstOrFail();

    $history = $service->record($content, intval($request->watch_duration));

    // Increment episode views
    $content->increment('views');

    // Increment podcast views if episode
    if ($content->content_group === 'podcast' && $content->parent_id) {
        Content::find($content->parent_id)?->increment('views');
    }

    return response()->json([
        'success' => true,
        'episode_views' => $content->views,
        'podcast_views' => $content->parent_id ? Content::find($content->parent_id)->views : null,
        'content_group' => $content->content_group
    ]);
}
}