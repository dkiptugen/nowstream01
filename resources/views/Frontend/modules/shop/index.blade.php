@extends('Frontend.includes.layout')

@section('content')
<main>
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <div class="section-title">
                        <span class="sub-title">Official Merchandise</span>
                        <h2 class="title">Shop</h2>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('cart.index') }}" class="btn btn-warning">View Cart</a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-lg-3 col-md-6">
                        @include('Frontend.modules.shop.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary">No merchandise available right now.</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</main>
@endsection
