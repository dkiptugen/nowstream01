@extends('Frontend.includes.layout')

@section('content') 
 <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Our<span>Podcasts</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Podcasts</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
 
        </div>
    </section> <!-- breadcrumb-area-end -->
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container">
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest Podcasts</span>
                    <h2 class="title">Latest Podcasts</h2>
                </div>
            </div>
            <div class="row tr-movie-active">

                @foreach($watchPodcasts as $podcast)
                @include('Frontend.includes.components.cards.podcast-card')
                @endforeach
            </div>
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Latest Podcasts</span>
                    <h2 class="title">Latest Podcasts</h2>
                </div>
            </div> 
 
            <div class="row tr-movie-active">

                @foreach($watchradios as $item)
                @include('Frontend.includes.components.cards.slider-card')
                @endforeach
            </div>

        </div>
    </section>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <h4 class="section-title">Watched Videos</h4> 
        @if($watchHistory->isEmpty())
    <p>You haven't watched any videos yet.</p>
@else
    <div class="row">
        @foreach ($watchHistory as $history) 
                <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-2 mb-4">
                    <div class="card radius-5 h-100">
                        <div class="image">
                            <img src="{{ $history->thumbnail_url  ?? asset('frontend-assets/images/default.png')}}" class="w-100 d-block aspect16"
                                alt="{{ $history->title }}">
                            <a href="{{ url("/video/{$history->uuid}/{$history->slug}") }}">
                                <div class="play fs-40">
                                    <i class="fadeIn animated bx bx-play-circle"></i>
                                </div>
                            </a>
                            <div class="time" id="duration-{{ $history->uuid }}"></div>
                        </div>
                        @php
                            $watchPercentage = $history->duration > 0 ? ($history->watch_duration / $history->duration) * 100 : 0;
                        @endphp
                        <div class="progress mb-0 rounded-0" style="height:4px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $watchPercentage }}%"
                                aria-valuenow="{{ $watchPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body pb-0">
                            <a href="{{ url("/video/{$history->uuid}/{$history->slug}") }}">
                                <h6 class="mb-0">{{ $history->title }}</h6>
                            </a>
                            <small class="text-muted">
                                <i class="lni lni-calendar"></i>
                                {{ \Carbon\Carbon::parse($history->watched_at)->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div> 
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $watchHistory->links() }}
    </div>
@endif
 
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($watchHistory as $history)
            @if ($history)
                const video = document.createElement('video');
                video.src = '{{ $history->video_path }}';
                video.addEventListener('loadedmetadata', function() {
                    const duration = video.duration;
                    const durationElement = document.getElementById('duration-{{ $history->uuid }}');
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
