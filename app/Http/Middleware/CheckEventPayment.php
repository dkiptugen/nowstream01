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
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $eventId = $request->route('eventId');

        // Check if user has paid for THIS event
        $subscription = Order::where('user_id', $request->user()->id)
            ->where('event_id', $eventId)
            ->where('status', 1)
            ->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'You must purchase access to view this stream.');
        }

        return $next($request);
    }
}