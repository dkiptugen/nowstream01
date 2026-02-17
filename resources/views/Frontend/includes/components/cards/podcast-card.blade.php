<div class="col-xl-2 col-lg-2 col-sm-6 grid-item grid-sizer">
                <div class="movie-item mb-60">
                    <div class="movie-poster">
                        <a href="{{ route('podcast.show',  $podcast->slug) }}">
                            <img src="{{$podcast->thumbnail_url ?? asset('frontend-assets/img/default.png')}}"
                                class="w-100 d-block w-100" alt="..." loading="lazy">
                            <div class="play fs-40">
                                <i class="fadeIn animated bx bx-play-circle"></i>
                            </div>
                        </a>
                    </div>
                    <div class="movie-content">
                        <!-- <div class="top">
                            <h6 class=" mt-0">
                                <a href="{{ url("/podcast/{$podcast->id}/{$podcast->slug}") }}">
                                    {{ucfirst($podcast->title)}}
                                </a>
                            </h6>
                        </div> -->
                        <div class="bottom">
                            <!-- Display number of views -->

                            <ul>
                                <li>
                                    <span class="channel"><i class="far fa-user"></i>
                                        {{ $podcast->author ? $podcast->author : 'Unknown' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>