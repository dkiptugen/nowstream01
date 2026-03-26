@extends('Frontend.includes.layout')
@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-5 mx-auto">
                        @if (session('success'))
                            <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-white">Success Alert</h6>
                                        <div class="text-white">{{ session('success') }}</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <p>
                            Enter your mobile number. You will receive an M-Pesa prompt to complete payment for
                            <strong>{{ optional($order->items->first()?->product)->name }}</strong>.
                            <br>
                            <span id="message" class="w-100"></span>
                        </p>

                        <div class="card w-100 radius-10 mt-4">
                            <div class="card-body">
                                <form action="{{ route('event.payment.mpesa.stk') }}" method="post" id="m-pay">
                                    @csrf
                                    <label for="">Enter Your Mpesa Number</label>
                                    <div class="w-100 d-flex align-items-center mt-2">
                                        +254
                                        <input type="text" placeholder="eg XXXXXXXXX" class="ms-2 form-control" name="msisdn">
                                    </div>

                                    <input type="hidden" name="order_number" value="{{ $order->order_number }}">

                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-danger w-100">Pay Now</button>
                                    </div>
                                </form>

                                <div class="mt-3">
                                    <p>You can also pay using Lipa na M-PESA with the details below:</p>
                                    <p class="mb-1">1. Go to the M-PESA menu</p>
                                    <p class="mb-1">2. Select Lipa na <b>M-PESA</b></p>
                                    <p class="mb-1">3. Select the Paybill option</p>
                                    <p class="mb-1">4. Enter business number: <b>{{ config('custom.MPESA.MPESA_SHORTCODE') }}</b></p>
                                    <p class="mb-1">5. Enter account number: <b>{{ $order->order_number }}</b></p>
                                    <p class="mb-1">6. Enter the amount: <b>{{ $order->currency }} {{ $order->total_amount }}</b></p>
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

@section('header')
@endsection

@section('footer')
<script>
    $(document).on('submit', '#m-pay', function (e) {
        e.preventDefault();
        var frm = $(this);
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: frm.attr('action'),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#message').html('<h6 class="text-success">Please check your phone for the STK Push prompt.</h6>');
                $('input[name=msisdn]').attr('disabled', 'true');
                $('#m-pay button').attr('disabled', 'true');
            },
            error: function (xhr) {
                var message = xhr.responseJSON?.message || 'Failed to initiate payment.';
                $('#message').html('<h6 class="text-danger">' + message + '</h6>');
                $('input[name=msisdn]').removeAttr('disabled');
                $('#m-pay button').removeAttr('disabled');
            }
        });
    });
</script>

<script>
    Pusher.logToConsole = true;
    var pusher = new Pusher("cfc4e18a5372052374ee", {
        cluster: 'mt1',
        encrypted: true,
        authEndpoint: '/pusher/auth',
    });

    var channel = pusher.subscribe('payment.{{ $order->order_number }}');
    channel.bind('new_payment', function (data) {
        if (data.check) {
            window.location.href = '{{ route('event.success', ['eventId' => optional($order->items->first()?->product)->payable_id]) }}';
        } else {
            window.location.reload();
        }
    });

    channel.bind('failed_payment', function (data) {
        $('#message').addClass('w-100').html('<h6 class="text-danger">' + data.error_message.message + '</h6>');
        $('input[name=msisdn]').removeAttr('disabled');
        $('#m-pay button').removeAttr('disabled');
    });
</script>
@endsection
