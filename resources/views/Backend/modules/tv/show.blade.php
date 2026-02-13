@extends('Backend.includes.layout')

@section('header')
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-border-primary shadow">
                <div class="card-header">
                    <h3 class="my-0 card-title text-primary h5">
                        View Tv
                    </h3>
                </div>

                <div class="card-body">

                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- IMPORTANT: No 'controls' attribute -->
                        <video
                            id="player"
                            class="embed-responsive-item"
                            playsinline
                            data-poster="{{ $tv->thumbnail_url }}"
                            crossorigin>
                        </video>
                    </div>

                    <h3 class="mt-3">{{ $tv->title }}</h3>

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

            const video = document.getElementById('player');
            const source = @json($tv->stream_url);
            let player;

            function initPlayer() {
                player = new Plyr(video, {
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
            }

            function loadHLS(url) {

                if (Hls.isSupported()) {

                    const hls = new Hls({
                        enableWorker: true,
                        lowLatencyMode: true,
                        maxBufferLength: 30
                    });

                    hls.loadSource(url);
                    hls.attachMedia(video);

                    hls.on(Hls.Events.MANIFEST_PARSED, function () {
                        initPlayer();
                    });

                    hls.on(Hls.Events.ERROR, function (event, data) {

                        if (data.fatal) {

                            switch (data.type) {

                                case Hls.ErrorTypes.NETWORK_ERROR:
                                    console.warn('Network error. Trying to recover...');
                                    hls.startLoad();
                                    break;

                                case Hls.ErrorTypes.MEDIA_ERROR:
                                    console.warn('Media error. Trying to recover...');
                                    hls.recoverMediaError();
                                    break;

                                default:
                                    console.error('Fatal error. Destroying player.');
                                    hls.destroy();
                                    break;
                            }
                        }
                    });

                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {

                    // Safari native HLS support
                    video.src = url;
                    initPlayer();

                } else {
                    console.error('HLS not supported in this browser.');
                }
            }

            function loadMP4(url) {
                video.src = url;
                initPlayer();
            }

            if (!source) {
                console.error('Stream URL missing.');
                return;
            }

            if (source.endsWith('.m3u8')) {
                loadHLS(source);
            } else {
                loadMP4(source);
            }

        });
    </script>
@endsection
