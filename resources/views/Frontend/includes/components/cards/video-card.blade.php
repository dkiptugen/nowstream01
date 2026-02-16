
                    <div class="col-xl-4 col-lg-4 col-sm-6 grid-item grid-sizer">
                        <div class="movie-item mb-60 shadow-sm bg-dark">
                            <div class="movie-poster mb-0">
                                <a href="{{ route('video.show', ['uuid' => $video->uuid, 'slug' => $video->slug]) }}">
                                    <img src="{{ $thumbnail }}"
                                         alt="{{ $video->title }}"
                                         class="w-100 d-block"
                                         style="object-fit: cover; aspect-ratio: 16/9;"
                                         loading="lazy">
                                    <div class="play fs-40">
                                        <i class="fadeIn animated bx bx-play-circle"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="movie-content p-2">
                                <div class="top">
                                    <h5 class="title mt-0">
                                        <a href="{{ route('video.show', ['uuid' => $video->uuid, 'slug' => $video->slug]) }}">
                                            {{ ucfirst($video->title) }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="bottom">
                                    <ul>
                                        <li><span class="quality">hd</span></li>
                                        <li>
                                            <span class="channel"><i class="far fa-user"></i> {{ $channel ? $channel->name : 'Unknown' }}</span>
                                            <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                            <span class="views ml-2"><i class="fas fa-eye"></i> {{ $video->views ?? 0 }} views</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>