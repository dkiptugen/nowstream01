@php
    use Carbon\Carbon;

    $isFree = $stream->rates->isEmpty();
    $url = $isFree
        ? url("/stream/free/{$stream->slug}")
        : url("/stream/{$stream->slug}");

    $event = $stream->event;
    $channel = $stream->channel;
    $now = Carbon::now();

    $status = 'Upcoming';
    $timeText = '';

    if ($event) {
        if ($event->end_time <= $now) {
            $status = 'Watch';
            $timeText = 'Ended';
        } elseif ($event->start_time <= $now && $event->end_time > $now) {
            $status = 'Live';
            $timeText = 'Started ' . $event->start_time->diffForHumans();
        } else {
            $status = 'Upcoming';
            $timeText = 'Starts in ' . $event->start_time->diffForHumans();
        }
    }
@endphp

<div class="card radius-5 h-100">
    <div class="image">

        <a href="{{ $url }}">
            <img src="{{ $stream->thumbnail_url }}"
                 class="w-100 d-block aspect16"
                 alt="{{ $stream->title }}"
                 loading="lazy">
        </a>

        <a href="{{ $url }}">
            <div class="play fs-40">
                <i class="fadeIn animated bx bx-play-circle"></i>
            </div>
        </a>

        {{-- Status Badge --}}
        @if($status === 'Watch')
            <div class="time d-flex align-items-center">
                Watch <i class="lni lni-play"></i>
            </div>
        @elseif($status === 'Live')
            <div class="time">Live</div>
        @else
            <div class="time">Upcoming</div>
        @endif

    </div>

    <div class="card-body pb-0">
        <a href="{{ $url }}">
            <h6 class="mb-0">{{ $stream->title }}</h6>
        </a>

        <small class="text-muted mt-1 d-block">
            {{ $channel->name ?? 'Unknown' }}
        </small>

        @if($event)
            <small class="text-muted">
                <i class="lni lni-calendar"></i>
                {{ $timeText }}
            </small>
        @endif
    </div>
</div>
