<?php

namespace App\Http\Middleware;

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
            return redirect()->route('user.login.form');
        }

        $eventId = $request->route('eventId');

        // Check if user has paid for THIS event
        $order = Order::query()
            ->where('user_id', $request->user()->id)
            ->where('payment_status', 'paid')
            ->whereHas('items.product', fn($query) => $query
                ->where('payable_id', $eventId)
                ->where('payable_type', \App\Models\Event::class))
            ->first();

        if (!$order) {
            return redirect()->route('events')->with('error', 'You must purchase a ticket for this event first.');
        }

        return $next($request);
    }
}
