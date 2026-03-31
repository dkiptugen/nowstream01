<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\MerchCartService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartApiController extends Controller
{
    public function __construct(private MerchCartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());
        return response()->json($this->cartService->cartSummary($cart));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()->merch()->active()->with('variants')->findOrFail($validated['product_id']);

        if ($product->variants->isNotEmpty() && empty($validated['variant_id'])) {
            return response()->json(['message' => 'Please choose a product variant.'], 422);
        }

        $variant = !empty($validated['variant_id'])
            ? $product->variants()->whereKey($validated['variant_id'])->firstOrFail()
            : null;

        try {
            $cart = $this->cartService->addItem($request->user(), $product, $variant, (int) ($validated['quantity'] ?? 1));
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'This product option is no longer available.'], 404);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        } catch (\Throwable $exception) {
            Log::error('Unable to add merchandise item to cart via API.', [
                'user_id' => $request->user()?->id,
                'product_id' => $validated['product_id'] ?? null,
                'variant_id' => $validated['variant_id'] ?? null,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json(['message' => 'Unable to add this item to cart right now.'], 500);
        }

        return response()->json($this->cartService->cartSummary($cart), 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $cart = $this->cartService->updateItem($request->user(), $cartItem->load('cart', 'product', 'variant'), (int) $validated['quantity']);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json($this->cartService->cartSummary($cart));
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $cart = $this->cartService->removeItem($request->user(), $cartItem->load('cart'));

        return response()->json($this->cartService->cartSummary($cart));
    }
}
