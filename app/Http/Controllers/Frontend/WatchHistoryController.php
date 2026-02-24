<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\WatchHistoryService;
use App\Models\Content;

class WatchHistoryController extends Controller
{
    protected $watchService;

    public function __construct(WatchHistoryService $watchService)
    {
        $this->watchService = $watchService;
    }

    public function record(Request $request)
    {
        $request->validate([
            'watchable_id' => 'required|integer',
        ]);

        $content = Content::findOrFail($request->watchable_id);

        $watchDuration = $request->input('watch_duration');

        $history = $this->watchService->record($content, $watchDuration);

        return response()->json([
            'success' => true,
            'watch_duration' => $history->watch_duration ?? null,
        ]);
    }
}