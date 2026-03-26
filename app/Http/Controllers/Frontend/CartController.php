<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\MerchCartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private MerchCartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());
        $summary = $this->cartService->cartSummary($cart);

        return view('Frontend.modules.shop.cart', compact('cart', 'summary'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->merch() 
            ->with('variants')
            ->findOrFail($validated['product_id']);
        $variantId = $validated['variant_id'] ?? null;

        $variant = $variantId
            ? $product->variants()->whereKey($validated['variant_id'])->firstOrFail()
            : null;

        $this->cartService->addItem($request->user(), $product, $variant, (int) ($validated['quantity'] ?? 1));

        return redirect()
            ->back()
            ->with('success', 'Item added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateItem($request->user(), $cartItem->load('cart', 'product', 'variant'), (int) $validated['quantity']);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $this->cartService->removeItem($request->user(), $cartItem->load('cart'));

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }
}
