<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEventPayment
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('user.login.form');
        }

        $eventId = $request->route('eventId');

        // Check if user has paid for THIS event
        $subscription = Subscription::where('user_id', $request->user()->id)
            ->where('event_id', $eventId)
            ->where('status', 1)
            ->first();

        if (!$subscription) {
            return redirect()->route('events')->with('error', 'You must purchase a ticket for this event first.');
        }

        return $next($request);
    }
}
