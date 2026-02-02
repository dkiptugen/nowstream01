@php use Carbon\Carbon; @endphp
@extends('Frontend.includes.layout')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<section>
			<div class="mb-4 container p-0">
				<div class="card">
					<div class="row g-2">
						<div class="col-md-5">
							<img src="{{$event->event_image}}" class="img-fluid w-100 h330" alt="...">
						</div>
						<div class="col-md-7">
							<div class="card-body pb-0">
								<div class="date btn btn-danger float-end d-none d-md-block">
									<h6 class="card-title mb-1">
										{{ Carbon::parse($event->start_time)->format('d') }}
										<sup>
											{{ strtoupper(Carbon::parse($event->start_time)->format('S')) }}
										</sup>
									</h6>
									<h5 class="card-title mb-0"><b>{{ strtoupper(Carbon::parse($event->start_time)->format('M')) }}</b></h5>
								</div> 
								<h1 class="card-title mb-3">
									{{$event->event_name}}
								</h1>
								<div class="text-container d-none d-md-block">
									<input id="ch" type="checkbox">
									<label for="ch"></label>
									<div class="less-text">
										{!!$event->description !!}
									</div>
								</div>

								<small class="text-muted">
									<i class="bx bx-time"></i>
									Event Starts at:
									{{ Carbon::parse($event->start_time)->format('h:i A') }}
								</small>

								<br>
								<!-- <small class="text-muted"><i class='bx bx-current-location'></i> Venue:
									{{$event->venue}}
								</small> -->
								<div class="payment d-none">
									<div class="py-2" style="color: rgba(0,0,0,0); font-size: 1px">re</div>
									@foreach($rates as $rate) 
									<div class="custom-control custom-radio first">
										<input type="radio" name="rate" id="{{ $rate->name }}" value="{{ $rate->id }}"
											class="custom-control-input custom-radio-size" @if($loop->first) checked
											@endif onclick="showBuyLink('{{ $rate->id }}');">
										<label class="custom-control-label mb-1" for="{{ $rate->name }}">
											{{ $rate->name }} <br> @KES<b class="upp">
												{{ $rate->cost }}
											</b>
											
{{-- 											<small class="text-muted mb-2">Sale Closes on --}}
{{-- 												{{ Carbon::parse($event->date_to)->format('M d, Y') }} at --}}
{{-- 												{{ Carbon::parse($event->date_to)->format('h:i A') }} --}}
{{-- 											</small> --}}
										</label>
									</div>
									<br>
									<div class="w-100 buy-link" id="buy-link-{{ $rate->id }}"
										style="display: @if($loop->first) block @else none @endif;">
										<a class="btn btn-success rounded-0 my-3 btm-100 px-5"
											href="{{ route('event.pay', ['eventId' => $event->id, 'rate_id' => $rate->id]) }}">
											Buy Link <i class='bx bx-link'></i>
										</a>
									</div>
									@endforeach

									<script>
										function showBuyLink(selectedId) {
											// Hide all buy links
											const buyLinks = document.querySelectorAll('.buy-link');
											buyLinks.forEach(link => link.style.display = 'none');

											// Show the selected buy link
											const selectedLink = document.getElementById('buy-link-' + selectedId);
											if (selectedLink) {
												selectedLink.style.display = 'block';
											}
										}
									</script>
</div>

<div class="payment pt-2 p-0 mb-3">
					<div class="card-body p-0 row px-md-3">
						<table class="table mb-0 table-striped">
							<thead class="table-dark">
								<tr>
									<th scope="col">Ticket</th>
									<th scope="col">Sub Total</th>
									<th class="text-end pe-md-3" scope="col">Buy</th>
								</tr>
							</thead>
							<tbody>
								@foreach($rates as $rate)
									<tr>
										<td class="align-content-center" scope="row">
											{{$rate->name}} 
										</td>
										<td class="align-content-center">
											@if($country == 'KE')
												KES 	{{$rate->cost}} 
											@else
												{{config('custom.BILLING.RESERVED_CURRENCY')." ".$rate->reserved_currency_cost}}
											@endif
										</td>
										<td class="align-content-center text-end">
											<a class="btn btn-sm btn-success ps-2"
												href="{{ route("event.pay", ['eventId' => $event->id, 'rate_id' => $rate->id]) }}">Buy
												Link <i class='bx bx-link'></i></a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>

						<h6 class="mt-3 px-0">To get <b>5GB</b> Bundles with Somali Nite Stream Access, Dial  <b>*544*46#Ok</b></h6> 
					</div> 
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="card-body p-0 p-md-3 mt-3 mt-md-0">
						<table class="table mb-0 table-striped">
							<thead class="table-dark">
								<tr>
									<th scope="col">Ticket</th>
									<th scope="col">Sub Total</th>
									<th scope="col">Buy</th>
								</tr>
							</thead>
							<tbody>
								@foreach($rates as $rate)
									<tr>
										<td class="align-content-center" scope="row">
											{{$rate->name}}
											<br>
											<small>Closes on
												{{$rate->date_to}}
											</small>
										</td>
										<td class="align-content-center">
										@if($country == 'KE')
										 KSH 	{{$rate->cost}} /=
										@else
											{{config('custom.BILLING.RESERVED_CURRENCY')." ".$rate->reserved_currency_cost}}/=
										@endif
										</td>
										<td class="align-content-center">
											<a class="text-danger"
												href="{{ route("event.pay", ['eventId' => $event->id, 'rate_id' => $rate->id]) }}">Buy
												Link <i class='bx bx-link'></i></a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div> -->

				</div>
			</div>
		</section>
		@if($events->isNotEmpty())
			<section class="event">
				<div class="container p-0 mt-4">
					<h5 class="my-3">Other Events</h5>
					<div class="row ">
						@foreach($events as $event)
							<div class="col-12 col-lg-4 col-md-12 col-xl-4 col-lg-4 mb-4">
								@include('Frontend.includes.components.cards.events')
							</div>
						@endforeach
					</div>
				</div>
				<!--end row-->
			</section>
		@endif 
		@endsection
		@section('header')
		<style>
			p span {
				color: inherit !important;
			}

			.less-text {
				height: 50px;
				overflow: hidden;
			}

			.text-container {
				position: relative;
				margin-bottom: 30px;
			}

			.text-container label {
				position: absolute;
				top: 100%;
			}


			.text-container input {
				display: none;
			}


			.text-container label:after {
				content: " ";
			}  .less-text font{
    color: inherit;
}

			.text-container input:checked+label:after {
				content: "Show Less";
			}


			.text-container input:checked~div {
				height: 100%;
			}

			.buy-link {
				display: block;
				position: absolute;
				bottom: 0;
			}
			.payment{
				margin-bottom: 60px;
			}
		</style>

		@endsection
		@section('footer')
		@endsection