<div class="shop-summary p-4">
    <h2 class="h4 text-white mb-3">Order Summary</h2>
    <div class="shop-summary__row"><span>Subtotal</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['subtotal'], 2) }}</strong></div>
    <div class="shop-summary__row"><span>Tax</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['tax'], 2) }}</strong></div>
    <div class="shop-summary__row"><span>Fees</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['fees'], 2) }}</strong></div>
    <div class="shop-summary__row"><span>Total</span><strong>{{ $summary['currency'] }} {{ number_format((float) $summary['total'], 2) }}</strong></div>
    <a href="{{ route('shop.checkout') }}" class="shop-btn-primary w-100 mt-3">Proceed To Checkout</a>
</div>
