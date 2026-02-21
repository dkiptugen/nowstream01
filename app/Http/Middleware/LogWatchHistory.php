<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\WatchHistory;

class LogWatchHistory
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if the user is authenticated and if the request contains video data
        if (Auth::check()) {
            // Log the watch history
            WatchHistory::create([
                'user_id' => Auth::id(),
                'content_id' => $request->content_uuid,
                'watched_at' => now(),
                'watch_duration' => $request->watch_duration // If you have the duration available
            ]);
        }

        return $response;
    }
}
