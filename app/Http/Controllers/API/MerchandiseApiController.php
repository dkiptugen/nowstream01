<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MerchandiseApiController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->merch()
            ->active()
            ->with(['payable', 'variants'])
            ->when($request->filled('event_uuid'), function ($query) use ($request) {
                $query->where('payable_id', $request->string('event_uuid'))
                    ->where('payable_type', \App\Models\Event::class);
            })
            ->latest()
            ->paginate(20);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->type === 'merch' && $product->is_active, 404);

        return response()->json($product->load(['payable', 'variants']));
    }
}
