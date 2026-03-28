@extends('Frontend.includes.layout')

@section('content')
<main>
    <section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card bg-dark border-secondary text-white">
                        <div class="card-body">
                            <h3 class="mb-4">Checkout</h3>
                            <form action="{{ route('shop.checkout.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Delivery Address</label>
                                    <textarea name="delivery_address" class="form-control" rows="4">{{ old('delivery_address') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                </div>
                                <input type="hidden" name="payment_method_id" value="1">
                                <button type="submit" class="btn btn-warning">Continue to Payment</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card bg-dark border-secondary text-white">
                        <div class="card-body">
                            <h4 class="mb-3">Order Summary</h4>
                            @foreach($summary['items'] as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        {{ $item['product']->name }}
                                        @if($item['variant'])
                                            <div class="small text-light">{{ $item['variant']->name }}</div>
                                        @endif
                                    </div>
                                    <div>{{ $summary['currency'] }} {{ number_format($item['line_total'], 2) }}</div>
                                </div>
                            @endforeach
                            <hr class="border-secondary">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span>{{ $summary['currency'] }} {{ number_format($summary['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
