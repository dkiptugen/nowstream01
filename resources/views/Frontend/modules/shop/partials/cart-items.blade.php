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
                    <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="d-flex align-items-center gap-2 js-cart-update-form">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" min="0" value="{{ $item['quantity'] }}" class="shop-input" style="max-width:96px;">
                        <button type="submit" class="shop-btn-secondary">Update</button>
                    </form>
                </td>
                <td>{{ $summary['currency'] }} {{ number_format((float) $item['line_total'], 2) }}</td>
                <td class="text-right">
                    <form action="{{ route('cart.destroy', $item['id']) }}" method="POST" class="js-cart-remove-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="shop-btn-danger">Remove</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
