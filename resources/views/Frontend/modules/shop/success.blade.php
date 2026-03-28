@extends('Frontend.includes.layout')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mx-auto mt-4">
                        <div class="card w-100 radius-10 mt-4" style="background: #f7f7f7;">
                            <div class="card-body text-center text-success">
                                <h1 class="text-center text-success mt-2">Order Received!</h1>
                                <img src="{{ asset('/success.png') }}" height="150" width="150" alt="">
                                <p class="mt-3 mb-0 text-dark">
                                    Your merchandise order <strong>{{ $order->order_number }}</strong> has been placed successfully.
                                </p>
                                <div class="text-start mt-4 text-dark">
                                    <p class="mb-2"><strong>Name:</strong> {{ $order->customer_name }}</p>
                                    <p class="mb-2"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                    @if($order->delivery_address)
                                        <p class="mb-2"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                                    @endif
                                </div>
                                <div class="text-center my-4">
                                    <a href="{{ route('shop.index') }}" class="btn btn-dark">
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
