<div class="col-xl-2 col-lg-2 col-sm-6 grid-item grid-sizer"
     onclick="playSingleAudio(
        '{{ $radio->stream_url }}',
        '{{ addslashes($radio->title) }}',
        'Live Radio',
        '{{ $radio->thumbnail_url ?? asset('assets/img/default-thumbnail.jpg') }}'
     )"
     style="cursor:pointer;"> 
                <div class="movie-item mb-60">
                    <div class="movie-poster">
                        
                            <img src="{{$radio->thumbnail_url ?? asset('frontend-assets/images/default.png')}}"
                                class="w-100 d-block w-100" alt="..." style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
                            <div class="play fs-40">
                                <i class="fadeIn animated bx bx-play-circle"></i>
                            </div> 
                    </div>
                    <div class="movie-content">
                        <div class="top">
                            <h6 class=" mt-0"> 
                                    {{ucfirst($radio->title)}} 
                            </h6>
                        </div>
                        <div class="bottom"> 

 
                            <ul>
                                <li>
                                    <span class="channel"><i class="far fa-user"></i>
                                        {{ $radio->author ? $radio->author : 'Unknown' }}</span>
                                    <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>