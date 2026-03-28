@extends('Frontend.includes.layout')

@section('content')
<main>
    <section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
        <div class="container">
            <div class="section-title mb-4">
                <span class="sub-title">Your Basket</span>
                <h2 class="title">Cart</h2>
            </div>

            @if($summary['items']->isEmpty())
                <div class="alert alert-secondary">Your cart is empty. <a href="{{ route('shop.index') }}">Browse merchandise</a>.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped text-white">
                        <thead class="table-dark">
                            <tr>
                                <th>Item</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary['items'] as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item['product']->name }}</strong>
                                        @if($item['variant'])
                                            <div class="small text-light">{{ $item['variant']->name }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $item['product']->currency }} {{ number_format($item['unit_price'], 2) }}</td>
                                    <td style="min-width: 160px;">
                                        <form action="{{ route('cart.update', ['cartItem' => $item['id']]) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" min="0" value="{{ $item['quantity'] }}" class="form-control">
                                            <button type="submit" class="btn btn-sm btn-outline-light">Update</button>
                                        </form>
                                    </td>
                                    <td>{{ $item['product']->currency }} {{ number_format($item['line_total'], 2) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.destroy', ['cartItem' => $item['id']]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-lg-4">
                        <div class="card bg-dark text-white border-secondary">
                            <div class="card-body">
                                <h5 class="mb-3">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span>{{ $summary['currency'] }} {{ number_format($summary['subtotal'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span>{{ $summary['currency'] }} {{ number_format($summary['total'], 2) }}</span>
                                </div>
                                <a href="{{ route('shop.checkout') }}" class="btn btn-warning w-100 mt-4">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</main>
@endsection
