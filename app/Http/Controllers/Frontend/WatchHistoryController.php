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
        $user = Auth::user();
        if (!$user || !$watchable) {
            Log::warning('[WatchHistory] No user or watchable provided', [
                'user' => $user?->id,
                'watchable' => $watchable?->uuid ?? null
            ]);
            return null;
        }

        $data = [
            'watched_at' => now(),
            'watch_duration' => $watchDuration
        ];

        // Log what we are saving
        Log::info('[WatchHistory] Saving watch history', [
            'user_id' => $user->id,
            'content_id' => $watchable->uuid,
            'data' => $data
        ]);

        $history = WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $watchable->uuid,
            ],
            $data
        );

        // Log result
        Log::info('[WatchHistory] Saved successfully', [
            'id' => $history->id,
            'user_id' => $history->user_id,
            'content_id' => $history->content_id,
            'watched_at' => $history->watched_at,
            'watch_duration' => $history->watch_duration
        ]);

        return $history;
    }

    /**
     * Get paginated watch history for a user filtered by content type
     *
     * @param string|null $contentGroup
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getUserHistory(?string $contentGroup = null, int $perPage = 10)
    {
        $user = Auth::user();
        if (!$user) {
            Log::warning('[WatchHistory] Attempt to get history without a user');
            return null;
        }

        $query = WatchHistory::where('user_id', $user->id)->with('content');

        if ($contentGroup) {
            $query->whereHas('content', function($q) use ($contentGroup) {
                $q->where('content_group', $contentGroup);
            });
        }

        return $query->latest('watched_at')->paginate($perPage);
    }
}