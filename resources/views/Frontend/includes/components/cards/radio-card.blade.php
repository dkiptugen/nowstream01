<div class="w-100"
     onclick="playSingleAudio(
        '{{ $item->stream_url }}',
        '{{ addslashes($item->title) }}',
        'Live item',
        '{{ $item->thumbnail_url ?? asset('assets/img/default-thumbnail.jpg') }}'
     )"
     style="cursor:pointer;"> 
                <div class="movie-item mb-60">
                    <div class="movie-poster radio-poster">
                        
                            <img src="{{$item->thumbnail_url ?? asset('frontend-assets/images/default.png')}}"
                                class="w-100 d-block w-100" alt="..." style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
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
            </div>