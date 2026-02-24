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

    public function store(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'watch_duration' => 'nullable|integer',
        ]);

        $content = Content::where('uuid', $request->uuid)->first();
dd($request);
        if (!$content) {
            return response()->json(['error' => 'Content not found'], 404);
        }

        $history = $this->watchService->record($content, $request->watch_duration);

        return response()->json([
            'success' => true,
            'content_id' => $content->id,
            'watched_at' => $history->watched_at,
            'watch_duration' => $history->watch_duration,
        ]);
    }
}