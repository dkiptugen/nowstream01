@php use Carbon\Carbon; @endphp
@php use App\Models\ContentRate;

            $checkRate = ContentRate::where([['event_id', $event->id], ['status', true]])->count();
            $freeStream = $checkRate == 0;
@endphp
@if($freeStream)
    <div class="card mb-3 h-100">
        <div class="row g-0">
            <a href="{{ url("/stream/free/{$event->id}/{$event->slug}") }}">
                <img src="{{ $event->event_image }}" class="img-fluid aspect16 w-100"
                     alt="{{ $event->event_name }}">
            </a>
            <div class="card-body pb-0">
                <div class="date btn btn-danger">
                    <h6 class="card-title mb-1">{{ Carbon::parse($event->start_time)->format('d') }}
                        <sup>{{ strtoupper(Carbon::parse($event->start_time)->format('S')) }}</sup></h6>
                    <h5 class="card-title mb-0"><b>{{ strtoupper(Carbon::parse($event->start_time)->format('M')) }}</b>
                    </h5>
                </div>
                <h4 class="card-title mb-3">
                    <a href="{{ url("/stream/free/{$event->id}/{$event->slug}") }}">
                        {{ $event->event_name }}
                    </a>
                </h4>
                <small class="card-text">
                    <i class="bx bx-time"></i>
                    Time: {{ Carbon::parse($event->start_time)->format('h:i A') }} -
                    To {{ Carbon::parse($event->end_time)->format('h:i A') }}
                </small>

                <br>
                <small class="card-text"><i class='bx bx-current-location'></i> Venue: {{ $event->venue }}</small>
                <div class="d-flex justify-content-between mt-3">
                    @php
                        $rate = ContentRate::where('event_id', $event->id)->orderBy('cost', 'asc')->first();
                    @endphp

                    @if($rate)
                        <h6 class="me-2"><i class="bx bx-money"></i> From KES {{ $rate->cost }}</h6>
                    @else
                        <h6 class="me-2"><i class="bx bx-money"></i> Free</h6>
                    @endif


                    <a class="text-danger" href="{{ url("/stream/free/{$event->id}/{$event->slug}") }}">Watch <i
                                class='lni lni-play'></i></a>

                </div>
            </div>
        </div>
    </div>

@else
    <div class="card mb-3 h-100">
        <div class="row g-0">
            <a href="{{ url("/event/{$event->id}/{$event->slug}") }}">
                <img src="{{ $event->event_image }}" class="img-fluid aspect16 w-100"
                     alt="{{ $event->event_name }}">
            </a>
            <div class="card-body pb-0">
                <div class="date btn btn-danger">
                    <h6 class="card-title mb-1">{{ Carbon::parse($event->start_time)->format('d') }}
                        <sup>{{ strtoupper(Carbon::parse($event->start_time)->format('S')) }}</sup></h6>
                    <h5 class="card-title mb-0"><b>{{ strtoupper(Carbon::parse($event->start_time)->format('M')) }}</b>
                    </h5>
                </div>
                <h4 class="card-title mb-3">
                    <a href="{{ url("/event/{$event->id}/{$event->slug}") }}">
                        {{ $event->event_name }}
                    </a>
                </h4>
                <small class="card-text">
                    <i class="bx bx-time"></i>
                    Time: {{ Carbon::parse($event->start_time)->format('h:i A') }} -
                    To {{ Carbon::parse($event->end_time)->format('h:i A') }}
                </small>

                <br>
                <small class="card-text"><i class='bx bx-current-location'></i> Venue: {{ $event->venue }}</small>
                <div class="d-flex justify-content-between mt-3">
                    @php
                        $rate = ContentRate::where('event_id', $event->id)->orderBy('cost', 'asc')->first();
                    @endphp

                    @if($rate)
                        <h6 class="me-2"><i class="bx bx-money"></i> From KES {{ $rate->cost }}</h6>
                    @else
                        <h6 class="me-2"><i class="bx bx-money"></i> Free</h6>
                    @endif

                    <a class="text-danger" href="{{ url("/event/{$event->id}/{$event->slug}") }}">Buy <i
                                class='bx bx-link'></i></a>

                </div>
            </div>
        </div>
    </div>

@endif
