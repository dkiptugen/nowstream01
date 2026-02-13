@php use App\Models\Channel; @endphp
@php
    use App\Models\ContentRate;
    use Carbon\Carbon;

    $checkRate = ContentRate::where([['event_id', $event->id], ['status', true]])->count();
    $freeStream = $checkRate == 0;
    $startDate = Carbon::parse($event->start_time);
    $endTime = Carbon::parse($event->end_time);
    $rate = $event->eventRates->sortBy('cost')->first();
@endphp
<div class="col-xl-4 col-lg-4 col-sm-6 grid-item grid-sizer">
    <div class="movie-item mb-60 shadow-sm">
        <div class="movie-poster mb-0">
            <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                <img src="{{$video->thumbnail ?? asset('frontend-assets/images/default.png')}}"
                     class="w-100 d-block w-100" alt="..." loading="lazy">
                <div class="play fs-40">
                    <i class="fadeIn animated bx bx-play-circle"></i>
                </div>
            </a>
        </div>
        <div class="movie-content p-2">
            <div class="top">
                @php
                    $channel = Channel::find($video->channel_id);
                @endphp
                <h5 class="title mt-0">
                    <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                        {{ucfirst($video->title)}}
                    </a>
                </h5>
            </div>
            <div class="bottom">
                <!-- Display number of views -->

                <ul>
                    <li><span class="quality">hd</span></li>
                    <li>
                                        <span class="channel"><i class="far fa-user"></i>
                                            {{ $channel ? $channel->name : 'Unknown' }}</span>
                        <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                        <span class="views ml-2">
                                            <i class="fas fa-eye"></i> {{ $video->views ?? 0 }} views
                                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
