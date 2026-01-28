@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout')
@section('content')

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <section>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card radius-5 mb-0 mb-md-4">
                        <div class="fixed-sm-top p-md-0">

                            <video id="player" controls playsinline data-poster="{{ $stream->thumbnail_url }}"></video>


                        </div>
                        <div class="card-body top-margin d-flex justify-content-between align-items-center">
                            <h2 class="mb-0 d-sm-none d-md-block">
                                {{$stream->title}}
                            </h2>
                            <!-- <p class="text-danger mb-0 mt-1">Entertainment</p>
					<small class="text-muted">
						<i class="lni lni-eye"></i> {{ $stream->viewers }} Viewers
						<i class="lni lni-calendar"></i>
						Started Streaming
						{{ $stream->created_at->diffForHumans() }}
								</small> -->
                        </div>
                    </div>
                    <div class="card radius-5 single-video-author box mb-3">
                        <div class="">
                            <div class="float-right d-flex d-none">
                                <button class="btn btn-danger btn-sm mx-2" type="button">
                                    <strong>HD</strong>
                                </button>
                                @php
                                    $channel = Channel::find($stream->channel_id);
                                @endphp

                                @if(Auth::check())
                                    <div class="d-none" id="subscription-controls-{{ $channel->id }}">
                                        @if(Auth::user()->subscribedChannels->contains($channel->id))
                                            <div id="subscribe-btn-{{ $channel->id }}">
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="toggleSubscription({{ $channel->id }}, false)">
                                                    Unsubscribe
                                                </button>
                                            </div>
                                        @else
                                            <div id="subscribe-btn-{{ $channel->id }}">
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="toggleSubscription({{ $channel->id }}, true)">
                                                    Subscribe
                                                </button>
                                            </div>
                                        @endif
                                @endif
                                </div>
                            </div>

                            <img class="ratio1 border bg-light" src="{{ $channel ? $channel->thumbnail : 'Unknown' }}"
                                alt="">
                            <p>
                                <a href="{{ url("/channel/{$channel->id}/{$channel->name}") }}">
                                    <strong>
                                        {{$channel->name}}
                                    </strong>
                                </a>
                                <span title="" data-placement="top" data-toggle="tooltip"
                                    data-original-title="Verified"><i
                                        class="fas fa-check-circle text-success"></i></span>
                            </p>
                            <small class="text-muted">
                                <!-- <i class="lni lni-eye"></i> {{ $stream->viewers }} Viewers -->
                                <i class="lni lni-calendar"></i>
                                Started Streaming
                                {{ $stream->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    <div class="card d-none d-md-block radius-5 box mb-3">
                        <div class="card-body">
                            @php
                                $event = \App\Models\Event::find($stream->event_id);
                            @endphp
                            <p>
                                {{ $event ? $event->title : 'Unknown' }}
                            </p>
                            <h6>About :</h6>
                            <p>
                                {!!$stream->description!!}
                            </p>
                            {{-- <h6>Tags :</h6>
                            <p class="tags mb-0">

                                <span><a href="#">Music</a></span>
                                <span><a href="#">Live Concert</a></span>
                                <span><a href="#">Kenyan Local</a></span>
                                <span><a href="#">1080P</a></span>
                                <span><a href="#">Genge</a></span>
                                <span><a href="#">+ 16</a></span>
                            </p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-5 comment sticky-top position-sticky">
                        <h5 class="p-3 mb-0">Live Comments</h5>
                        <!-- Comment Section -->
                        <div class="comment-top" id="comment-list">
                            <div class="" id="streamcommentlist">
                                @foreach ($comments as $comment)
                                                                <div
                                                                    class="card-body d-flex py-2 border-top px-2 mx-0 w-100 justify-content-between comment-item">
                                                                    <div class="d-flex">
                                                                        <div class="align-self-center text-center me-md-2">
                                                                            @php
                                                                                $comment_user = \App\Models\User::find($comment->user_id);
                                                                            @endphp
                                                                            <img src="{{ $comment_user->image ?? asset('avatar.png') }}" height="50"
                                                                                class="w-100 d-block w-100 aspect1" alt="...">
                                                                        </div>
                                                                        <div class="mx-1 mx-md-2">
                                                                            <div class="media-body text-align-baseline">
                                                                                <small class="my-0 text-muted">
                                                                                    {{ $comment_user->name }}
                                                                                </small>
                                                                                <h6 class="mb-0">
                                                                                    @php
                                                                                        // Sanitize comment to remove links and phone numbers
                                                                                        $sanitizedComment = preg_replace([
                                                                                            '/https?:\/\/\S+/', // Remove URLs
                                                                                            '/\b\d{10,13}\b/' // Remove phone numbers
                                                                                        ], '', $comment->comment);
                                                                                    @endphp
                                                                                    {{ $sanitizedComment }}
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <small class="text-muted float-end time-comm">
                                                                        {{ $comment->created_at->diffForHumans() }}
                                                                    </small>
                                                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-body row border-top px-0 mt-70 mx-0">
                            @auth
                                <form id="stream-comment-form"
                                    action="{{ route('comment.post', ['commentableType' => 'stream', 'commentableId' => $stream->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="chat-footer d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="comment" rows="3"
                                                    placeholder="Type a comment">
                                                <button type="submit" class="input-group-text">
                                                    <i class="bx bx-send"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <p class="text-center mt-3">Please
                                    <a href="{{ route('user.login') }}">login</a>
                                    to post a
                                    comment.
                                </p>
                            @endauth

                        </div>

                    </div>
                </div>

                <div class="d-md-none card radius-5 box mb-3">
                    <div class="card-body">
                        <h6>Event:</h6>
                        @php
                            $event = \App\Models\Event::find($stream->event_id);
                        @endphp
                        <p>
                            {{ $event ? $event->event_name : 'Unknown' }}
                        </p>
                        <h6>About :</h6>
                        <p>
                            {!!$stream->description!!}
                        </p>
                        <h6>Tags :</h6>
                        <p class="tags mb-0">
                            <span><a href="#">Music</a></span>
                            <span><a href="#">Live Concert</a></span>
                            <span><a href="#">Kenyan Local</a></span>
                            <span><a href="#">1080P</a></span>
                            <span><a href="#">Genge</a></span>
                            <span><a href="#">+ 16</a></span>
                        </p>
                    </div>
                </div>

            </div>
        </section>

        @if($streams->isNotEmpty())
            <section>
                <h5 class="mt-4 section-title mb-3">Continue Watching</h5>
                <div class="row">
                    @foreach ($streams as $stream)
                        <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-3">
                            @include('Frontend.includes.components.cards.stream-card')
                        </div>
                    @endforeach
                </div>
                <!--end row-->
            </section>
        @endif

        @if($channels->isNotEmpty())
            <section>
                <h5 class="mt-4 section-title mb-3">Popular Channels</h5>
                <div class="d-flex scrolling">
                    @foreach ($channels as $channel)
                        <div class="col-6 col-lg-2 me-3">
                            @include('Frontend.includes.components.cards.channels')
                        </div>
                    @endforeach
                </div>
                <!--end row-->
            </section>
        @endif
        @endsection
        @section('header')
        <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
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

                .stream-comment-top {
                    height: 40vh;
                    overflow-y: scroll;
                }

                .stream-comment-top {
                    max-height: 30vh;
                    overflow-y: scroll;
                    height: auto;
                }
            }

            p span {
                color: inherit !important;
            }

            #my-video {
                width: 100%;
                aspect-ratio: 16 / 9 !important;
                height: auto;
            }

            .video-js {
                width: 100%;
                height: auto;
            }

            font {
                color: inherit;
            }

            /* Center the play button */
            .vjs-big-play-centered .vjs-big-play-button {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin-top: initial !important;
                margin-left: initial !important;
                background-color: #de1f1f;


            }

            /* Ensure the poster image covers the video area */
            .video-js .vjs-poster {
                background-size: cover;
                background-position: center;
            }

            /* Fullscreen and rotated styles for mobile */
            @media (max-width: 768px) {
                .video-js.fullscreen {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    transform: rotate(90deg);
                    transform-origin: center center;
                    z-index: 9999;
                }

                .comment-top {
                    height: 30vh;
                    overflow-y: scroll;
                }
            }

            @media (max-width: 767px) {
                .fixed-sm-top {
                    z-index: 99999;
                }

                .d-sm-none {
                    font-size: 1px;
                }

                .page-footer {
                    z-index: 9999;
                }

                .col-12.col-lg-4,
                .col-12.col-lg-8 {
                    padding: 0;
                }
            }

            .pace {
                display: none;
            }

            #player {
                transition: all 0.3s ease-in-out;
            }

            #player.sticky {
                position: fixed;
                bottom: 0;
                right: 0;
                width: 300px;
                z-index: 9999;
                height: max-content;
            }
        </style>
        @endsection
        @section('footer')

        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const video = document.getElementById('player');
                const player = new Plyr(video, {
                    // You can customize Plyr options here
                });

                // Your video URL
                const videoUrl = '{{ $stream->stream_video_link }}';

                // Function to determine video type
                function getVideoType(url) {
                    const extension = url.split('.').pop();
                    if (extension === 'm3u8') {
                        return 'application/vnd.apple.mpegurl';
                    } else if (extension === 'mp4') {
                        return 'video/mp4';
                    } else if (extension === 'mov') {
                        return 'video/quicktime';
                    } else {
                        return '';
                    }
                }

                // Function to load video based on its type
                function loadVideo(url) {
                    const type = getVideoType(url);

                    if (type === 'application/vnd.apple.mpegurl') {
                        if (Hls.isSupported()) {
                            const hls = new Hls();
                            hls.loadSource(url);
                            hls.attachMedia(video);
                            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                                video.play();
                            });
                        } else if (video.canPlayType(type)) {
                            video.src = url;
                            video.addEventListener('loadedmetadata', () => {
                                video.play();
                            });
                        } else {
                            console.error('HLS is not supported in this browser');
                        }
                    } else if (video.canPlayType(type)) {
                        video.src = url;
                        video.addEventListener('loadedmetadata', () => {
                            video.play();
                        });
                    } else {
                        console.error('This video format is not supported in this browser');
                    }
                }

                // Load the video
                loadVideo(videoUrl);
            });
        </script>
        <script>

            $(document).ready(function () {
                $(document).on('submit', '#stream-comment-form', function (e) {
                    e.preventDefault();
                    var frm = $(this);
                    var formData = new FormData(this);  // Use FormData to handle file uploads
                    $.ajax({
                        type: 'POST',
                        url: frm.attr('action'),
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: formData,
                        contentType: false,  // Prevent jQuery from setting the Content-Type
                        processData: false,
                        success: function (Mess) {
                            frm.trigger('reset');
                        },
                        error: function (xhr, status, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                });
            });

            Pusher.logToConsole = true;
            var pusher = new Pusher("cfc4e18a5372052374ee", {
                cluster: 'mt1',
                encrypted: true,
                authEndpoint: '/pusher/auth',
            });

            var channel = pusher.subscribe('stream_comment.{{$stream->id}}');
            channel.bind('new_comment', function (data) {
                console.log(data);
                var comment = '<div class="card-body d-flex py-2 border-top px-2 mx-0 w-100 justify-content-between comment-item">' +
                    '<div class="d-flex">' +
                    ' <div class="align-self-center text-center">' +
                    ' <img src="' + data.user_img + '" height="50" class="w-100 d-block w-100 aspect1" alt="">' +
                    '</div>' +
                    '<div class="mx-1 mx-md-2">' +
                    '<div class="media-body">' +
                    '<h6 class="my-0">' +
                    data.user_name +
                    ' </h6>' +
                    '<p class="mb-0">' +
                    data.comment +
                    '</p>' +
                    '</div>' +
                    ' </div>' +
                    '</div>' +
                    ' <small class="text-muted float-end time-comm">' +
                    data.comment_time +
                    ' </small>' +
                    '</div>';
                $('#streamcommentlist').append(comment);
                const commentTop = document.querySelector('.comment-top');
                commentTop.scrollTop = commentTop.scrollHeight;

            });

        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const player = document.getElementById('player');
                const playerOffsetTop = player.offsetTop;

                window.addEventListener('scroll', function () {
                    if (window.scrollY > playerOffsetTop) {
                        player.classList.add('sticky');
                    } else {
                        player.classList.remove('sticky');
                    }
                });
            });
        </script>
        @endsection
