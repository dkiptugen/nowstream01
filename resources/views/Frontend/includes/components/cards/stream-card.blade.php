<div class="card radius-5 h-100">
    <div class="image">
        @php
            use App\Models\ContentRate;
            $checkRate = ContentRate::where('content_id', $stream->id)->count();
            $freeStream = $checkRate == 0;
        @endphp

        @if($freeStream)
            <a href="{{ url("/stream/free/{$stream->id}/{$stream->slug}") }}">
                <img src="{{$stream->thumbnail_url}}" class="w-100 d-block w-100 aspect16" alt="{{ $stream->title }}">
            </a>
            <a href="{{ url("/stream/free/{$stream->id}/{$stream->slug}") }}">
                <div class="play fs-40">
                    <i class="fadeIn animated bx bx-play-circle"></i>
                </div>
            </a>
        @else
            <a href="{{ url("/stream/{$stream->id}/{$stream->slug}") }}">
                <img src="{{$stream->thumbnail_url}}" class="w-100 d-block w-100 aspect16" alt="{{ $stream->title }}">
            </a>
            <a href="{{ url("/stream/{$stream->id}/{$stream->slug}") }}">
                <div class="play fs-40">
                    <i class="fadeIn animated bx bx-play-circle"></i>
                </div>
            </a>
        @endif
        @php
            $event = \App\Models\Event::find($stream->event_id);
            use Carbon\Carbon;
            $current_time = Carbon::now();
        @endphp

        @if($event->end_time <= $current_time)
            <div class="time align-items-center d-flex">Watch <i class="lni lni-play"></i></div>
        @elseif($event->start_time <= $current_time && $event->end_time > $current_time)
            <div class="time">Live</div>
        @else
            <div class="time">Upcoming</div>
        @endif
    </div>
    <div class="card-body pb-0">
        @if($freeStream)
            <a href="{{ url("/stream/free/{$stream->id}/{$stream->slug}") }}">
                <h6 class="mb-0">{{$stream->title}}</h6>
            </a>
        @else
            <a href="{{ url("/stream/{$stream->id}/{$stream->slug}") }}">
                <h6 class="mb-0">{{$stream->title}}</h6>
            </a>
        @endif
        @php
            $channel = \App\Models\Channel::find($stream->channel_id);
        @endphp

        <small class="text-muted mb-0 mt-1">
            {{ $channel ? $channel->name : 'Unknown' }}
        </small>
        <br><small class="text-muted">
            <i class="lni lni-calendar"></i>

            @if($event->start_time > $current_time)
                <small class="text-muted">Starts in {{ $event->start_time->diffForHumans() }}</small>
            @elseif($event->end_time > $current_time)
                <small class="text-muted">Started {{ $event->start_time->diffForHumans() }}</small>
            @else
                <small class="text-muted">Ended</small>
            @endif
        </small>
    </div>
</div>
