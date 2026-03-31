<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchandiseController extends Controller
{
    use CacheHelper;

    public function index(Request $request)
    {
        $products = Product::query()
            ->merch()
            ->with(['payable', 'variants'])
            ->latest()
            ->paginate(12);
        $products->appends($request->all());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Frontend.modules.shop.partials.product-grid-items', compact('products'))->render(),
                'hasMore' => $products->hasMorePages(),
                'nextPageUrl' => $products->nextPageUrl(),
            ]);
        }

        $events = $this->get_events();
        $videos = $this->get_videos();

        return view('Frontend.modules.shop.index', compact('products', 'events', 'videos'));
    }

    public function show(Product $product)
    {
        abort_unless($product->type === 'merch', 404);

        $product->loadMissing(['payable', 'variants']);

        $related = Cache::remember("shop_related_{$product->id}", now()->addMinutes(30), function () use ($product) {
            return Product::query()
                ->merch() 
                ->whereKeyNot($product->id)
                ->with(['payable', 'variants'])
                ->latest()
                ->take(4)
                ->get();
        });

        return view('Frontend.modules.shop.show', compact('product', 'related'));
    }
}
