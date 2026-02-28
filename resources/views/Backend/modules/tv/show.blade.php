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
                    <h5 class="text-primary mb-0">View {{ $tv->title  }}TV</h5>
                </div>

                <div class="card-body">

                        <video
                            id="player"
                            playsinline
                            data-poster="{{ $tv->thumbnail_url }}">
                        </video>




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
            function reportStreamFailure(reason, url) {

                fetch('/api/content/{{ $tv->uuid }}/failure', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        stream_url: url,
                        reason: reason,
                        user_agent: navigator.userAgent
                    })
                });

                console.log('Stream reported:', reason);
            }

            // Load media dynamically
            function loadMedia(url) {
                const type = getMediaType(url);

                if (type === 'hls') {
                    if (Hls.isSupported()) {

                        const hls = new Hls({
                            maxBufferLength: 30,
                            fragLoadingTimeOut: 15000,
                            manifestLoadingTimeOut: 15000
                        });

                        hls.loadSource(url);
                        hls.attachMedia(video);

                        hls.on(Hls.Events.MANIFEST_PARSED, () => {
                            video.play();
                        });

                        // 🔥 ERROR TRACKING
                        hls.on(Hls.Events.ERROR, function(event, data) {

                            console.log('HLS ERROR', data);

                            if (data.fatal) {

                                let reason = data.type + ':' + data.details;

                                reportStreamFailure(reason, url);

                                switch(data.type) {

                                    case Hls.ErrorTypes.NETWORK_ERROR:
                                        hls.startLoad();
                                        break;

                                    case Hls.ErrorTypes.MEDIA_ERROR:
                                        hls.recoverMediaError();
                                        break;

                                    default:
                                        hls.destroy();
                                }
                            }
                        });
                    }
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
            video.addEventListener('error', () => {

                const error = video.error;

                let reason = 'UNKNOWN';

                if (error) {
                    switch (error.code) {
                        case error.MEDIA_ERR_ABORTED:
                            reason = 'MEDIA_ABORTED';
                            break;
                        case error.MEDIA_ERR_NETWORK:
                            reason = 'NETWORK_ERROR';
                            break;
                        case error.MEDIA_ERR_DECODE:
                            reason = 'DECODE_ERROR';
                            break;
                        case error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                            reason = 'NOT_SUPPORTED';
                            break;
                    }
                }

                reportStreamFailure(reason, video.src);
            });
            loadMedia(mediaUrl);


        });
    </script>

@endsection
