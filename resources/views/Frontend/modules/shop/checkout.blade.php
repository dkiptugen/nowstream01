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
                <p class="shop-kicker mb-2">Checkout</p>
                <h1 class="shop-title mb-0">Confirm your order</h1>
            </div>
            <a href="{{ route('cart.index') }}" class="shop-btn-secondary">Back To Cart</a>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <form action="{{ route('shop.checkout.store') }}" method="POST" class="shop-form-card p-4 p-lg-5">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="shop-label" for="customer_name">Full Name</label>
                            <input type="text" class="shop-input" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="shop-label" for="customer_phone">Phone Number</label>
                            <input type="text" class="shop-input" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="shop-label" for="delivery_address">Delivery Address</label>
                            <textarea class="shop-textarea" id="delivery_address" name="delivery_address">{{ old('delivery_address') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="shop-label" for="notes">Notes</label>
                            <textarea class="shop-textarea" id="notes" name="notes">{{ old('notes') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="shop-label" for="payment_method_id">Payment Method</label>
                            <select id="payment_method_id" name="payment_method_id" class="shop-select" required>
                                <option value="1" selected>M-Pesa</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="shop-btn-primary">Place Order</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="shop-summary p-4">
                    <h2 class="h4 text-white mb-3">Items</h2>
                    @foreach($summary['items'] as $item)
                        <div class="shop-summary__row">
                            <div>
                                <div class="text-white">{{ $item['product']->name }}</div>
                                <div class="shop-muted">
                                    Qty {{ $item['quantity'] }}
                                    @if($item['variant'])
                                        · {{ $item['variant']->name }}
                                    @endif
                                </div>
                            </div>
                            <strong>{{ $summary['currency'] }} {{ number_format((float) $item['line_total'], 2) }}</strong>
                        </div>
                    @endforeach
                    <div class="shop-summary__row"><span>Subtotal</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['subtotal'], 2) }}</strong></div>
                    <div class="shop-summary__row"><span>Total</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['total'], 2) }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
