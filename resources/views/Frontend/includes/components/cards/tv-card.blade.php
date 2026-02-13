<div class="col-xl-2 col-lg-2 col-sm-6 grid-item grid-sizer">
                <div class="movie-item mb-60">
                    <div class="movie-poster">
                        <a href="{{ route('tv.show', [$tv->uuid, $tv->slug]) }}">
                            <img src="{{$tv->thumbnail_url ?? asset('frontend-assets/images/default.png')}}"
                                class="w-100 d-block w-100" alt="...">
                            <div class="play fs-40">
                                <i class="fadeIn animated bx bx-play-circle"></i>
                            </div>
                        </a>
                    </div>
                    <div class="movie-content">
                        <div class="top">
                            <h6 class=" mt-0">
                                <a href="{{ url("/tv/{$tv->id}/{$tv->slug}") }}">
                                    {{ucfirst($tv->title)}}
                                </a>
                            </h6>
                        </div>
                        <div class="bottom">
                            <!-- Display number of views -->

                            <ul>
                                <li>
                                    <span class="channel"><i class="far fa-user"></i>
                                        {{ $tv->author ? $tv->author : 'Unknown' }}</span>
                                    <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>