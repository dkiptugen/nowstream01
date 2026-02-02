@extends('Frontend.includes.layout')
@section('content')


        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
<section>
	<h5 class="mb-3">Popular Channels</h5>
	<div class="row g-2 g-md-3">
		@foreach($channels as $channel)
		<div class="col-6 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-md-4 mb-2">
			@include('Frontend.includes.components.cards.channels')
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