@php
    use Carbon\Carbon;

    $startDate = Carbon::parse($event->start_time);
    $endTime   = Carbon::parse($event->end_time);

    $hasPaidRates = $event->eventRates->where('status', true)->count() > 0;
    $freeStream   = !$hasPaidRates;

    $rate = $event->eventRates
        ->where('status', true)
        ->sortBy('cost')
        ->first();

    $url = $freeStream
        ? route('free.show', ['id' => $event->id, 'slug' => $event->slug])
        : route('event.show', ['eventId' => $event->id, 'slug' => $event->slug]);
@endphp

<div class="col-xl-3 col-lg-4 col-sm-6 grid-item grid-sizer">
    <div class="movie-item mb-60">
        <div class="movie-poster">
            <a href="{{ $url }}">
                <img src="{{ $event->event_image }}" class="img-fluid" alt="{{ $event->event_name }}">
            </a>

            <h5 class="card-title mb-0 mt-2">
                <b>{{ strtoupper($startDate->format('d M, Y')) }}</b>
            </h5>
        </div>

        <div class="movie-content mt-3">
            <div class="top">
                <h5 class="title">
                    <a href="{{ $url }}">
                        {{ $event->event_name }}
                    </a>
                </h5>

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
                            {{ $rate ? "From KES {$rate->cost}" : 'Free' }}
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
</div>
