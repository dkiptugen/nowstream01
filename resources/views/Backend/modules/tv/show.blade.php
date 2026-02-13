@extends('Backend.includes.layout')

@section('header')
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
@endsection

@section('content')

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="text-primary mb-0">View TV</h5>
                </div>

                <div class="card-body">

                        <video
                            id="player"
                            playsinline
                            data-poster="{{ $tv->thumbnail_url }}"
                            crossorigin = "anonymous">
                        </video>


                    <h4 class="mt-3">{{ $tv->title }}</h4>

                </div>
            </div>
        </div>
    </div>

@endsection


@section('footer')

    <!-- HLS.js -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('player');
            const player = new Plyr(video, {});
            // Determine media type
            function getMediaType(url) {
                const ext = url.split('.').pop().toLowerCase();
                if (ext === 'm3u8') return 'hls';
                if (ext === 'mp4') return 'video/mp4';
                if (ext === 'mp3') return 'audio/mp3';
                if (ext === 'mov') return 'video/quicktime';
                return '';
            }

            // Load media dynamically
            function loadMedia(url) {
                const type = getMediaType(url);

                if (type === 'hls') {
                    if (Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(url);
                        hls.attachMedia(video);
                        hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());
                    }
                    else if (video.canPlayType('application/vnd.apple.mpegurl'))
                    {
                        video.src = url;
                        video.addEventListener('loadedmetadata', () => video.play());
                    }
                    else console.error('HLS not supported');
                }
                else if (video.canPlayType(type))
                {
                    video.src = url;
                    video.addEventListener('loadedmetadata', () => video.play());
                }
                else console.error('Unsupported media type');
            }

            // Example video/live URL
            const mediaUrl = '{{ $tv->stream_url }}'; // Replace with dynamic URL

            loadMedia(mediaUrl);


        });
    </script>

@endsection
