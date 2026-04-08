@php
use Carbon\Carbon;

$startDate = Carbon::parse($event->start_time);
$endTime = Carbon::parse($event->end_time);

$tickets = $event->tickets ?? collect(); // fallback to empty collection

$hasPaidTickets = $tickets->count() > 0;
$freeStream = !$hasPaidTickets;

$ticket = $tickets->sortBy('price')->first();

$url = $freeStream
? route('event.show',  $event->slug)
: route('event.show',  $event->slug);

$thumbnail = $event->event_image ? Storage::disk(config('filesystems.default'))->url($event->event_image) : asset('frontend-assets/images/default.png');
@endphp

<div class="movie-item mb-4 mb-lg-5 nowstream-event-card">
    <div class="movie-poster">
        <a href="{{ $url }}">
            <img src="{{ $thumbnail }}" class="img-fluid nowstream-event-card__image" alt="{{ $event->event_name }}" loading="lazy" style="aspect-ratio: 1.5 / 2;">
        </a>
    </div>

    <div class="movie-content mt-3 nowstream-event-card__body">
        <div class="top">
            <small class="mb-0 nowstream-event-card__date">
                {{ strtoupper($startDate->format('d M, Y')) }}
            </small>

            <h3 class="nowstream-event-card__title">
                <a href="{{ $url }}">{{ $event->event_name }}</a>
            </h3>

            <span class="date nowstream-event-card__time">
                <small class="card-text">
                    <i class="fas fa-clock"></i>
                    {{ $startDate->format('h:i A') }} - {{ $endTime->format('h:i A') }}
                </small>
            </span>
        </div>

        <div class="bottom nowstream-event-card__meta" style="position: relative;">
            <ul>
                <li>
                    <h6 class="quality">
                        <i class="bx bx-money"></i>
                        {{ $ticket ? "From KES {$ticket->price}" : 'Free' }}
                    </h6>
                </li>

                <li>
                    <span class="duration">
                        <i class="fas fa-map-marker-alt"></i>
                        Venue: {{ $event->venue }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
