<div class="movie-wrapper">
    
   @php
    $thumbnail = $item->thumbnail_url ?? asset('assets/img/default.png');
    $title = ucfirst($item->title);

    // Map content types to routes
    $routeMap = [
        'video' => 'video.show',
        'tv' => 'tv.show',
        'radio' => 'radio.show',
        'podcast' => 'podcast.show',
    ];

    $routeName = $routeMap[$item->content_group] ?? null;
    $link = $routeName ? route($routeName, [$item->uuid, $item->slug]) : '#';
@endphp


    @if($item->content_group === 'radio')
        <div class="movie-item" style="cursor:pointer;"
            onclick="playSingleAudio('{{ $item->stream_url }}', '{{ addslashes($title) }}', 'Live Radio', '{{ $thumbnail }}')">
            <div class="movie-poster">
                <img src="{{ $thumbnail }}" class="w-100 d-block" alt="{{ $title }}" style="object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
                <div class="play fs-40">
                    <i class="fadeIn animated bx bx-play-circle"></i>
                </div>
            </div>
            <div class="movie-content">
                <div class="top">
                    <h6 class="mt-0">{{ $title }}</h6>
                </div>
            </div>
        </div>

    @elseif($item->content_group === 'tv' || $item->content_group === 'video')
        <div class="movie-item">
            <div class="movie-poster">
                <a href="{{ $link }}">
                    <img src="{{ $thumbnail }}" class="w-100 d-block" alt="{{ $title }}" style="object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
                    <div class="play fs-40">
                        <i class="fadeIn animated bx bx-play-circle"></i>
                    </div>
                </a>
            </div>
            <div class="movie-content">
                <div class="top">
                    <h6 class="mt-0">
                        <a href="{{ $link }}">{{ $title }}</a>
                    </h6>
                </div>
            </div>
        </div>

    @elseif($item->content_group === 'podcast')
        <div class="movie-card">
            <div class="movie-poster">
                <a href="{{ $link }}">
                    <img src="{{ $thumbnail }}" class="movie-img" alt="{{ $title }}" loading="lazy">
                    <div class="play-icon">
                        <i class="bx bx-play-circle"></i>
                    </div>
                </a>
            </div>
        </div>

    @else
        <div class="movie-item">
            <div class="movie-poster">
                <img src="{{ $thumbnail }}" class="w-100 d-block" alt="{{ $title }}" style="object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
            </div>
            <div class="movie-content">
                <div class="top">
                    <h6 class="mt-0">{{ $title }}</h6>
                </div>
            </div>
        </div>
    @endif
</div>
