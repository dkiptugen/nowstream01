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
        document.addEventListener('DOMContentLoaded', function () {

            console.log("Initializing TV Player...");

            const video = document.getElementById('player');
            const source = @json($tv->stream_url);

            if (!video) {
                console.error("Video element not found");
                return;
            }

            if (!source) {
                console.error("Stream URL missing");
                return;
            }

            let player = new Plyr(video, {
                controls: [
                    'play-large',
                    'play',
                    'progress',
                    'current-time',
                    'mute',
                    'volume',
                    'settings',
                    'fullscreen'
                ]
            });

            if (source.endsWith('.m3u8')) {

                if (Hls.isSupported()) {

                    const hls = new Hls({
                        enableWorker: true,
                        lowLatencyMode: true
                    });

                    hls.loadSource(source);
                    hls.attachMedia(video);

                    hls.on(Hls.Events.ERROR, function (event, data) {
                        if (data.fatal) {
                            console.error("Fatal HLS error:", data);
                            hls.destroy();
                        }
                    });

                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = source;
                }

            } else {
                video.src = source;
            }

        });
    </script>

@endsection
