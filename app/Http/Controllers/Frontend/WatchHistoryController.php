<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Content;
use App\Services\WatchHistoryService; 

class WatchHistoryController extends Controller
{
    protected $watchService;

    public function __construct(WatchHistoryService $watchService)
    {
        $this->watchService = $watchService;
    }

public function store($uuid, WatchHistoryService $service)
{
    $content = Content::where('uuid', $uuid)->firstOrFail();
    $history = $service->record($content, request('watch_duration'));

    return response()->json([
        'success' => true,
        'watch_history' => $history
    ]);
}
}