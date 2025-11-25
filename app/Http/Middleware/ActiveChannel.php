<?php

namespace App\Http\Middleware;

use App\Models\Channel;
use App\Models\SystemUserChannel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveChannel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        header("Content-Security-Policy: upgrade-insecure-requests");
        if ($request->route()->hasParameter('channel'))
            {

                        $channel                   = SystemUserChannel::with(['channel'])
                                                                      ->where('channel_id', $request->route('channel'))
                                                                      ->where('system_user_id', $request->user()->id)
                                                                      ->first();
                        if(!is_null($channel))
                            {
                                $user                      = Auth::guard('admin')->user();
                                $user->user_active_channel = $channel->identifier;
                                $user->save();
                            }



            }
        return $next($request);
    }
}
