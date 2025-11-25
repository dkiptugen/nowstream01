@if($streams->isNotEmpty())
	<section>
	<h5 class="section-title mb-3">Trending Streams</h5>
	<div class="row">
		@foreach($streams as $stream)
			<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-3">
				@include('Frontend.includes.components.cards.stream-card')
			</div>
		@endforeach
	</div>
	<!--end row-->
</section>
@endif