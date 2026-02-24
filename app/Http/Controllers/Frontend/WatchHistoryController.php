<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Services\WatchHistoryService; 

class WatchHistoryController extends Controller
{
    protected $watchService;

    public function __construct(WatchHistoryService $watchService)
    {
        $this->watchService = $watchService;
    }

    public function store(Request $request, $uuid)
    {
        // Find the episode by UUID
        $episode = Content::where('uuid', $uuid)->firstOrFail();

        // Watch duration from request (optional)
        $watchDuration = $request->input('watch_duration', null);

        // Record in DB
        $history = $this->watchService->record($episode, $watchDuration);

        return response()->json([
            'success' => (bool) $history,
            'watch_duration' => $watchDuration,
            'episode_id' => $episode->id,
            'user_id' => auth()->id(),
        ]);
    }
}