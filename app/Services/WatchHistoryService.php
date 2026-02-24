<?php

namespace App\Services;

use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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
        if (!$user || !$watchable) {
            Log::warning('[WatchHistory] No user or invalid content', [
                'user' => $user?->id,
                'content' => $watchable?->uuid ?? null,
            ]);
            return null;
        }

        $data = ['watched_at' => now()];

        if ($watchDuration !== null) {
            $data['watch_duration'] = $watchDuration;
        }

        Log::info('[WatchHistory] Saving watch history', [
            'user_id' => $user->id,
            'content_id' => $watchable->uuid,
            'watch_duration' => $watchDuration,
        ]);

        return WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $watchable->uuid,
            ],
            $data
        );
    }

    /**
     * Get paginated watch history for a user filtered by model type
     *
     * @param string $watchableType
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getUserHistory(string $watchableType, int $perPage = 10)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        return WatchHistory::where('user_id', $user->id)
            ->with('watchable')
            ->latest('watched_at')
            ->paginate($perPage);
    }
}