@php use App\Models\Channel; @endphp
@extends('Frontend.includes.layout') 
@section('content')

<main>

	<section class="movie-details-area" data-background="{{ asset('assets/img/bg/movie_details_bg.jpg') }}">
                <div class="container custom-container">
                    <div class="row align-items-center position-relative g-0">
					<div class="col-xl-9 col-lg-8">
					<div id="videoWrap" class="tv-wrap">
													<tv id="player" controls playsinline data-poster="{{ $tv->thumbnail }}"></tv> 	   </div>

@php
	 $oldvid= $tv;
	$vid = $tv->id;
@endphp
                        </div>
						@include('Frontend.includes.components.partials.tv-comments') 

                        <div class="col-xl-7 col-lg-8 mt-4">
                            <div class="movie-details-content">
                                <h5>New Episodes</h5>
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
                                            <span>Pg 18</span>
                                            <span>hd</span>
                                        </li>
                                        <li class="category">
                                            <a href="#">Romance,</a>
                                            <a href="#">Drama</a>
                                        </li>
                                        <li class="release-time">
                                            <span><i class="far fa-calendar-alt"></i> 2021</span>
                                            <span><i class="far fa-clock"></i> 128 min</span>
                                        </li>
                                    </ul>
                                </div>
                                <p>{{ $tv->description }}</p>
                                <div class="movie-details-prime">
                                    <ul>
                                        <li class="share"><a href="#"><i class="fas fa-share-alt"></i> Share</a></li>
                                        <li class="streaming">
                                            <h6>Prime tv</h6>
                                            <span>Streaming Channels</span>
                                        </li>
                                        <li class="watch"><a href="https://www.youtube.com/watch?v=R2gbPxeNk2E" class="btn popup-tv"><i class="fas fa-play"></i> Watch Now</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="movie-details-btn">
                            <a href="img/poster/movie_details_img.jpg" class="download-btn" download="">Download <img src="fonts/download.svg" alt=""></a>
                        </div>
                    </div>
                </div>
            </section>
		<section class="d-none">
			<div class="row">
				<div class="col-12 col-lg-8">
					<div class="card radius-5 row mx-md-0">

						<tv id="player" controls playsinline data-poster="{{ $tv->thumbnail }}"></tv>

						@php
						     $oldvid= $tv;
							$vid = $tv->id;
						@endphp
						<div class="card-body">
							<h2 class="mb-0">
								{{$tv->title}}
							</h2>
							<p class="text-danger mb-0 mt-1">Entertainment</p>
							<small class="text-muted"><i class="lni lni-eye"></i> 1.9M Views <i
									class="lni lni-calendar"></i>
								Started Streaming 12min ago </small>
						</div>
					</div>
					<div class="card radius-5 single-tv-author box mb-3">
						<div class="">
							<div class="float-right d-flex align-items-center">

								@if(Auth::check())
									<div id="favorite-btn">
										@if(Auth::user()->favoritetvs->contains($tv->id))
											<button class="btn btn-danger btn-sm"
												onclick="toggleFavorite({{ $tv->id }}, false)">
												Unlike tv
											</button>
										@else
											<button class="btn btn-outline-primary btn-sm"
												onclick="toggleFavorite({{ $tv->id }}, true)">
												Like tv
											</button>
										@endif
									</div>
								@endif
								<div class="mx-1">.</div>



						</div>
                        
						<p><a href="#"><strong>

 
								</strong></a> <span title="" data-placement="top" data-toggle="tooltip"
								data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span>
						</p>
						<small>Started Streaming 12min ago</small>
					</div>
					<div class="card radius-5 box mb-3">
						<div class="card-body">
							<h6>Event:</h6>
							@php
								$event = \App\Models\Event::find($tv->event_id);
							@endphp
							<p>
								{{ $event ? $event->title : 'Unknown' }}
							</p>
							{{-- <h6>Hosts:</h6>
							<p>Nathan Drake , Victor Sullivan , Sam Drake , Elena Fisher</p>
							<h6>Performances:</h6>
							<p> Drake , Kelele Takatifu , Khaligraph Jones , Mejja</p>
							<h6>Category :</h6>
							<p>Gospel , VVIP Exclusive , Gameplay , 1080p</p>
							<h6>About :</h6> --}}
							<p>
								{!!$tv->description!!}
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
				
			</div>
		</section>
		@if($related->isNotEmpty())
		<section class="movie-area movie-bg" data-background="{{ asset('assets/img')}}/bg/movie_bg.jpg">
		<div class="container">
			<h5 class="mb-3 section-title">
				<!-- Error Alert -->
				@if (session('success'))
					You dont Have an active subscription. Pick an Event Below <br>

				@endif 
			</h5>
			<div class="row align-items-end mb-60">
				<div class="col-lg-6">
					<div class="section-title text-center text-lg-left">
						<span class="sub-title">.......</span>
						<h2 class="title">Continue <span>Watching</span></h2>
					</div>
				</div>
				<div class="col-lg-6">
				</div>
			</div>
			<div class="row tr-movie-active">
				@foreach($related as $tv)  
					@include('Frontend.includes.components.cards.tv-card')
				@endforeach
			</div>
		</div>
	</section>
		@endif
 
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

				.comment-top {
					height: 40vh;
					overflow-y: scroll;
				}

				.comment-top {
					max-height: 30vh;
					overflow-y: scroll;
					height: auto;
				}
			}

			.plyr--tv {
				padding: 0;
			}

			#my-tv {
				width: 100%;
				aspect-ratio: 16 / 9 !important;
				height: auto;
			}

			#player {
				transition: all 0.3s ease-in-out;
			}

			#player.sticky {
				position: fixed;
				bottom: 0;
				right: 0;
				width: 400px;
				z-index: 9999;
				height: max-content;
			}
		</style>

