@extends('Frontend.includes.layout')
@section('content')

<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<section class="event">

			<h5 class="mb-3 section-title">
				<!-- Error Alert -->
				@if (session('success'))
					You dont Have an active subscription. Pick an Event Below <br>
				@else
					All Events
				@endif 
			</h5>
			<div class="row ">
				@foreach($events as $event) 
					<div class="col-12 col-lg-3 col-md-12 col-xl-3 col-lg-3 col-xxl-3 mb-4">
						@include('Frontend.includes.components.cards.events')
					</div>
				@endforeach
			</div>
			<!--end row-->
		</section> 
		@endsection
		@section('header')
		@endsection
		@section('footer')
		@endsection