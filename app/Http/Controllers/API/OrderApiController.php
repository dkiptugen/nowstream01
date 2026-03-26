<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MerchCartService;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function __construct(private MerchCartService $cartService)
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'delivery_address' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $order = $this->cartService->checkout($request->user(), $validated);

            return response()->json([
                'order' => $order,
                'payment_url' => route('shop.payment.mpesa', ['order' => $order->id]),
            ], 201);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function show(Request $request, Order $order)
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);

        return response()->json($order->load('items.product.payable', 'items.variant'));
    }
}
