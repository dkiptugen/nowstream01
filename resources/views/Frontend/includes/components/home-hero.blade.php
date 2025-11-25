<section>
	<div class="mb-4">
		<div class="card">
			<div class="row hero">
				<div class="col-md-5 align-self-center order-2 order-md-0 p-3">
					<div class="text-center p-md-4">
						<h1><b>Welcome to Baze Live</b></h1>
						<h5>STREAM EVERY SHOW, EVERY CONCERT, EVERY GIG IN AFRICA.</h5>
						<div class="form-group w-75 mx-auto">
							@if (session('error'))
								<div class="alert alert-danger mt-4">
									{{ session('error') }}
								</div>
							@endif 
							<form action="{{ route('stream.find') }}" method="POST" class="form-inline">
							    @csrf
							    <input type="text" name="stream_token" class="form-control mt-4" placeholder="Enter Stream Token eg EWEEESU9">
							    <button type="submit" class="btn btn-danger mt-4">Submit To Watch</button>
							</form>
							<div class="d-flex justify-content-end flex-column">
								<p class="mt-3 w-100 text-left">Dont have a stream Token? <a
										href="{{url('/events')}}">Purchase</a></p>
							</div>
						</div>
					</div>
					<div class="p-md-4 w-75">
					</div>

				</div>
				<div class="col-md-7">
					<div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
						<div class="carousel-inner">
							@foreach($events as $index => $event) 
								<div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
							        <a href="{{ url("/event/{$event->id}/{$event->slug}") }}" class="w-100">
							            <img src="{{$event->event_image}}" class="w-100 d-block h400 w-100" alt="...">
 							        </a>
							    </div>
							@endforeach
 
						</div>
						<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
							data-bs-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</button>
					</div>

				</div>
			</div>
		</div>
		</div>
</section>