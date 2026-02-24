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
           <h1>{{ $history->content->title }}</h1> 
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
        @foreach($watchHistory as $history)
        @if($history->watchable)
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