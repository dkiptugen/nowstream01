<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WatchHistoryService;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class WatchHistoryController extends Controller
{
    protected $watchHistoryService;

    public function __construct(WatchHistoryService $watchHistoryService)
    {
        $this->watchHistoryService = $watchHistoryService;
        $this->middleware('auth');
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