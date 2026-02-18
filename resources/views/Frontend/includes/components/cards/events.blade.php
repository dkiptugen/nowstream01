@php
use Carbon\Carbon;

$startDate = Carbon::parse($event->start_time);
$endTime = Carbon::parse($event->end_time);

$tickets = $event->tickets ?? collect(); // fallback to empty collection

$hasPaidTickets = $tickets->count() > 0;
$freeStream = !$hasPaidTickets;

$ticket = $tickets->sortBy('price')->first();

$url = $freeStream
? route('event.show', ['eventId' => $event->uuid, 'slug' => $event->slug])
: route('event.show', ['eventId' => $event->uuid, 'slug' => $event->slug]);

$thumbnail = $event->event_image ? Storage::disk(config('filesystems.default'))->url($event->event_image) : asset('frontend-assets/images/default.png');
@endphp
 
    <div class="movie-item mb-60">
        <div class="movie-poster">
            <a href="{{ $url }}">
                                <!-- <img src="{{ $event->event_image }}" class="img-fluid" alt="{{ $event->event_name }}" loading="lazy"> -->

                <img src="{{ $thumbnail }}" class="img-fluid" alt="{{ $event->event_name }}" loading="lazy" style="
    aspect-ratio: 1.5 / 2.2;">
            </a>
<!-- 
            <h5 class="card-title mb-0 mt-3">
                <a href="{{ $url }}">
                   {{ $event->event_name }}
                </a>
            </h5> -->
        </div>

        <div class="movie-content mt-3">
            <div class="top">
                <small class=" mb-0">
                    {{ strtoupper($startDate->format('d M, Y')) }}
                </small>

                <span class="date">
                    <small class="card-text">
                        <i class="fas fa-clock"></i>
                        {{ $startDate->format('h:i A') }} - {{ $endTime->format('h:i A') }}
                    </small>
                </span>
            </div>

            <div class="bottom">
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