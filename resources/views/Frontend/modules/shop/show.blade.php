@extends('Frontend.includes.layout')

@section('content')
@php
    $image = $product->image_url ?? asset('frontend-assets/images/default.png');
@endphp
<main>
    <section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-5">
                    <img src="{{ $image }}" class="img-fluid rounded" alt="{{ $product->name }}">
                </div>
                <div class="col-lg-7">
                    <div class="movie-details-content">
                        <h5>Merchandise</h5>
                        <h2>{{ $product->name }}</h2>
                        <p class="mb-3">{{ $product->description ?: 'Official merchandise item.' }}</p>
                        <p class="text-warning h4">{{ $product->currency }} {{ number_format($product->price, 2) }}</p>

                        <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            @if($product->variants->isNotEmpty())
                                <div class="mb-3">
                                    <label for="variant_id" class="form-label text-white">Variant</label>
                                    <select name="variant_id" id="variant_id" class="form-control">
                                        <option value="">Choose a variant</option>
                                        @foreach($product->variants as $variant)
                                            <option value="{{ $variant->id }}">
                                                {{ $variant->name }}@if($variant->price_override) - {{ $product->currency }} {{ number_format($variant->price_override, 2) }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="quantity" class="form-label text-white">Quantity</label>
                                <input type="number" min="1" value="1" name="quantity" id="quantity" class="form-control">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">Add to Cart</button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-light">View Cart</a>
                            </div>
                        </form>

                        @if($product->payable)
                            <div class="mt-4">
                                <a href="{{ route('event.show', ['slug' => $product->payable->slug]) }}" class="btn btn-outline-light">
                                    Back to Event
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($related->isNotEmpty())
                <div class="row g-4 mt-5">
                    <div class="col-12">
                        <div class="section-title">
                            <span class="sub-title">You May Also Like</span>
                            <h2 class="title">Related Merchandise</h2>
                        </div>
                    </div>
                    @foreach($related as $relatedProduct)
                        <div class="col-lg-3 col-md-6">
                            @include('Frontend.modules.shop.partials.product-card', ['product' => $relatedProduct])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>
@endsection
