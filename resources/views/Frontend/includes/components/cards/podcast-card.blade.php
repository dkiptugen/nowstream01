
<div class="col-xl-2 col-lg-2 col-sm-6 grid-item grid-sizer">
    <div class="movie-item mb-60">
        <div class="movie-poster">
            <a href="{{ route('podcast.show', ['uuid' => $podcast->uuid, 'slug' => $podcast->slug]) }}">
                <img src="{{ $podcast->thumbnail_url }}" class="img-fluid" alt="{{ $podcast->title }}">
            </a>

            <h5 class="card-title mb-0 mt-3">
                    <a href="{{ route('podcast.show', ['uuid' => $podcast->uuid, 'slug' => $podcast->slug]) }}">
                       <b>{{ $podcast->title }}</b>
                    </a>
            </h5>
        </div>

        <div class="movie-content mt-3">
            <div class="top">
                <small class=" mb-0">
               {{ $podcast->author }}
                </small>

                <span class="date">
                    <small class="card-text">
                        <i class="fas fa-clock"></i>
                        {{ $podcast->duration ? $podcast->duration . ' mins' : '' }}
                    </small>
                </span>
            </div>
 
        </div>
    </div>
</div>
