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

    // Get single content by UUID
    $content = Content::where('uuid', $request->uuid)->firstOrFail();

    // Save watch progress
    $history = $service->record($content, intval($request->watch_duration));

    // Increment episode views
    $content->increment('views');

    // If it's a podcast episode, increment parent podcast views too
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