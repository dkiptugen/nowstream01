@extends('Frontend.includes.layout')

@section('header')
@include('Frontend.modules.shop.partials.theme')
@endsection

@section('content')
<main class="shop-page">
    <div class="container shop-shell">
        @include('Frontend.modules.shop.partials.flash')

        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="shop-form-card p-4 p-lg-5">
                    <p class="shop-kicker mb-2">M-Pesa Payment</p>
                    <h1 class="shop-title mb-3">Complete your payment</h1>
                    <p class="shop-subtitle mb-4">Use your M-Pesa number to receive an STK push and pay for order <strong>{{ $order->order_number }}</strong>.</p>

                    <div class="shop-meta mb-4">
                        <span>Order total: {{ $order->currency }} {{ number_format((float) $order->total_amount, 2) }}</span>
                        <span>Status: {{ ucfirst($order->payment_status) }}</span>
                    </div>

                    <form id="mpesa-form" class="d-grid gap-3">
                        @csrf
                        <input type="hidden" name="order_number" value="{{ $order->order_number }}">

                        <div>
                            <label class="shop-label" for="msisdn">M-Pesa Number</label>
                            <input type="text" class="shop-input" id="msisdn" name="msisdn" value="{{ $order->customer_phone }}" required>
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            <button type="submit" class="shop-btn-primary">Send STK Push</button>
                            <a href="{{ route('shop.success', $order) }}" class="shop-btn-secondary">Refresh Order</a>
                        </div>
                    </form>

                    <div id="mpesa-status" class="shop-flash shop-flash--success mt-4 d-none"></div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="shop-summary p-4">
                    <h2 class="h4 text-white mb-3">Order Items</h2>
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
    </div>
</main>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('mpesa-form');
        const statusBox = document.getElementById('mpesa-status');

        if (!form || !statusBox) {
            return;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            statusBox.classList.remove('d-none', 'shop-flash--error');
            statusBox.classList.add('shop-flash--success');
            statusBox.textContent = 'Sending STK push...';

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route('shop.payment.mpesa.stk') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.message || 'Unable to start payment.');
                }

                statusBox.textContent = payload.message || 'STK push sent.';
            } catch (error) {
                statusBox.classList.remove('shop-flash--success');
                statusBox.classList.add('shop-flash--error');
                statusBox.textContent = error.message || 'Unable to start payment.';
            }
        });

        Pusher.logToConsole = true;
        var pusher = new Pusher("cfc4e18a5372052374ee", {
            cluster: 'mt1',
            encrypted: true,
            authEndpoint: '/pusher/auth',
        });

        var channel = pusher.subscribe('payment.{{ $order->order_number }}');
        channel.bind('new_payment', function (data) {
            if (data.check) {
                window.location.href = '{{ route('shop.success', ['order' => $order->id]) }}';
            }
        });

        channel.bind('failed_payment', function (data) {
            statusBox.classList.remove('d-none', 'shop-flash--success');
            statusBox.classList.add('shop-flash--error');
            statusBox.textContent = data.error_message.message || 'Payment failed.';
        });
    });
</script>
@endsection