<style>
	.btn-send {
    padding: 5px;
    border: 1px solid #2a2b2c;
}
	/* Make both columns stretch to same height */
.tv-comments-row {
  align-items: stretch !important;
}

/* comments card takes full height of the column */
.yt-comments-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

/* scroll area */
.yt-comments-body {
  flex: 1;
  overflow-y: auto;
}

    .text-light-50 { color: rgba(255,255,255,.55) !important; }

    .yt-comments-card{
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 14px;
        background: rgba(10, 10, 10, 0.55);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        box-shadow: 0 10px 30px rgba(0,0,0,.45);
        overflow: hidden;
    }

    .yt-comments-header,
    .yt-comments-footer{
        background: rgba(0,0,0,.35);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .yt-comments-body{
        max-height: 520px;
        overflow-y: auto;
    }

    .yt-comments-body::-webkit-scrollbar{ width: 6px; }
    .yt-comments-body::-webkit-scrollbar-thumb{
        background: rgba(255,255,255,.15);
        border-radius: 20px;
    }

    .yt-comment-input{
        background: rgba(255,255,255,.06) !important;
        border: 1px solid rgba(255,255,255,.10) !important;
        color: #fff !important;
        border-radius: 10px 0 0 !important;
        padding: 10px 12px !important;
    }

    .yt-comment-input::placeholder{ color: rgba(255,255,255,.55) !important; }

    .yt-actions a{
        color: rgba(255,255,255,.55);
        text-decoration: none;
        transition: .2s;
    }
    .yt-actions a:hover{
        color: #fff;
        text-decoration: none;
    }
	#comment-list{
    max-height: 520px;
    overflow-y: auto;
}
/* tv wrapper uses 16:9 ratio like YouTube */
.tv-wrap{
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 */
    overflow: hidden;
    border-radius: 10px;
}
.tv-wrap tv,
.tv-wrap iframe,
.tv-wrap .plyr{
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
}

/* Comments card height must match tv height */
@media (min-width: 1200px) {
    #commentsCard{
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Scroll only the comment list */
    #comment-list{
        flex: 1 1 auto;
        overflow-y: auto;
        min-height: 0;
    }
}

/* Dark translucent */
.yt-comments-card{
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,.08);
}
.sticky{
	z-index: 99;
}

