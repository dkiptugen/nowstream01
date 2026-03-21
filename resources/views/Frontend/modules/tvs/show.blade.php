@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')

<main>

	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
		<div class="container custom-container">
			<div class="row position-relative g-0 tv-comments-row">
				<div class="col-xl-9 col-lg-8">
					<div id="videoWrap" class="tv-wrap">
						<video
							id="player"
							data-stream="{{ $tv->stream_url }}"
							playsinline
							controls
							poster="{{ $tv->thumbnail_url }}">
						</video>
						<div class="live-badge">LIVE</div>
					</div>
				</div>
                <div class="col-xl-3 col-lg-4">
                    @include('Frontend.includes.components.partials.video-comments', [
                    'comments' => $comments,
                    'commentableType' => 'tv',
                    'commentableId' => $tv->uuid
                    ])
                </div>

				<div class="col-xl-12 col-lg-12 mt-4">
					<div class="movie-details-content">
						<h5>TV Channel</h5>
						@php
						$words = preg_split('/\s+/', trim(ucfirst($tv->title)));
						$half = (int) ceil(count($words) / 2);

						$firstHalf = implode(' ', array_slice($words, 0, $half));
						$secondHalf = implode(' ', array_slice($words, $half));
						@endphp

						<h2>
							{{ $firstHalf }}
							<span>{{ $secondHalf }}</span>
						</h2>

						<div class="banner-meta">
							<ul>
								<li class="quality">
									<span>@if($tv->is_explicit)R @else Pg 18 @endif</span>
									<span>{{ $tv->quality ?? 'HD' }}</span>
								</li>
								<li class="category">
									@if($tv->categories)
                                        @foreach($tv->categories as $category)
                                        <a href="{{ route('genre.tvs', $category->slug) }}">
                                            {{ $category->name }}@if(!$loop->last),@endif
                                        </a>
                                        @endforeach
                                    @endif
								</li>
							</ul>
						</div>
						<p>{{ $tv->description }}</p>
						<div class="movie-details-prime">
							<ul>
								<li class="share"><a href="#"><i class="fas fa-share-alt"></i> Share</a></li>
								<li class="streaming">
									<h6>Streamer.co.ke</h6>
									<span>Tv Streaming</span>
								</li>
                                @if(Auth::check())
                                <li class="watch">
                                    <div id="favorite-btn">
                                        @php
                                        $favorites = Auth::user()->favoritetvs ?? collect();
                                        @endphp

                                        @if($favorites->contains('uuid', $tv->uuid))
                                        <button class="btn btn-sm btn-danger" onclick="toggleFavorite('{{ $tv->uuid }}', false)">
                                            <i class="fas fa-heart"></i> Liked
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline-light" onclick="toggleFavorite('{{ $tv->uuid }}', true)">
                                            <i class="far fa-heart"></i> Like TV
                                        </button>
                                        @endif
                                    </div>
                                </li>
                                @endif
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	@if(isset($related) && $related->isNotEmpty())
	<section class="movie-area movie-bg" data-background="{{ asset('assets/img')}}/bg/movie_bg.jpg">
		<div class="container">
			<div class="episode-top-wrap">
				<div class="section-title"> <span class="sub-title">Related Tvs</span>
					<h2 class="title">Trending Tvs</h2>
				</div>
			</div>
		</div>

		<div class="pcar-wrapper">

			<!-- Outside container overlays -->
			<div class="pcar-overlay pcar-overlay-left"></div>
			<div class="pcar-overlay pcar-overlay-right"></div>

			<div class="pcar" data-autoplay="true" data-interval="3500" data-desktop="11" data-tablet="3"
				data-mobile="1">

				<div class="pcar-track">
					@foreach($related as $item)
					<div class="pcar-item">
						@include('Frontend.includes.components.cards.slider-card')
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</section>
	@endif

</main>

@endsection

@section('header')
<style>
    @media (min-width: 769px) {
        .page-footer {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .page-wrapper {
            margin-top: 30px !important;
        }
    }

    .plyr--tv {
        padding: 0;
    }

    #player {
        transition: all 0.3s ease-in-out;
    }

    .btn-send {
        padding: 5px;
        border: 1px solid #2a2b2c;
    }

    /* Make both columns stretch to same height */
    .tv-comments-row {
        align-items: stretch !important;
    }

    .text-light-50 {
        color: rgba(255, 255, 255, .55) !important;
    }

    .yt-comments-card {
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 14px;
        background: rgba(10, 10, 10, 0.55);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .45);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .yt-comments-header,
    .yt-comments-footer {
        background: rgba(0, 0, 0, .35);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .yt-comments-body {
        flex: 1;
        overflow-y: auto;
        max-height: 520px;
    }

    .yt-comments-body::-webkit-scrollbar {
        width: 6px;
    }

    .yt-comments-body::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .15);
        border-radius: 20px;
    }

    .yt-comment-input {
        background: rgba(255, 255, 255, .06) !important;
        border: 1px solid rgba(255, 255, 255, .10) !important;
        color: #fff !important;
        border-radius: 10px 0 0 !important;
        padding: 10px 12px !important;
    }

    .yt-comment-input::placeholder {
        color: rgba(255, 255, 255, .55) !important;
    }

    .yt-actions a {
        color: rgba(255, 255, 255, .55);
        text-decoration: none;
        transition: .2s;
    }

    .yt-actions a:hover {
        color: #fff;
        text-decoration: none;
    }

    #comment-list {
        overflow-y: auto;
    }

    /* tv wrapper uses 16:9 ratio */
    .tv-wrap {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        overflow: hidden;
        border-radius: 10px;
        background: #000;
    }

    .tv-wrap video,
    .tv-wrap iframe,
    .tv-wrap .plyr {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    @media (min-width: 1200px) {
        #commentsCard {
            height: 100%;
        }
    }

    .live-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #ff0000;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 3px;
        z-index: 10;
        letter-spacing: 1px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
