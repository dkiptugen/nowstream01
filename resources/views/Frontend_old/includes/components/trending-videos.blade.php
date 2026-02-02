@if($videos->isNotEmpty())
	<section>
		<h5 class="mt-4 section-title mb-3">Latest Videos</h5>
		<div class="row">
			@foreach($videos as $video)
				<div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
					@include('Frontend.includes.components.cards.video-card')
				</div>
			@endforeach
		</div>
		<!--end row-->
	</section>
@endif