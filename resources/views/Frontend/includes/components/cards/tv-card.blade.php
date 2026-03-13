<div class="col-xl-2 col-lg-2 col-6 grid-item grid-sizer">
               <div class="movie-card">

    <!-- Poster -->
    <div class="movie-poster radio-poster">
        <a href="{{ route('tv.show',  $tv->slug) }}">
            <img src="{{ $tv->thumbnail_url ?? asset('assets/img/default.png') }}"
                class="movie-img" alt="{{ $tv->title  }}" loading="lazy">
            <div class="play-icon">
                <i class="bx bx-play-circle"></i>
            </div>
        </a>
    </div>

</div>
</div>