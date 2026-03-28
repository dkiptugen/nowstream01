@extends('Frontend.includes.layout')

@section('header')
@include('Frontend.modules.shop.partials.theme')
@endsection

@section('content')
<main class="shop-page">
    <div class="container shop-shell">
        <section class="shop-hero p-4 p-lg-5">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <p class="shop-kicker mb-2">Order Status</p>
                    <h1 class="shop-title mb-0">Your merchandise order</h1>
                </div>
                <span class="shop-order-badge">{{ ucfirst($order->payment_status) }}</span>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="shop-panel p-4 h-100">
                        <div class="shop-summary__row"><span>Order number</span><strong>{{ $order->order_number }}</strong></div>
                        <div class="shop-summary__row"><span>Customer</span><strong>{{ $order->customer_name }}</strong></div>
                        <div class="shop-summary__row"><span>Phone</span><strong>{{ $order->customer_phone }}</strong></div>
                        <div class="shop-summary__row"><span>Payment</span><strong>{{ ucfirst($order->payment_status) }}</strong></div>
                        @if($order->delivery_address)
                            <div class="shop-summary__row"><span>Delivery</span><strong>{{ $order->delivery_address }}</strong></div>
                        @endif
                        @if($order->notes)
                            <div class="shop-summary__row"><span>Notes</span><strong>{{ $order->notes }}</strong></div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="shop-summary p-4">
                        <h2 class="h4 text-white mb-3">Items</h2>
                        @foreach($order->items as $item)
                            <div class="shop-summary__row">
                                <div>
                                    <div class="text-white">{{ $item->product?->name }}</div>
                                    <div class="shop-muted">
                                        Qty {{ $item->quantity }}
                                        @if($item->variant)
                                            · {{ $item->variant->name }}
                                        @endif
                                    </div>
                                </div>
                                <strong>{{ $order->currency }} {{ number_format((float) $item->total_price, 2) }}</strong>
                            </div>
                        @endforeach
                        <div class="shop-summary__row"><span>Total</span><strong>{{ $order->currency }} {{ number_format((float) $order->total_amount, 2) }}</strong></div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 mt-4">
                @if($order->payment_status !== 'paid')
                    <a href="{{ route('shop.payment.mpesa', $order) }}" class="shop-btn-primary">Complete Payment</a>
                @endif
                <a href="{{ route('shop.index') }}" class="shop-btn-secondary">Back To Store</a>
            </div>
        </section>
    </div>
</main>
@endsection
