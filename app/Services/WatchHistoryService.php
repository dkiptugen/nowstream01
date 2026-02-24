<?php

namespace App\Services;

use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class WatchHistoryService
{
    /**
     * Record or update watch history for a user
     *
     * @param Model $watchable
     * @param int|null $watchDuration
     * @return WatchHistory|null
     */
  public function record(Model $watchable, ?int $watchDuration = null): ?WatchHistory
{
    $user = auth()->user();
    if (!$user || !$watchable) return null;

    try {
        return WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'watchable_id' => $watchable->uuid,
                'watchable_type' => $watchable->content_group
            ],
            [
                'watched_at' => now(),
                'watch_duration' => $watchDuration ?? 0
            ]
        );
    } catch (\Throwable $e) {
        \Log::error('WatchHistoryService failed', [
            'user_id' => $user->id,
            'watchable_id' => $watchable->uuid,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}

    /**
     * Get paginated watch history for a user filtered by model type
     */
    public function getUserHistory(string $watchableType, int $perPage = 10)
    {
        $user = Auth::user();
        if (!$user) {
            Log::warning('WatchHistoryService: getUserHistory called without user.');
            return null;
        }

        try {
            return WatchHistory::where('user_id', $user->id)
                ->where('watchable_type', $watchableType)
                ->with('watchable')
                ->latest('watched_at')
                ->paginate($perPage);
        } catch (\Throwable $e) {
            Log::error('WatchHistoryService: failed to fetch watch history.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'watchable_type' => $watchableType,
            ]);
            return null;
        }
    }
}