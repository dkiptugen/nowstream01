<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BelongsToChannel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $channelId = $request->route('channel'); // Assuming the channel ID is passed as a route parameter

        $user = Auth::guard('admin')->user();

        // Assuming the User model has a channels() relationship
        if (!$user->channels()->where('channels.id', $channelId)->exists() && $user->type=='stream_partner')
            {
                return redirect()->route('admin_dashboard')->with('error', 'You do not have access to this channel.');
            }
        return $next($request);
    }
}
