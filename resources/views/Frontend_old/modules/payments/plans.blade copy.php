@extends('Frontend.includes.layout')
@section('content') 
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-6 mx-auto text-center">
				<h1>
					KES {{ $event->rates->reserved_currency_cost }}
				</h1>
				<h5>
					Unlimited access to
					{{ $event->event_name }}
				</h5>
				<div class="card w-100 radius-10 mt-4">
                    <form action="{{route ('mpesa')}}" method="post">
                          @csrf
                        <div class="card-body">
                            <input type="hidden" name="event_id" value="{{ $event->event_id }}">
                            <input type="hidden" name="channel_id" value="{{ $event->channel_id }}">
                            <input type="hidden" name="cost" value="{{ $event->cost }}">
                            <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                            <div class="card radius-10 border-primary border shadow-none">
                                <label class="card-body" for="mpesa">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/M-PESA_LOGO-01.svg/2560px-M-PESA_LOGO-01.svg.png" height="70" alt="">
                                        </div>
                                        <div class="ms-auto">
                                            <input class="widgets-icons-2 bg-success text-white" checked type="radio" name="payment_method" id="mpesa">
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="card radius-10 border shadow-none">
                                <label class="card-body bg-light radius-10" for="creditcard">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <img src="https://wallpapers.com/images/hd/credit-card-logos-comparison-wrp70ko4rhiamk8x.png" height="40" alt="">
                                        </div>
                                        <div class="ms-auto">
                                            <input class="widgets-icons-2 bg-gradient-ibiza text-white" type="radio" name="payment_method" id="creditcard">
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-danger w-100">
                                    Proceed to Pay
                                </button>
                            </div>
                        </div>
                     </form>
                </div>

			</div>
		</div>
	</div>
</section>

@endsection
@section('header')
@endsection
@section('footer')

@endsection