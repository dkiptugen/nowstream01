@extends('Backend.includes.layout')

@section('header')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css"/>
@endsection

@section('content')

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="text-primary mb-0">View: {{ $episode->title }}</h5>
                </div>

                <div class="card-body">

                    @php
                        $path = parse_url($episode->stream_url, PHP_URL_PATH);

                            $ext = strtolower(pathinfo($path->path??'', PATHINFO_EXTENSION));
                            $mime =  match ($ext) {
                                        'mp3' => 'audio/mpeg',
                                        'm4a' => 'audio/mp4',
                                        'aac' => 'audio/aac',
                                        'wav' => 'audio/wav',
                                        'ogg' => 'audio/ogg',
                                        'm3u8' => 'application/vnd.apple.mpegurl',
                                        default => 'audio/mpeg',
                                    };
                    @endphp


                        <!-- VIDEO -->
                    <div class="ratio ratio-16x9">
                        <video id="player" playsinline data-poster="{{ $episode->thumbnail_url }}">
                            <source src="{{ $episode->stream_url }}" type="{{ $mime }}">
                        </video>
                    </div>


                    <h4 class="mt-3"></h4>

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
            const player = new Plyr(media, {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume']
            });

        });
    </script>

@endsection
