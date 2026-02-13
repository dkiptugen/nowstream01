@foreach($podcasts as $podcast)
<div class="col-md-4 mb-3">
    <div class="card">
        <img src="{{ $podcast->thumbnail_url }}" class="card-img-top" alt="{{ $podcast->title }}">
        <div class="card-body">
            <h5 class="card-title">{{ $podcast->title }}</h5>
            <p class="card-text">{{ Str::limit(strip_tags($podcast->description), 80) }}</p>
            <a href="{{ route('free.show', ['stream' => $podcast->uuid, 'slug' => $podcast->slug]) }}" class="btn btn-primary">Listen</a>
        </div>
    </div>
</div>
@endforeach
