<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
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

	public function watchedContent()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('user.login')
            ->with('error', 'You must be logged in to view watched videos.');
    }

    $items = Content::whereHas('watch', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->latest()
        ->paginate(10);

    return view('Frontend.modules.videos.continue', compact('items'));
}
}