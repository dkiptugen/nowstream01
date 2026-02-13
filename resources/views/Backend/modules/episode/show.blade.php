@extends('Backend.includes.layout')

@section('header')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
@endsection

@section('content')

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="text-primary mb-0">View Media</h5>
                </div>

                <div class="card-body">

                    @php
                        $ext = strtolower(pathinfo($episode->content_path, PATHINFO_EXTENSION));
                    @endphp

                    @if($ext === 'mp3')
                        <!-- AUDIO -->
                        <audio
                            id="player"
                            crossorigin="anonymous">
                        </audio>
                    @else
                        <!-- VIDEO -->
                        <div class="ratio ratio-16x9">
                            <video
                                id="player"
                                playsinline
                                data-poster="{{ $episode->thumbnail_url }}"
                                crossorigin="anonymous">
                            </video>
                        </div>
                    @endif

                    <h4 class="mt-3">{{ $episode->title }}</h4>

                </div>
            </div>
        </div>
    </div>

@endsection


@section('footer')

    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const media = document.getElementById('player');
            const source = @json($episode->content_path);

            if (!media || !source) {
                console.error("Media or source missing");
                return;
            }

            let player;

            // HLS
            if (source.endsWith('.m3u8')) {

                if (Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(source);
                    hls.attachMedia(media);

                    hls.on(Hls.Events.MANIFEST_PARSED, function () {
                        player = new Plyr(media);
                    });

                } else if (media.canPlayType('application/vnd.apple.mpegurl')) {
                    media.src = source;
                    player = new Plyr(media);
                }

                return;
            }

            // MP3 or MP4
            media.src = source;
            player = new Plyr(media);

        });
    </script>

@endsection
