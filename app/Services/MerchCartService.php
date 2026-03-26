<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MerchCartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function getCart(User $user): Cart
    {
        return $this->getOrCreateCart($user)->load('items.product.payable', 'items.variant');
    }

    public function addItem(User $user, Product $product, ?ProductVariant $variant, int $quantity = 1): Cart
    {
        $cart = $this->getOrCreateCart($user);

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
        ]);

        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;
        $this->assertInventory($product, $variant, $item->quantity);
        $item->save();

        return $this->getCart($user);
    }

    public function updateItem(User $user, CartItem $item, int $quantity): Cart
    {
        $this->authorizeItem($user, $item);

        if ($quantity <= 0) {
            $item->delete();
            return $this->getCart($user);
        }

        $this->assertInventory($item->product, $item->variant, $quantity);
        $item->update(['quantity' => $quantity]);

        return $this->getCart($user);
    }

    public function removeItem(User $user, CartItem $item): Cart
    {
        $this->authorizeItem($user, $item);
        $item->delete();

        return $this->getCart($user);
    }

    public function cartSummary(Cart $cart): array
    {
        $cart->loadMissing('items.product.payable', 'items.variant');

        $items = $cart->items->map(function (CartItem $item) {
            $unitPrice = $item->variant?->price_override ?? $item->product->price;
            $lineTotal = $unitPrice * $item->quantity;

            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'unit_price' => (float) $unitPrice,
                'line_total' => (float) $lineTotal,
                'product' => $item->product,
                'variant' => $item->variant,
            ];
        })->values();

        $subtotal = $items->sum('line_total');

        return [
            'items' => $items,
            'subtotal' => (float) $subtotal,
            'tax' => 0.0,
            'fees' => 0.0,
            'total' => (float) $subtotal,
            'currency' => optional($cart->items->first()?->product)->currency ?? 'KES',
        ];
    }

    public function checkout(User $user, array $payload): Order
    {
        $cart = $this->getCart($user);
        $summary = $this->cartSummary($cart);

        if ($summary['items']->isEmpty()) {
            throw new \RuntimeException('Your cart is empty.');
        }

        return DB::transaction(function () use ($user, $payload, $cart, $summary) {
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $payload['customer_name'],
                'customer_phone' => $payload['customer_phone'],
                'type' => 'product',
                'order_number' => 'SHOP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8)),
                'subtotal' => $summary['subtotal'],
                'tax' => $summary['tax'],
                'fees' => $summary['fees'],
                'total_amount' => $summary['total'],
                'currency' => $summary['currency'],
                'delivery_address' => $payload['delivery_address'] ?? null,
                'notes' => $payload['notes'] ?? null,
                'payment_status' => 'pending',
                'subscription_token' => (string) Str::uuid(),
            ]);

            foreach ($summary['items'] as $line) {
                $this->assertInventory($line['product'], $line['variant'], $line['quantity']);

                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'variant_id' => $line['variant']?->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'total_price' => $line['line_total'],
                ]);
            }

            $cart->items()->delete();

            return $order->load('items.product.payable', 'items.variant');
        });
    }

    private function authorizeItem(User $user, CartItem $item): void
    {
        if ((int) $item->cart->user_id !== (int) $user->id) {
            abort(403);
        }
    }

    private function assertInventory(Product $product, ?ProductVariant $variant, int $quantity): void
    {
        $stockTotal = $variant?->stock_total ?? $product->stock_total;
        $stockSold = $variant?->stock_sold ?? $product->stock_sold;

        if ($stockTotal === null) {
            return;
        }

        $remaining = max(0, $stockTotal - $stockSold);
        if ($quantity > $remaining) {
            throw new \RuntimeException('Requested quantity exceeds available stock.');
        }
    }
}
