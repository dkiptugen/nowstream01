<?php

namespace App\Services;

use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Content;

class WatchHistoryService
{
    /**
     * Record or update watch history for a user
     *
     * @param Content $content
     * @param int|null $watchDuration
     * @return WatchHistory|null
     */
    public function record(Content $content, ?int $watchDuration = null): ?WatchHistory
    {
        $user = Auth::user();
        if (!$user || !$content) {
            return null;
        }

        $data = [
            'watched_at' => now(),
        ];

        if ($watchDuration !== null) {
            $data['watch_duration'] = $watchDuration;
        }

        // Use content_id instead of watchable_id/watchable_type
        return WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $content->id,
            ],
            $data
        );
    }

    /**
     * Get paginated watch history for a user filtered by content group
     *
     * @param string|null $contentGroup
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getUserHistory(?string $contentGroup = null, int $perPage = 10)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $query = WatchHistory::where('user_id', $user->id)->with('content');

        if ($contentGroup) {
            $query->whereHas('content', fn($q) => $q->where('content_group', $contentGroup));
        }

        return $query->latest('watched_at')->paginate($perPage);
    }
}