</style>
@endsection

@section('footer')
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    function toggleFavorite(uuid, isAdding) {
        const url = isAdding ? '/favorites/add' : '/favorites/remove';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ content_uuid: uuid, type: 'tv' })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        })
        .catch(err => console.error('Error toggling favorite:', err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('player');
        if (!video) return;

        const streamUrl = video.dataset.stream;
        if (!streamUrl) return;

        const player = new Plyr(video, {});

        let hls = null;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 10;
        const reconnectDelay = 3000;

        function getMediaType(url) {
            const ext = url.split('.').pop().toLowerCase();
            if (ext === 'm3u8') return 'hls';
            if (ext === 'mp4') return 'video/mp4';
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
        }

        function autoplayWithSound() {
            video.muted = false;
            video.volume = 1;
            video.play().catch(() => {
                video.muted = true;
                video.play().catch(() => {});
            });
        }

        function destroyHls() {
            if (hls) {
                hls.destroy();
                hls = null;
            }
        }

        function reconnectStream() {
            if (reconnectAttempts >= maxReconnectAttempts) {
                console.error('Max reconnect attempts reached');
                return;
            }
            reconnectAttempts++;
            setTimeout(() => loadStream(streamUrl), reconnectDelay);
        }

        function loadStream(url) {
            const type = getMediaType(url);
            video.pause();
            video.removeAttribute('src');
            video.load();
            destroyHls();

            if (type === 'hls') {
                if (Hls.isSupported()) {
                    hls = new Hls({
                        maxBufferLength: 30,
                        enableWorker: true,
                        lowLatencyMode: true
                    });
                    hls.loadSource(url);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        reconnectAttempts = 0;
                        autoplayWithSound();
                    });
                    hls.on(Hls.Events.ERROR, (event, data) => {
                        if (data.fatal) {
                            reportStreamFailure(data.type + ':' + data.details, url);
                            switch (data.type) {
                                case Hls.ErrorTypes.NETWORK_ERROR:
                                    reconnectStream();
                                    break;
                                case Hls.ErrorTypes.MEDIA_ERROR:
                                    hls.recoverMediaError();
                                    break;
                                default:
                                    reconnectStream();
                                    break;
                            }
                        }
                    });
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = url;
                    video.addEventListener('loadedmetadata', autoplayWithSound, { once: true });
                }
            } else {
                video.src = url;
                video.addEventListener('loadedmetadata', autoplayWithSound, { once: true });
            }
        }

        window.addEventListener('online', () => {
            reconnectAttempts = 0;
            loadStream(streamUrl);
        });

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && video.paused) {
                video.play().catch(() => {});
            }
        });

        loadStream(streamUrl);
    });

    $(document).ready(function() {
        function scrollCommentsToBottom() {
            let list = $('#comment-list');
            if (list.length) list.scrollTop(list[0].scrollHeight);
        }

        scrollCommentsToBottom();

        $('#comment-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let btn = $('#comment-submit-btn');
            let commentInput = $('#comment-input');
            let commentText = commentInput.val().trim();

            if (!commentText) return;
            btn.prop('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(res) {
                    let name = "{{ auth()->check() ? auth()->user()->name : 'Guest' }}";
                    let avatar = "{{ auth()->check() ? (auth()->user()->image ? asset('storage/'.auth()->user()->image) : asset('assets/img/images/default-avatar.png')) : asset('assets/img/images/default-avatar.png') }}";
                    let safeText = $('<div>').text(commentText).html();

                    let newComment = `
                        <div class="media py-3 border-bottom border-dark">
                            <img src="${avatar}" class="mr-3 rounded-circle" style="width:42px;height:42px;object-fit:cover;">
                            <div class="media-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong class="text-white" style="font-size: 14px;">${name}</strong>
                                    <small class="text-light-50" style="font-size: 12px;">just now</small>
                                </div>
                                <div class="mt-1 text-light" style="font-size: 14px; line-height: 1.4;">${safeText}</div>
                            </div>
                        </div>`;
                    $('#comment-list').append(newComment);
                    commentInput.val('');
                    scrollCommentsToBottom();
                },
                complete: () => btn.prop('disabled', false)
            });
        });

        function syncCommentsHeight() {
            if (window.innerWidth < 1200) {
                $('#commentsCard').css('height', 'auto');
                return;
            }
            let videoHeight = $('#videoWrap').outerHeight();
            if (videoHeight > 0) $('#commentsCard').css('height', videoHeight + 'px');
        }

        syncCommentsHeight();
        $(window).on('resize', syncCommentsHeight);
        setTimeout(syncCommentsHeight, 1000);
    });
</script>
@endsection
