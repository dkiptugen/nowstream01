<?php

namespace App\Http\Controllers\Frontend;

use App\Events\PaymentFailed;
use App\Http\Controllers\Controller;
use App\Libs\Mpesa;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\MerchCartService;
use App\Traits\Meta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MerchCheckoutController extends Controller
{
    use Meta;

    public function __construct(private MerchCartService $cartService)
    {
    }

    public function create(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());
        $summary = $this->cartService->cartSummary($cart);

        if ($summary['items']->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        return view('Frontend.modules.shop.checkout', compact('cart', 'summary'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'delivery_address' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method_id' => ['required', 'integer', 'in:1'],
        ]);

        try {
            $order = $this->cartService->checkout($request->user(), $validated);

            return redirect()->route('shop.payment.mpesa', ['order' => $order->id]);
        } catch (\RuntimeException $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function mpesa(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->payment_status === 'paid') {
            return redirect()->route('shop.success', ['order' => $order->id]);
        }

        $order->loadMissing('items.product', 'items.variant');

        return view('Frontend.modules.shop.mpesa', compact('order'));
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
                ->with('items.product', 'items.variant')
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
                $transaction->event_id = null;
                $transaction->channel_id = null;
                $transaction->order_id = $order->subscription_token;
                $transaction->user_id = Auth::id();
                $transaction->save();

                $order->latest_transaction_id = $transaction->id;
                $order->save();
            }

            $shortcode = config('mpesa.paybill');
            $passkey = config('mpesa.pass_key');
            $consumerKey = config('mpesa.consumer_key');
            $consumerSecret = config('mpesa.consumer_secret');

            if (!$shortcode || !$passkey || !$consumerKey || !$consumerSecret) {
                return response()->json(['message' => 'M-Pesa is not configured right now.'], 500);
            }

            $mpesa = new Mpesa('production');
            $mpesa->shortcode = $shortcode;
            $mpesa->passkey = $passkey;
            $mpesa->consumerkey = $consumerKey;
            $mpesa->consumersecret = $consumerSecret;
            $mpesa->type = 'Paybill';
            $mpesa->msisdn = '254' . substr($this->removeSpaces($request->msisdn), -9);
            $mpesa->amount = (int) ceil($order->total_amount);
            $mpesa->ref = $order->order_number;
            $mpesa->stk_callback = route('mpesa.stk_push_request', ['subscription' => $order->order_number]);
            $mpesa->desc = 'payment for merchandise order';

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
        } catch (\Throwable $exception) {
            Log::error('Merch M-Pesa STK request failed.', [
                'order_number' => $request->order_number,
                'user_id' => Auth::id(),
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['message' => 'Unable to initiate M-Pesa payment right now.'], 500);
        }
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->loadMissing('items.product.payable', 'items.variant');

        return view('Frontend.modules.shop.success', compact('order'));
    }
}
