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
                        <video id="player" class="embed-responsive-item" data-poster="{{ $tv->thumbnail_url }}"></video>
                    </div>
                    <h3>{{$tv->title}}</h3>

                </div>

            </div>
        </div>
    </div>

@endsection
@section('header')
@endsection
@section('footer')
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
                            <video id="player" class="embed-responsive-item" playsinline data-poster="{{ $tv->thumbnail_url }}" controls crossorigin></video>
                        </div>
                        <h3>{{$tv->title}}</h3>

                    </div>

                </div>
            </div>
        </div>

    @endsection
    @section('header')
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
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
                let player;

                function initPlayer() {
                    player = new Plyr(video);
                }

                function loadHLS(url) {
                    if (Hls.isSupported()) {
                        const hls = new Hls({
                            maxBufferLength: 30,
                            maxMaxBufferLength: 60,
                            enableWorker: true
                        });

                        hls.loadSource(url);
                        hls.attachMedia(video);

                        hls.on(Hls.Events.MANIFEST_PARSED, function () {
                            initPlayer();
                        });

                        hls.on(Hls.Events.ERROR, function (event, data) {
                            if (data.fatal) {
                                console.error('Fatal HLS error:', data);
                                switch (data.type) {
                                    case Hls.ErrorTypes.NETWORK_ERROR:
                                        hls.startLoad();
                                        break;
                                    case Hls.ErrorTypes.MEDIA_ERROR:
                                        hls.recoverMediaError();
                                        break;
                                    default:
                                        hls.destroy();
                                        break;
                                }
                            }
                        });

                    }
                    else if (video.canPlayType('application/vnd.apple.mpegurl'))
                    {
                        video.src = url;
                        initPlayer();
                    }
                    else {
                        console.error('HLS not supported in this browser');
                    }
                }

                function loadMP4(url) {
                    video.src = url;
                    initPlayer();
                }

                if (source.endsWith('.m3u8')) {
                    loadHLS(source);
                } else {
                    loadMP4(source);
                }
            });
        </script>


@endsection
