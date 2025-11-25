@extends('Frontend.includes.layout')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<section>
			<div class="container">
				<div class="row">
					<div class="col-md-5 mx-auto">
						<!-- Success Alert -->
						@if (session('success'))
							<div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
								<div class="d-flex align-items-center">
									<div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
									<div class="ms-3">
										<h6 class="mb-0 text-white">Success Alert</h6>
										<div class="text-white">
											{{ session('success') }}
										</div>
									</div>
								</div>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif
						@if (session('error'))
									<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
										<div class="d-flex align-items-center">
											<div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
											<div class="ms-3">
												<h6 class="mb-0 text-white">Error Alert</h6>
												<div class="text-white">
													{{ session('error') }}
												</div>
											</div>
										</div>
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								</div> 
							</div>
						@endif
				<!-- Error Alert -->

			

				<p >
				Enter your Mobile Number. You will receive M-Pesa prompt to enter M-Pesa PIN and complete your payments.
					<br>
				<span id="message" class="w-100"></span>
				</p>
				<div class="card w-100 radius-10 mt-4">
					<div class="card-body">
						<form action="{{ route('mpesa_stk_pay') }}" method="post" id="m-pay">
							@csrf
							<label for="">Enter Your Mpesa Number</label>
							<div class="w-100 d-flex align-items-center mt-2">
								+254 <input type="text" placeholder="eg XXXXXXXXXXX" class="ms-2 form-control"
									name="msisdn">
							</div>

							<input type="hidden" name="identifier" value="{{ $subscription->identifier }}">
							<div class="text-end mt-3">
								<button type="submit" class="btn btn-danger w-100">
									Pay Now
								</button>
							</div>
						</form>

						<div class="mt-3">
							<p>
								You can also pay using Lipa na MPESA by using the following instructions:
							</p>
							<p class="mb-1">
								1. Go to the M-PESA menu
							</p>
							<p class="mb-1">
								2. Select Lipa na <b>M-PESA</b>
							</p>
							<p class="mb-1">

								3. Select the Paybill option
							</p>
							<p class="mb-1">

								4. Enter business number: <b>
									{{ config('custom.MPESA.MPESA_SHORTCODE') }}
								</b>

							</p>
							<p class="mb-1">
								5. Enter your account number: <b>
									{{$subscription->identifier}}
								</b>

							</p>
							<p class="mb-1">
								6. Enter the amount: <b>
									KES {{$subscription->balance}}
								</b>

							</p>
							<p class="mb-1">
								7. Enter PIN and press OK to send

							</p>
							<p class="mb-1">
								8. You will receive a confirmation SMS with your payment
							</p>
						</div>
					</div>

				</div>


			</div>



		</section>

		@endsection
		@section('header')
		@endsection
		@section('footer')
		<script>
			$(document).on('submit', '#m-pay', function (e) {
				e.preventDefault();
				var frm = $(this);
				var formData = new FormData(this);  // Use FormData to handle file uploads
				$.ajax({
					type: 'POST',
					url: frm.attr('action'),
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					data: formData,
					contentType: false,  // Prevent jQuery from setting the Content-Type
					processData: false,
					success: function (response) {
						$('#message').html('<h6 class="text-success">Please Check your phone for STK Push. Enter Your Pin To Proceed</h6>');
                        $('input[name=msisdn]').attr('disabled','true');
                        $('#m-pay button').attr('disabled','true');
					},
					error: function (xhr, status, errorThrown) {
						console.error('Error:', errorThrown);
					}
				});
			});
		</script>

	
		<script>
			// pusherScript.js
		Pusher.logToConsole = true;
			var pusher = new Pusher("cfc4e18a5372052374ee", {
				cluster: 'mt1', 
				encrypted: true,
				authEndpoint: '/pusher/auth',
			});

			var channel = pusher.subscribe('payment.{{$subscription->identifier}}');
			channel.bind('new_payment', function (data) {
				console.log(data);
				if (data.check) {
                    gtag('event', 'purchase', {
                        transaction_id: '{{$subscription->identifier}}',  // Unique transaction ID
                        affiliation: '{{ $subscription->event->name }}',
                        value:	'{{$subscription->balance}}',
                        currency: 'KES ',
                    });
					window.location.href = '{{ route('stream.show', [$subscription->event->streams->id, $subscription->event->streams->slug]) }}';
				} else {
					window.location.reload();
				}


			});
			channel.bind('failed_payment', function (data) {
                //console.log(data);
				$('#message').addClass('w-100').html('<h6 class="text-danger">'+data.error_message.message+'</h6>');
                $('input[name=msisdn]').removeAttr('disabled');
                $('#m-pay button').removeAttr('disabled');

			});
			channel.bind('pusher:subscription_count', function (members) {
				console.log('successfully subscribed!');
			});
			channel.bind('pusher:subscription_succeeded', function (members) {
				console.log('successfully subscribed!' + members);
			});

		</script>
		@endsection