@php
use Carbon\Carbon;

// Event & Stream data
$event = $stream->event;
$channel = $stream->channel;

// Stream thumbnail fallback
$thumbnail = $stream->thumbnail_url
    ? Storage::disk(config('filesystems.default'))->url($stream->thumbnail_url)
    : asset('frontend-assets/images/default.png');

// Event start/end
$startDate = $event ? Carbon::parse($event->start_time) : null;
$endTime = $event ? Carbon::parse($event->end_time) : null;
$now = Carbon::now();

// Tickets
$tickets = $event->tickets ?? collect();
$ticket = $tickets->sortBy('price')->first();
$hasPaidTickets = $tickets->count() > 0;
$freeStream = !$hasPaidTickets;

// Stream URL
$url = $freeStream
    ? route('free.show', ['slug' => $stream->slug])
    : route('stream.show', ['slug' => $stream->slug]);

// Event status & time text
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

<div class="movie-item mb-60">
    <div class="movie-poster">
        <a href="{{ $url }}">
            <img src="{{ $thumbnail }}" class="img-fluid" alt="{{ $stream->title }}" loading="lazy" style="aspect-ratio: 1.5 / 2;">
        </a>

        {{-- Status Badge --}}
        <!-- @if($status === 'Watch')
            <div class="time d-flex align-items-center">
                Watch <i class="lni lni-play"></i>
            </div>
        @elseif($status === 'Live')
            <div class="time">Live</div>
        @else
            <div class="time">Upcoming</div>
        @endif -->
    </div>

    <div class="movie-content mt-3">
        <div class="top d-flex justify-content-between align-items-center mb-2">
            @if($startDate)
                <small>{{ strtoupper($startDate->format('d M, Y')) }}</small>
                <span class="date">
                    <small class="card-text">
                        <i class="fas fa-clock"></i>
                        {{ $startDate->format('h:i A') }} - {{ $endTime->format('h:i A') }}
                    </small>
                </span>
            @endif
        </div>

        <div class="bottom">
            <ul class="list-unstyled mb-1">
                <li>
                    <h6 class="quality">
                        <i class="bx bx-money"></i>
                        {{ $ticket ? "From KES {$ticket->price}" : 'Free' }}
                    </h6>
                </li>
                <li>
                    <span class="duration">
                        <i class="fas fa-map-marker-alt"></i>
                        Venue: {{ $stream->venue ?? 'Unknown' }}
                    </span>
                </li>
            </ul>

            <!-- <small class="text-muted mt-1 d-block">
                {{ $channel->name ?? 'Unknown' }}
            </small> -->

            @if($stream)
                <small class="text-muted d-block">
                    <i class="lni lni-calendar"></i> {{ $timeText }}
                </small>
            @endif
        </div>
    </div>
</div>
