@if ($item->content_group === 'radio')

    <div class="movie-item" onclick="playSingleAudio(
            '{{ $item->stream_url }}',
            '{{ addslashes($item->title) }}',
            'Live Radio',
            '{{ $item->thumbnail_url ?? asset('assets/img/default-thumbnail.jpg') }}'
         )" style="cursor:pointer;">

        <div class="movie-poster">
            <img src="{{$item->thumbnail_url ?? asset('frontend-assets/images/default.png')}}" class="w-100 d-block w-100"
                alt="{{ $item->title  }}" style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
            <div class="play fs-40">
                <i class="fadeIn animated bx bx-play-circle"></i>
            </div>
        </div>
        <div class="movie-content">
            <div class="top">
                <h6 class=" mt-0">
                    {{ucfirst($item->title)}}
                </h6>
            </div>
        </div>
    </div>
@elseif ($item->content_group === 'tv')
    <div class="movie-item">

        <div class="movie-poster">
            <a href="{{ route($item->content_group . '.show', [$item->uuid, $item->slug]) }}">
                <img src="{{$item->thumbnail_url ?? asset('frontend-assets/images/default.png')}}"
                    class="w-100 d-block w-100" alt="{{ $item->title  }}" style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
                <div class="play fs-40">
                    <i class="fadeIn animated bx bx-play-circle"></i>
                </div>
            </a>
        </div>
        <div class="movie-content">
            <div class="top">
                <h6 class=" mt-0">
                    <a href="{{ route($item->content_group . '.show', [$item->uuid, $item->slug]) }}">
                        {{ucfirst($item->title)}}
                    </a>
                </h6>
            </div>
        </div>
    </div>
@elseif ($item->content_group === 'podcast') 
    <div class="movie-card">

        <!-- Poster -->
        <div class="movie-poster">
            <a href="{{ route($item->content_group . '.show', [$item->uuid, $item->slug]) }}">
                <img src="{{ $item->thumbnail_url ?? asset('frontend-assets/images/default.png') }}"
                     class="movie-img" alt="{{ $item->title  }}" loading="lazy">
                <div class="play-icon">
                    <i class="bx bx-play-circle"></i>
                </div>
            </a>
        </div>

    </div> 
@elseif ($item->content_group === 'video')
    <div class="movie-item">

        <div class="movie-poster">
            <a href="{{ route($item->content_group . '.show', [$item->uuid, $item->slug]) }}">
                <img src="{{$item->thumbnail_url ?? asset('frontend-assets/images/default.png')}}"
                    class="w-100 d-block w-100" alt="{{ $item->title  }}" style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
                <div class="play fs-40">
                    <i class="fadeIn animated bx bx-play-circle"></i>
                </div>
            </a>
        </div>
        <div class="movie-content">
            <div class="top">
                <h6 class=" mt-0">
                    <a href="{{ route($item->content_group . '.show', [$item->uuid, $item->slug]) }}">
                        {{ucfirst($item->title)}}
                    </a>
                </h6>
            </div>
        </div>
    </div>
@else
    <div class="movie-item">
        <div class="movie-poster">
            <img src="{{$item->thumbnail_url ?? asset('frontend-assets/images/default.png')}}" class="w-100 d-block w-100"
                alt="{{ $item->title  }}" style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
        </div>
        <div class="movie-content">
            <div class="top">
                <h6 class=" mt-0">
                    {{ucfirst($item->title)}}
                </h6>
            </div>
        </div>
    </div>
@endif