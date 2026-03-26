@extends('Frontend.includes.layout')

@section('header')
@include('Frontend.modules.shop.partials.theme')
@endsection

@section('content')
@php
    $image = $product->image_url ?? asset('frontend-assets/images/default.png');
    $payableLabel = $product->payable?->event_name ?? $product->payable?->title ?? 'Nowstream Merch';
    $remaining = $product->stock_total !== null ? max(0, (int) $product->stock_total - (int) $product->stock_sold) : null;
@endphp

<main class="shop-page">
    <div class="container shop-shell">
        @include('Frontend.modules.shop.partials.flash')

        <section class="shop-hero p-4 p-lg-5 mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <div class="shop-product-media">
                        <img src="{{ $image }}" alt="{{ $product->name }}">
                    </div>
                </div>
                <div class="col-lg-7">
                    <p class="shop-kicker mb-3">Merchandise</p>
                    <h1 class="shop-title mb-3">{{ $product->name }}</h1>
                    <p class="shop-subtitle mb-3">{{ $product->description ?: 'Official Nowstream merchandise.' }}</p>

                    <div class="shop-meta mb-4">
                        <span>{{ $payableLabel }}</span>
                        <span>{{ $product->currency ?? 'KES' }} {{ number_format((float) $product->price, 2) }}</span>
                        <span>{{ $remaining === null ? 'Unlimited stock' : $remaining . ' left in stock' }}</span>
                    </div>

                    @auth
                        <form action="{{ route('cart.store') }}" method="POST" class="shop-form-card p-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="row g-3">
                                @if($product->variants->isNotEmpty())
                                    <div class="col-md-7">
                                        <label class="shop-label" for="variant_id">Variant</label>
                                        <select name="variant_id" id="variant_id" class="shop-select">
                                            <option value="">Choose a variant</option>
                                            @foreach($product->variants as $variant)
                                                @php
                                                    $variantRemaining = $variant->stock_total !== null ? max(0, (int) $variant->stock_total - (int) $variant->stock_sold) : null;
                                                    $variantPrice = $variant->price_override ?? $product->price;
                                                @endphp
                                                <option value="{{ $variant->id }}">
                                                    {{ $variant->name }} - {{ $product->currency ?? 'KES' }} {{ number_format((float) $variantPrice, 2) }}
                                                    @if($variantRemaining !== null)
                                                        ({{ $variantRemaining }} left)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-{{ $product->variants->isNotEmpty() ? '5' : '4' }}">
                                    <label class="shop-label" for="quantity">Quantity</label>
                                    <input type="number" min="1" value="1" name="quantity" id="quantity" class="shop-input">
                                </div>
                                <div class="col-12 d-flex flex-wrap gap-3">
                                    <button type="submit" class="shop-btn-primary">Add To Cart</button>
                                    <a href="{{ route('cart.index') }}" class="shop-btn-secondary">View Cart</a>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="shop-panel p-4">
                            <p class="shop-muted mb-3">Login to add this item to your cart and continue to checkout.</p>
                            <a href="{{ route('user.login') }}" class="shop-btn-primary">Login To Buy</a>
                        </div>
                    @endauth
                </div>
            </div>
        </section>

        @if($related->isNotEmpty())
            <section>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="shop-kicker mb-2">More Merch</p>
                        <h2 class="h3 text-white mb-0">Related Products</h2>
                    </div>
                    <a href="{{ route('shop.index') }}" class="shop-btn-secondary">Back To Store</a>
                </div>

                <div class="row g-4">
                    @foreach($related as $product)
                        <div class="col-sm-6 col-lg-3">
                            @include('Frontend.modules.shop.partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</main>
@endsection
