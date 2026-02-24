<?php

namespace App\Services;

use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        if (!$user || !$watchable) return null;

        $data = [
            'watched_at' => now(),
            'watch_duration' => $watchDuration ?? 0,
        ];

        // Use content_group as watchable_type
        return WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'watchable_id' => $watchable->id,
                'watchable_type' => $watchable->content_group, 
            ],
            $data
        );
    }

    /**
     * Get paginated watch history for a user filtered by model type
     */
    public function getUserHistory(string $watchableType, int $perPage = 10)
    {
        $user = Auth::user();
        if (!$user) return null;

        return WatchHistory::where('user_id', $user->id)
            ->where('watchable_type', $watchableType)
            ->with('watchable')
            ->latest('watched_at')
            ->paginate($perPage);
    }
}