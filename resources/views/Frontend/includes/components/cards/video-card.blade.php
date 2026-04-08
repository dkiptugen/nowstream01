                    <div class="col-12 col-sm-6 col-lg-4 grid-item grid-sizer nowstream-grid-card">
                          @php 
                        $thumbnail = $video->thumbnail_url ? Storage::disk(config('filesystems.default'))->url($video->thumbnail_url) : asset('frontend-assets/images/default.png');
                    @endphp
                        <div class="movie-item mb-4 mb-lg-5 shadow-sm bg-dark nowstream-media-card">
                            <div class="movie-poster mb-0">
                                <a href="{{ route('video.show', ['uuid' => $video->uuid, 'slug' => $video->slug]) }}">
                                    <img src="{{ $thumbnail }}"
                                         alt="{{ $video->title }}"
                                         class="w-100 d-block nowstream-media-card__image"
                                         style="object-fit: cover; aspect-ratio: 16/9;"
                                         loading="lazy">
                                    <div class="play fs-40">
                                        <i class="fadeIn animated bx bx-play-circle"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="movie-content p-3">
                                <div class="top">
                                    <h5 class="title mt-0 nowstream-media-card__title">
                                        <a href="{{ route('video.show', ['uuid' => $video->uuid, 'slug' => $video->slug]) }}">
                                            {{ ucfirst($video->title) }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="bottom nowstream-media-card__meta">
                                    <ul>
                                        <li><span class="quality">hd</span></li>
                                        <li>
                                            <span class="channel"><i class="far fa-user"></i> {{ $channel ? $channel->name : 'Unknown' }}</span>
                                            <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                            <span class="views ms-2"><i class="fas fa-eye"></i> {{ $video->views ?? 0 }} views</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
