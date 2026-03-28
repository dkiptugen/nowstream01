<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\CacheHelper;
use Illuminate\Support\Facades\Cache;

class MerchandiseController extends Controller
{
    use CacheHelper;

    public function index()
    {
        $products = Product::query()
            ->merch()
            ->active()
            ->with(['payable', 'variants'])
            ->latest()
            ->paginate(12);

        $events = $this->get_events();
        $videos = $this->get_videos();

        return view('Frontend.modules.shop.index', compact('products', 'events', 'videos'));
    }

    public function show(Product $product)
    {
        abort_unless($product->type === 'merch' && $product->is_active, 404);

        $product->loadMissing(['payable', 'variants']);

        $related = Cache::remember("shop_related_{$product->id}", now()->addMinutes(30), function () use ($product) {
            return Product::query()
                ->merch()
                ->active()
                ->whereKeyNot($product->id)
                ->with(['payable', 'variants'])
                ->latest()
                ->take(4)
                ->get();
        });

        return view('Frontend.modules.shop.show', compact('product', 'related'));
    }
}
