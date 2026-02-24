<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;

class WatchHistoryController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'uuid' => 'required|exists:contents,uuid',
            'watch_duration' => 'nullable|numeric|min:0',
        ]);

        $content = Content::where('uuid', $request->uuid)->firstOrFail();

        // Save or update watch history using UUID
        $history = WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $content->uuid, 
            ],
            [
                'watch_duration' => $request->watch_duration,
                'watched_at' => now(),
            ]
        );

        // Increment episode views
        $content->increment('views');

        // If it's a podcast episode, increment parent podcast views too
        if ($content->content_group === 'podcast' && $content->parent_id) {
            $parent = Content::find($content->parent_id);
            if ($parent) $parent->increment('views');
        }

        return response()->json([
            'success' => true,
            'episode_views' => $content->views,
            'podcast_views' => $content->parent_id ? Content::find($content->parent_id)->views : null,
            'content_group' => $content->content_group
        ]);
    }
}