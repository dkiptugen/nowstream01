<?php

namespace App\Http\Middleware;

use App\Models\Stream;
use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEventPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if(Auth::check())
			{
				$eventId = $request->route('eventId');
				
				$subscription = Subscription::with(['event'])
				                            ->where('event_id',$eventId)
				                            ->where('user_id',$request->user()->id)
				                            ->first();
				if(!is_null($subscription))
					{
						if($subscription->status == 1)
							{
								$stream = Stream::select(['id','slug'])->whereEventId($subscription->event_id)
									            ->first();
								//dd(route('stream.show',[$stream->id,$stream->slug]));
								return redirect()->route('stream.show',[$stream->id,$stream->slug]);
							}
					}
			
			}
	   
        return $next($request);
    }
}
