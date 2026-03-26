@extends('Frontend.includes.layout')

@section('header')
@include('Frontend.modules.shop.partials.theme')
@endsection

@section('content')
<main class="shop-page">
    <div class="container shop-shell">
        @include('Frontend.modules.shop.partials.flash')

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <p class="shop-kicker mb-2">Shopping Cart</p>
                <h1 class="shop-title mb-0">Your merchandise cart</h1>
            </div>
            <a href="{{ route('shop.index') }}" class="shop-btn-secondary">Continue Shopping</a>
        </div>

        @if($summary['items']->isEmpty())
            <div class="shop-panel shop-empty">
                <h2 class="h4 text-white mb-2">Your cart is empty.</h2>
                <p class="shop-muted mb-4">Add a product from the store to begin checkout.</p>
                <a href="{{ route('shop.index') }}" class="shop-btn-primary">Browse Merchandise</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="shop-panel p-4">
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['items'] as $item)
                                    @php
                                        $product = $item['product'];
                                        $variant = $item['variant'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $product->image_url ?? asset('frontend-assets/images/default.png') }}" alt="{{ $product->name }}" width="72" height="72" style="border-radius:14px;object-fit:cover;">
                                                <div>
                                                    <div class="text-white font-weight-bold">{{ $product->name }}</div>
                                                    @if($variant)
                                                        <div class="shop-muted">Variant: {{ $variant->name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $summary['currency'] }} {{ number_format((float) $item['unit_price'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" min="0" value="{{ $item['quantity'] }}" class="shop-input" style="max-width:96px;">
                                                <button type="submit" class="shop-btn-secondary">Update</button>
                                            </form>
                                        </td>
                                        <td>{{ $summary['currency'] }} {{ number_format((float) $item['line_total'], 2) }}</td>
                                        <td class="text-right">
                                            <form action="{{ route('cart.destroy', $item['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="shop-btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="shop-summary p-4">
                        <h2 class="h4 text-white mb-3">Order Summary</h2>
                        <div class="shop-summary__row"><span>Subtotal</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['subtotal'], 2) }}</strong></div>
                        <div class="shop-summary__row"><span>Tax</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['tax'], 2) }}</strong></div>
                        <div class="shop-summary__row"><span>Fees</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['fees'], 2) }}</strong></div>
                        <div class="shop-summary__row"><span>Total</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['total'], 2) }}</strong></div>
                        <a href="{{ route('shop.checkout') }}" class="shop-btn-primary w-100 mt-3">Proceed To Checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
