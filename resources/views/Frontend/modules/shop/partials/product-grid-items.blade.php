@foreach($products as $product)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        @include('Frontend.modules.shop.partials.product-card', ['product' => $product])
    </div>
@endforeach
