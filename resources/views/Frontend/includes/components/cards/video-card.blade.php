@php use App\Models\Channel; @endphp
<div class="card radius-5 h-100">
	<div class="image">
		<a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
			<img src="{{$video->thumbnail ?? asset('frontend-assets/images/default.png')}}"
				class="w-100 d-block w-100 aspect16" alt="...">
			<div class="play fs-40">
				<i class="fadeIn animated bx bx-play-circle"></i>
			</div>
		</a>
	</div>
	<div class="card-body pb-0">
		<a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
			<h6 class="mb-0">
				{{$video->title}}
			</h6>
		</a> 
		@php
			$channel = Channel::find($video->channel_id);
		@endphp

		<small class="text-muted mb-0 mt-1">
			{{ $channel ? $channel->name : 'Unknown' }}
		</small>
		<br>
		<small class="text-muted">
			<!-- <i class="lni lni-eye"></i> {{$video->id}} Views<span class="mx-1">.</span> -->
			<i
				class="lni lni-calendar"></i> {{ $video->created_at->diffForHumans() }}
		</small>
	</div>
</div>