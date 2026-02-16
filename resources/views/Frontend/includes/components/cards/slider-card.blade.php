 <div class="movie-item">
                    <div class="movie-poster">
                        <a href="{{ route($item->content_group . '.show', [$item->uuid, $item->slug]) }}">
                            <img src="{{$item->thumbnail_url ?? asset('frontend-assets/images/default.png')}}"
                                class="w-100 d-block w-100" alt="..." style=" object-fit: cover; aspect-ratio: 1/1;" loading="lazy">
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