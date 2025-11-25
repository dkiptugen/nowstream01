@extends('Frontend.includes.layout')

@section('content') 
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <h4 class="section-title">Watched Videos</h4> 
        @if($watchHistory->isEmpty())
    <p>You haven't watched any videos yet.</p>
@else
    <div class="row">
        @foreach ($watchHistory as $history)
            @if ($history->watchable)
                <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-4">
                    <div class="card radius-5 h-100">
                        <div class="image">
                            <img src="{{ $history->watchable->thumbnail }}" class="w-100 d-block aspect16"
                                alt="{{ $history->watchable->title }}">
                            <a href="{{ url("/video/{$history->watchable->id}/{$history->watchable->slug}") }}">
                                <div class="play fs-40">
                                    <i class="fadeIn animated bx bx-play-circle"></i>
                                </div>
                            </a>
                            <div class="time" id="duration-{{ $history->watchable->id }}"></div>
                        </div>
                        @php
                            $watchPercentage = $history->watchable->duration > 0 ? ($history->watch_duration / $history->watchable->duration) * 100 : 0;
                        @endphp
                        <div class="progress mb-0 rounded-0" style="height:4px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $watchPercentage }}%"
                                aria-valuenow="{{ $watchPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body pb-0">
                            <a href="{{ url("/video/{$history->watchable->id}/{$history->watchable->slug}") }}">
                                <h6 class="mb-0">{{ $history->watchable->title }}</h6>
                            </a>
                            <small class="text-muted">
                                <i class="lni lni-calendar"></i>
                                {{ \Carbon\Carbon::parse($history->watched_at)->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $watchHistory->links() }}
    </div>
@endif

        <h4 class="section-title">Watched streams</h4> 
        @if($streamWatchHistory->isEmpty())
            <p>You haven't watched any streams yet.</p>
        @else
            <div class="row">
                @foreach ($streamWatchHistory as $history)
                    @if ($history->watchable)
                        <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-4">
                            <div class="card radius-5 h-100">
                                <div class="image">
                                    <img src="{{ $history->watchable->thumbnail_url }}" class="w-100 d-block aspect16"
                                        alt="{{ $history->watchable->title }}">
                                    <a href="{{ url("/stream/{$history->watchable->id}/{$history->watchable->slug}") }}">
                                        <div class="play fs-40">
                                            <i class="fadeIn animated bx bx-play-circle"></i>
                                        </div>
                                    </a>
                                    <div class="time">3:50</div>
                                </div>
                                @php
                                    $watchPercentage = $history->watchable->duration > 0 ? ($history->watch_duration / $history->watchable->duration) * 100 : 0;
                                @endphp
                                <div class="progress mb-0 rounded-0" style="height:4px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $watchPercentage }}%"
                                        aria-valuenow="{{ $watchPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="card-body pb-0">
                                    <a href="{{ url("/stream/{$history->watchable->id}/{$history->watchable->slug}") }}">
                                        <h6 class="mb-0">{{ $history->watchable->title }}</h6>
                                    </a>
                                    <small class="text-muted">
                                        <i class="lni lni-calendar"></i>
                                        {{ \Carbon\Carbon::parse($history->watched_at)->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $streamWatchHistory->links() }}
            </div>
        @endif 
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($watchHistory as $history)
            @if ($history->watchable)
                const video = document.createElement('video');
                video.src = '{{ $history->watchable->video_path }}';
                video.addEventListener('loadedmetadata', function() {
                    const duration = video.duration;
                    const durationElement = document.getElementById('duration-{{ $history->watchable->id }}');
                    if (durationElement) {
                        durationElement.textContent = formatDuration(duration);
                    }
                });
            @endif
        @endforeach

        function formatDuration(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
    });
</script>
