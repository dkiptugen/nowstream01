@php
    $image = $product->image_url ?? asset('frontend-assets/images/default.png');
    $hasVariants = $product->variants->isNotEmpty();
    $basePrice = $hasVariants
        ? ($product->variants->min(fn($variant) => $variant->price_override ?? $product->price) ?? $product->price)
        : $product->price;
@endphp
<div class="movie-item h-100">
    <div class="movie-poster position-relative">
        <a href="{{ route('shop.show', ['product' => $product->id]) }}">
            <img src="{{ $image }}" class="w-100 d-block" alt="{{ $product->name }}" style="object-fit: cover; aspect-ratio: 1 / 1;" loading="lazy">
        </a>
    </div>
    <div class="movie-content">
        <div class="top">
            <h6 class="mt-0">
                <a href="{{ route('shop.show', ['product' => $product->id]) }}">{{ $product->name }}</a>
            </h6>
        </div>
        <p class="mb-2 small text-light">
            @if($product->payable)
                Linked to {{ $product->payable->event_name ?? $product->payable->title ?? 'Event' }}
            @else
                Merchandise item
            @endif
        </p>
        <div class="d-flex align-items-center justify-content-between gap-2">
            <span class="text-warning fw-bold">{{ $product->currency }} {{ number_format($basePrice, 2) }}</span>
            <a href="{{ route('shop.show', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-light">View</a>
        </div>
    </div>
</div>
