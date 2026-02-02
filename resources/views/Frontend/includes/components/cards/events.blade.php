@php
use App\Models\EventRate;
use Carbon\Carbon;

$checkRate = EventRate::where([['event_id', $event->id], ['status', true]])->count();
$freeStream = $checkRate == 0;
$startDate = Carbon::parse($event->start_time);
$endTime = Carbon::parse($event->end_time);
$rate = $event->eventRates->sortBy('cost')->first();
@endphp

<div class="col-xl-3 col-lg-4 col-sm-6 grid-item grid-sizer">
    <div class="movie-item mb-60">
        <div class="movie-poster">
            <a href="{{ url($freeStream ? "/stream/free/{$event->id}/{$event->slug}" : "/event/{$event->id}/{$event->slug}") }}">
                <img src="{{ $event->event_image }}" class="img-fluid" alt="{{ $event->event_name }}">
            </a>
            <h5 class="card-title mb-0 mt-2"><b>{{ strtoupper($startDate->format('d M, Y')) }}</b></h5>
        </div>
        <div class="movie-content mt-3">
            <div class="top">
                <h5 class="title">
                    <a href="{{ url($freeStream ? "/stream/free/{$event->id}/{$event->slug}" : "/stream/{$event->id}/{$event->slug}") }}">
                        {{ $event->event_name }}
                    </a>
                </h5>
                <span class="date">
                    <small class="card-text">
                        <i class="fas fa-clock"></i> {{ $startDate->format('h:i A') }} - {{ $endTime->format('h:i A') }}
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
                            <i class="fas fa-map-marker-alt"></i> Venue: {{ $event->venue }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
