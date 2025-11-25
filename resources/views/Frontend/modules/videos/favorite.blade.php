@extends('Frontend.includes.layout')
@section('content')

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
<section>
	<h5 class="mb-3">Your Favorite Videos</h5>
	<div class="row">
		@foreach ($videos as $video)
		<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
			@include('Frontend.includes.components.cards.video-card')
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