<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
	    // Get IP address
	    $ipAddress = $request->ip();
	    
	    // Get user agent
	    $userAgent = $request->header('User-Agent');
	    $agent = Agent::getFacadeRoot();
	    
	    // Log information
	    Log::info('User Activity', [
		    'ip_address' => $ipAddress,
		    'user_agent' => $userAgent,
		    'device' => $agent->device(),
		    'platform' => $agent->platform(),
		    'browser' => $agent->browser(),
		    'timestamp' => now(),
	    ]);
        return $next($request);
    }
}
