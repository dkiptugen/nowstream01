<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Services\WatchHistoryService;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;

class WatchHistoryController extends Controller
{
    protected $watchHistoryService;

    public function __construct(WatchHistoryService $watchHistoryService)
    {
        $this->watchHistoryService = $watchHistoryService; 
    }

    public function store(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'watch_duration' => 'nullable|integer',
        ]);

        $content = Content::where('uuid', $request->uuid)->firstOrFail();

        $history = $this->watchHistoryService->record($content, $request->watch_duration);

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }
}