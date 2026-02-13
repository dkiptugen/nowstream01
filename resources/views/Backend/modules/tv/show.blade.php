@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col ">
            <div class="card card-border-primary shadow">
                <div class="card-header">
                    <h3 class="my-0 card-title text-primary  h5">
                        View Tv
                    </h3>
                </div>
                <div class="card-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <video id="player" class="embed-responsive-item" playsinline crossorigin></video>
                    </div>
                    <h3>{{$tv->title}}</h3>

                </div>

            </div>
        </div>
    </div>

@endsection
@section('header')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css"/>
@endsection


@section('footer')
    <!-- HLS.js (for .m3u8 support) -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('player');
            const source = '{{ $tv->stream_url }}';

            const player = new Plyr(video, {
                controls: [
                    'play-large', 'play', 'progress', 'current-time',
                    'mute', 'volume', 'settings', 'fullscreen'
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
                            console.error('HLS fatal error:', data);
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
