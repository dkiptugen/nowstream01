@php
    $image = $product->image_url ?? asset('frontend-assets/images/default.png');
    $remaining = $product->stock_total !== null ? max(0, (int) $product->stock_total - (int) $product->stock_sold) : null;
    $payableLabel = $product->payable?->event_name ?? $product->payable?->title ?? 'Nowstream Merch';
@endphp

<div class="shop-card">
    <a href="{{ route('shop.show', $product) }}">
        <img
            src="{{ $image }}"
            alt="{{ $product->name }}"
            class="shop-card__image"
            loading="lazy"
            decoding="async"
        >
    </a>
    <div class="shop-card__body">
        <p class="shop-kicker mb-2">Merchandise</p>
        <h3 class="h5 mb-2">
            <a href="{{ route('shop.show', $product) }}" class="text-white">{{ $product->name }}</a>
        </h3>
        <p class="shop-muted mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 90) }}</p>
        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <span class="shop-price">{{ $product->currency ?? 'KES' }} {{ number_format((float) $product->price, 2) }}</span>
            <span class="shop-muted">{{ $remaining === null ? 'In stock' : $remaining . ' left' }}</span>
        </div>
        <div class="shop-meta mb-3">
            <span>{{ $payableLabel }}</span>
            @if($product->variants->isNotEmpty())
                <span>{{ $product->variants->count() }} variants</span>
            @endif
        </div>
        <a href="{{ route('shop.show', $product) }}" class="shop-btn-secondary w-100">View Product</a>
    </div>
</div>
