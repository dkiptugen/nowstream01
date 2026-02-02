
				<section>
					 <h5 class="mt-4 section-title mb-3">Popular Channels</h5>
					<div class="d-flex scrolling">
						@foreach ($channels as $channel)
						<div class="col-6 col-lg-2 me-3">
                            @include('Frontend.includes.components.cards.channels')
						</div>
						@endforeach
					</div><!--end row-->
				</section>