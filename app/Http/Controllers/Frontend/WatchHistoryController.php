<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Content;
use App\Services\WatchHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WatchHistoryController extends Controller
{
    protected $watchService;

    public function __construct(WatchHistoryService $watchService)
    {
        $this->watchService = $watchService;
    }

    public function store(Request $request, $uuid)
    {
        $request->validate([
            'watch_duration' => 'nullable|integer|min:0'
        ]);

        $content = Content::where('uuid', $uuid)->first();

        if (!$content) {
            Log::warning("[WatchHistory] Content not found", ['uuid' => $uuid]);
            return response()->json(['message' => 'Content not found'], 404);
        }

        $history = $this->watchService->record($content, $request->watch_duration);

        if (!$history) {
            Log::error("[WatchHistory] Failed to save watch history", [
                'uuid' => $uuid,
                'user_id' => auth()->id()
            ]);
            return response()->json(['message' => 'Failed to save watch history'], 500);
        }

        return response()->json([
            'message' => 'Watch history saved',
            'history' => $history
        ]);
    }
}