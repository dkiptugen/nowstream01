@extends('Frontend.includes.layout')

@section('header')
@include('Frontend.modules.shop.partials.theme')
@endsection

@section('content')
<main class="shop-page">
    <div class="container shop-shell">
        @include('Frontend.modules.shop.partials.flash')

        <section class="shop-hero p-4 p-lg-5 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="shop-kicker mb-3">Nowstream Store</p>
                    <h1 class="shop-title mb-3">Official merchandise, event drops, and exclusive fan gear.</h1>
                    <p class="shop-subtitle mb-4">Browse live event merch, limited products, and items attached to Nowstream experiences.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('cart.index') }}" class="shop-btn-primary">View Cart</a>
                        @auth
                            <a href="{{ route('shop.checkout') }}" class="shop-btn-secondary">Checkout</a>
                        @else
                            <a href="{{ route('user.login') }}" class="shop-btn-secondary">Login To Buy</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-5 mt-4 mt-lg-0">
                    <div class="shop-panel p-4">
                        <h2 class="h4 text-white mb-3">Store Highlights</h2>
                        <div class="shop-summary__row"><span>Products</span><strong>{{ $products->total() }}</strong></div>
                        <div class="shop-summary__row"><span>Live events</span><strong>{{ $events->count() }}</strong></div>
                        <div class="shop-summary__row"><span>Fresh videos</span><strong>{{ $videos->count() }}</strong></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="shop-kicker mb-2">Catalog</p>
                    <h2 class="h3 text-white mb-0">Merchandise</h2>
                </div>
            </div>

            @if($products->count())
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            @include('Frontend.modules.shop.partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="shop-panel shop-empty">
                    <h3 class="h4 text-white mb-2">No merchandise available right now.</h3>
                    <p class="shop-muted mb-0">Products will appear here once event merch is published.</p>
                </div>
            @endif
        </section>
    </div>
</main>
@endsection
