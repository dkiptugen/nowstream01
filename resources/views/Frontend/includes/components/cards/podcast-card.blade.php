<div class="col-xl-2 col-lg-2 col-sm-6 grid-item grid-sizer">
               <div class="movie-card">

    <!-- Poster -->
    <div class="movie-poster radio-poster">
        <a href="{{ route('podcast.show',  $podcast->slug) }}">
            <img src="{{ $podcast->thumbnail_url ?? asset('assets/img/default.png') }}"
                class="movie-img" alt="{{ $podcast->title  }}" loading="lazy">
            <div class="play-icon">
                <i class="bx bx-play-circle"></i>
            </div>
        </a>
    </div>

</div>
</div>