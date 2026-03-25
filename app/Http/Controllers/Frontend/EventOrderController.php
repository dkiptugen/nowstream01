<?php

namespace App\Http\Controllers\Frontend;

use App\Events\PaymentFailed;
use App\Http\Controllers\Controller;
use App\Libs\Mpesa;
use App\Models\Event;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Traits\Meta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventOrderController extends Controller
{
    use Meta;

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|uuid|exists:events,uuid',
            'rate_id' => 'required|integer|exists:products,id',
            'payment_method_id' => 'required|integer|in:1',
        ]);

        $event = Event::where('uuid', $validated['event_id'])->where('status', 1)->firstOrFail();
        $rate = $event->eventRates()->whereKey($validated['rate_id'])->first();

        if (!$rate) {
            return redirect()
                ->route('event.show', ['slug' => $event->slug])
                ->with('error', 'That ticket option is no longer available.');
        }

        $existingTicket = Ticket::where('user_id', $request->user()->id)
            ->where('event_id', $event->uuid)
            ->first();
        $existingPaidOrder = Order::query()
            ->where('user_id', $request->user()->id)
            ->where('payment_status', 'paid')
            ->whereHas('items.product', fn($query) => $query
                ->where('payable_id', $event->uuid)
                ->where('payable_type', Event::class))
            ->first();

        if ($existingTicket || $existingPaidOrder) {
            return redirect()
                ->route('event.success', ['eventId' => $event->uuid])
                ->with('success', 'You already have a paid ticket for this event.');
        }

        $order = null;

        DB::transaction(function () use (&$order, $request, $event, $rate) {
            $order = Order::query()
                ->where('user_id', $request->user()->id)
                ->where('payment_status', 'pending')
                ->whereHas('items', fn($query) => $query->where('product_id', $rate->id))
                ->latest()
                ->first();

            if (!$order) {
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'type' => 'product',
                    'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8)),
                    'subtotal' => $rate->price,
                    'tax' => 0,
                    'fees' => 0,
                    'total_amount' => $rate->price,
                    'currency' => $rate->currency ?? 'KES',
                    'payment_status' => 'pending',
                    'subscription_token' => (string) Str::uuid(),
                ]);

                $order->items()->create([
                    'product_id' => $rate->id,
                    'quantity' => 1,
                    'unit_price' => $rate->price,
                    'total_price' => $rate->price,
                ]);
            } else {
                $order->update([
                    'subtotal' => $rate->price,
                    'total_amount' => $rate->price,
                    'currency' => $rate->currency ?? 'KES',
                ]);
            }
        });

        return redirect()->route('event.payment.mpesa', ['order' => $order->id]);
    }

    public function mpesa(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->payment_status === 'paid') {
            $event = $this->getOrderEvent($order);

            return redirect()->route('event.success', ['eventId' => $event->uuid]);
        }

        $order->loadMissing('items.product');

        return view('Frontend.modules.payments.event-mpesa', compact('order'));
    }

    public function mpesaStk(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'msisdn' => 'required|string',
        ]);

        try {
            $order = Order::where('order_number', $request->order_number)
                ->where('user_id', Auth::id())
                ->with('items.product')
                ->firstOrFail();

            if ($order->payment_status === 'paid') {
                return response()->json(['message' => 'Order already paid.']);
            }

            $transaction = Transaction::find($order->latest_transaction_id);
            if (is_null($transaction)) {
                $transaction = new Transaction();
                $transaction->payment_method = 'mpesa';
                $transaction->cost = $order->total_amount;
                $transaction->amount_paid = 0;
                $transaction->currency = $order->currency;
                $transaction->event_id = optional($order->items->first()?->product)->payable_id;
                $transaction->channel_id = null;
                $transaction->order_id = $order->subscription_token;
                $transaction->user_id = Auth::id();
                $transaction->save();

                $order->latest_transaction_id = $transaction->id;
                $order->save();
            }

            $mpesa = new Mpesa('production');
            $mpesa->shortcode = config('custom.MPESA.MPESA_SHORTCODE');
            $mpesa->passkey = config('custom.MPESA.MPESA_PASS_KEY');
            $mpesa->consumerkey = config('custom.MPESA.MPESA_CONSUMER_KEY');
            $mpesa->consumersecret = config('custom.MPESA.MPESA_CONSUMER_SECRET');
            $mpesa->type = 'Paybill';
            $mpesa->msisdn = '254' . substr($this->removeSpaces($request->msisdn), -9);
            $mpesa->amount = (int) ceil($order->total_amount);
            $mpesa->ref = $order->order_number;
            $mpesa->stk_callback = route('mpesa.stk_push_request', ['subscription' => $order->order_number]);
            $mpesa->desc = 'payment for event ticket';

            $response = $mpesa->stkpush();

            $transaction->response = $response;
            $transaction->save();

            if (isset($response->errorCode)) {
                event(new PaymentFailed($order->order_number, ['message' => $response->errorMessage]));

                return response()->json(['message' => $response->errorMessage], 422);
            }

            return response()->json(['message' => 'STK push sent.']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Order not found.'], 404);
        }
    }

    public function success($eventId)
    {
        $event = Event::where('uuid', $eventId)->firstOrFail();
        $ticket = null;

        if (Auth::check()) {
            $ticket = Ticket::where('user_id', Auth::id())
                ->where('event_id', $event->uuid)
                ->latest()
                ->first();

            if (!$ticket) {
                $paidOrder = Order::query()
                    ->where('user_id', Auth::id())
                    ->where('payment_status', 'paid')
                    ->whereHas('items.product', fn($query) => $query->where('payable_id', $event->uuid)->where('payable_type', Event::class))
                    ->with('items.product')
                    ->latest('paid_at')
                    ->first();

                if ($paidOrder) {
                    $ticketProduct = optional($paidOrder->items->first())->product;

                    $ticket = Ticket::firstOrCreate(
                        [
                            'user_id' => Auth::id(),
                            'event_id' => $event->uuid,
                        ],
                        [
                            'type' => optional($ticketProduct)->name ?? 'Standard',
                            'price' => $paidOrder->total_amount,
                        ]
                    );
                }
            }
        }

        return view('Frontend.modules.payments.event-successful', compact('event', 'ticket'));
    }

    private function getOrderEvent(Order $order): Event
    {
        $order->loadMissing('items.product');

        $eventId = optional($order->items->first()?->product)->payable_id;

        return Event::where('uuid', $eventId)->firstOrFail();
    }
}
