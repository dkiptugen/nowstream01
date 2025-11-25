<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChooseChannelMiddleware
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
			    $user = Auth::guard ('admin')->user ();
			
				if(is_null ($user->user_active_channel))
					{
						return redirect()->route('choose_outlet');
					}
		    }
        return $next($request);
    }
}
