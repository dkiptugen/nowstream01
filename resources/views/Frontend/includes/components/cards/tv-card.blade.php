<div class="col-6 col-sm-4 col-lg-3 col-xl-2 grid-item grid-sizer nowstream-grid-card">
               <div class="movie-card nowstream-media-card">

    <!-- Poster -->
    <div class="movie-poster radio-poster">
        <a href="{{ route('tv.show',  $tv->slug) }}">
            <img src="{{ $tv->thumbnail_url ?? asset('assets/img/default.png') }}"
                class="movie-img nowstream-media-card__image" alt="{{ $tv->title  }}" loading="lazy" style="aspect-ratio: 1/1; object-fit: cover;">
            <div class="play-icon">
                <i class="bx bx-play-circle"></i>
            </div>
        </a>
    </div>

</div>
</div>
