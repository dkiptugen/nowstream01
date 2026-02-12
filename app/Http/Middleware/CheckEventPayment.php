<?php

namespace App\Http\Middleware;

use App\Models\Content;
use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEventPayment
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $eventId = $request->route('eventId');

            $subscription = Order::where('user_id', $request->user()->id)
                                        ->first(); 

            if ($subscription && $subscription->status == 1) {
                $stream = Content::where('event_id', $eventId)
                                 ->select(['id', 'slug'])
                                 ->first();

                if ($stream) {
                    return redirect()->route('stream.show', [$stream->id, $stream->slug]);
                }
            }
        }

        // If user is not subscribed or no stream exists, continue to event page
        return $next($request);
    }
}