</style>
		@endsection
		@section('footer')

		<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

		<script>
			document.addEventListener('DOMContentLoaded', () => {
				const tv = document.getElementById('player');
				const player = new Plyr(tv, {
					// You can customize Plyr options here
				});

				// Your tv URL
				const tvUrl = '{{ $oldvid->tv_path }}';

				// Function to determine tv type
				function gettvType(url) {
					const extension = url.split('.').pop();
					if (extension === 'm3u8') {
						return 'application/vnd.apple.mpegurl';
					} else if (extension === 'mp4') {
						return 'tv/mp4';
					} else if (extension === 'mov') {
						return 'tv/quicktime';
					} else {
						return '';
					}
				}

				// Function to load tv based on its type
				function loadtv(url) {
					const type = gettvType(url);

					if (type === 'application/vnd.apple.mpegurl') {
						if (Hls.isSupported()) {
							const hls = new Hls();
							hls.loadSource(url);
							hls.attachMedia(tv);
							hls.on(Hls.Events.MANIFEST_PARSED, () => {
								tv.play();
							});
						} else if (tv.canPlayType(type)) {
							tv.src = url;
							tv.addEventListener('loadedmetadata', () => {
								tv.play();
							});
						} else {
							console.error('HLS is not supported in this browser');
						}
					} else if (tv.canPlayType(type)) {
						tv.src = url;
						tv.addEventListener('loadedmetadata', () => {
							tv.play();
						});
					} else {
						console.error('This tv format is not supported in this browser');
					}
				}

				// Load the tv
				loadtv(tvUrl);
			});
		</script>
		<script>
$(document).ready(function () {

    function scrollCommentsToBottom() {
        let list = $('#comment-list');
        if (!list.length) return;
        list.scrollTop(list[0].scrollHeight);
    }

    // scroll on load
    scrollCommentsToBottom();

    // submit comment
    $('#comment-form').on('submit', function (e) {
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
            success: function (res) {

                // if you use broadcast()->toOthers() then we must append manually for sender
                let name = "{{ auth()->check() ? auth()->user()->name : '' }}";
                let avatar = "{{ auth()->check() ? (auth()->user()->image ?? asset('avatar.png')) : asset('avatar.png') }}";

                let safeText = $('<div>').text(commentText).html();

                let newComment = `
                    <div class="media py-3 border-bottom border-dark">
                        <img src="${avatar}" class="mr-3 rounded-circle"
                             style="width:42px;height:42px;object-fit:cover;" alt="avatar">

                        <div class="media-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap">
                                    <strong class="mr-2 text-white" style="font-size: 14px;">
                                        ${name}
                                    </strong>
                                    <small class="text-light-50" style="font-size: 12px;">
                                        just now
                                    </small>
                                </div>
                            </div>

                            <div class="mt-1 text-light" style="font-size: 14px; line-height: 1.4;">
                                ${safeText}
                            </div>

                            <div class="mt-2 d-flex align-items-center yt-actions" style="font-size: 13px;">
                                <a href="javascript:void(0)" class="mr-3"><i class="fa fa-thumbs-up"></i> Like</a>
                                <a href="javascript:void(0)" class="mr-3"><i class="fa fa-thumbs-down"></i> Dislike</a>
                                <a href="javascript:void(0)"><i class="fa fa-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                `;

                // IMPORTANT: append to bottom (latest at bottom)
                $('#commentlist').append(newComment);

                // update count
                let countEl = $('#comment-count');
                countEl.text(parseInt(countEl.text() || 0) + 1);

                // clear input
                commentInput.val('');

                // scroll bottom
                scrollCommentsToBottom();
            },
            error: function () {
                alert('Failed to post comment. Please try again.');
            },
            complete: function () {
                btn.prop('disabled', false);
            }
        });
    });

});
</script>

		<!-- <script>
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
		</script> -->
		 
<script>
function syncCommentsHeight(){
    if (window.innerWidth < 1200) return;

    let videoWrap = document.getElementById('videoWrap');
    let commentsCard = document.getElementById('commentsCard');

    if(!videoWrap || !commentsCard) return;

    commentsCard.style.height = videoWrap.offsetHeight + "px";
}

$(document).ready(function(){
    syncCommentsHeight();
    $(window).on('resize', syncCommentsHeight);

    // delay to allow Plyr render
    setTimeout(syncCommentsHeight, 300);
    setTimeout(syncCommentsHeight, 1000);
});
</script>

		@endsection