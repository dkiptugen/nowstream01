<?php

namespace App\Http\Middleware;

use App\Models\Channel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckChannels
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check())
            {
                $user = Auth::guard('admin')->user();
                if (!Cache::has('user_channels_' .$user->id))
                    {
                        if($user->type == 'owner')
                            {
                                Cache::put('user_channels_' . $user->id, Channel::orderBy('created_at','desc')->limit(10)->get());
                            }
                        else
                            {
                                if(!is_null( $user->channels))
                                    {
                                        Cache::put('user_channels_' . $user->id, $user->channels);
                                    }
                            }

                    }

            }
        return $next($request);
    }
}
