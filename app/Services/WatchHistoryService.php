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
        dd($watchable);
        $user = Auth::user();
        if (!$user || !$watchable) {
            return null;
        }

        $data = ['watched_at' => now()];

        if ($watchDuration !== null) {
            $data['watch_duration'] = $watchDuration;
        }

        return $watchable->watch()->updateOrCreate(
            ['user_id' => $user->id],
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
            ->where('watchable_type', $watchableType)
            ->with('watchable')
            ->latest('watched_at')
            ->paginate($perPage);
    }
}
