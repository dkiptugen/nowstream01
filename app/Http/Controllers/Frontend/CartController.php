<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\MerchCartService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            ->active()
            ->with('variants')
            ->findOrFail($validated['product_id']);

        if ($product->variants->isNotEmpty() && empty($validated['variant_id'])) {
            $message = 'Please choose a product variant.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        $variant = !empty($validated['variant_id'])
            ? $product->variants()->whereKey($validated['variant_id'])->firstOrFail()
            : null;

        try {
            $cart = $this->cartService->addItem($request->user(), $product, $variant, (int) ($validated['quantity'] ?? 1));
        } catch (ModelNotFoundException $exception) {
            $message = 'This product option is no longer available.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 404);
            }

            return redirect()->back()->with('error', $message);
        } catch (\RuntimeException $exception) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $exception->getMessage()], 422);
            }

            return redirect()->back()->with('error', $exception->getMessage());
        } catch (\Throwable $exception) {
            Log::error('Unable to add merchandise item to cart.', [
                'user_id' => $request->user()?->id,
                'product_id' => $validated['product_id'] ?? null,
                'variant_id' => $validated['variant_id'] ?? null,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            $message = 'Unable to add this item to cart right now.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 500);
            }

            return redirect()->back()->with('error', $message);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return $this->storeAjaxResponse($cart, 'Item added to cart.');
        }

        return redirect()
            ->back()
            ->with('success', 'Item added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $cart = $this->cartService->updateItem(
                $request->user(),
                $cartItem->load('cart', 'product', 'variant'),
                (int) $validated['quantity']
            );
        } catch (\RuntimeException $exception) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $exception->getMessage()], 422);
            }

            return redirect()->back()->with('error', $exception->getMessage());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return $this->ajaxCartResponse($cart, 'Cart updated.');
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $cart = $this->cartService->removeItem($request->user(), $cartItem->load('cart'));

        if ($request->expectsJson() || $request->ajax()) {
            return $this->ajaxCartResponse($cart, 'Item removed from cart.');
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }

    protected function ajaxCartResponse($cart, string $message)
    {
        $summary = $this->cartService->cartSummary($cart);

        return response()->json([
            'message' => $message,
            'count' => $summary['items']->sum('quantity'),
            'empty' => $summary['items']->isEmpty(),
            'items_html' => view('Frontend.modules.shop.partials.cart-items', compact('summary'))->render(),
            'summary_html' => view('Frontend.modules.shop.partials.cart-summary', compact('summary'))->render(),
            'empty_html' => view('Frontend.modules.shop.partials.cart-empty')->render(),
        ]);
    }

    protected function storeAjaxResponse($cart, string $message)
    {
        $summary = $this->cartService->cartSummary($cart);

        return response()->json([
            'message' => $message,
            'count' => $summary['items']->sum('quantity'),
            'empty' => $summary['items']->isEmpty(),
        ]);
    }
}
