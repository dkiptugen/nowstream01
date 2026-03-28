@extends('Frontend.includes.layout')

@section('header')
@include('Frontend.modules.shop.partials.theme')
@endsection

@section('content')
<main class="shop-page">
    <div class="container shop-shell">
        <div id="shop-cart-flash">
            @include('Frontend.modules.shop.partials.flash')
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <p class="shop-kicker mb-2">Shopping Cart</p>
                <h1 class="shop-title mb-0">Your merchandise cart</h1>
            </div>
            <a href="{{ route('shop.index') }}" class="shop-btn-secondary">Continue Shopping</a>
        </div>

        <div id="shop-cart-page">
            @if($summary['items']->isEmpty())
                @include('Frontend.modules.shop.partials.cart-empty')
            @else
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="shop-panel p-4" id="shop-cart-items">
                            @include('Frontend.modules.shop.partials.cart-items', ['summary' => $summary])
                        </div>
                    </div>

                    <div class="col-lg-4" id="shop-cart-summary">
                        @include('Frontend.modules.shop.partials.cart-summary', ['summary' => $summary])
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const page = document.getElementById('shop-cart-page');
        const flash = document.getElementById('shop-cart-flash');

        if (!page || !flash) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const headerCartCount = document.getElementById('header-cart-count');

        const showFlash = (message, type = 'success') => {
            flash.innerHTML = `<div class="shop-flash shop-flash--${type === 'error' ? 'error' : 'success'}">${message}</div>`;
        };

        const updateHeaderCartCount = (count) => {
            if (!headerCartCount) {
                return;
            }

            headerCartCount.textContent = count;
            headerCartCount.classList.toggle('d-none', Number(count) <= 0);
        };

        const bindCartActions = () => {
            page.querySelectorAll('.js-cart-update-form, .js-cart-remove-form').forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken || '',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            throw new Error(payload.message || 'Unable to update cart.');
                        }

                        if (payload.empty) {
                            page.innerHTML = payload.empty_html;
                        } else {
                            page.innerHTML = `
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        <div class="shop-panel p-4" id="shop-cart-items">${payload.items_html}</div>
                                    </div>
                                    <div class="col-lg-4" id="shop-cart-summary">${payload.summary_html}</div>
                                </div>
                            `;
                        }

                        updateHeaderCartCount(payload.count ?? 0);
                        showFlash(payload.message || 'Cart updated.');
                        bindCartActions();
                    } catch (error) {
                        showFlash(error.message || 'Unable to update cart.', 'error');
                    }
                });
            });
        };

        bindCartActions();
    });
</script>
@endsection
