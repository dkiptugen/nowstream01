<div class="card radius-10 shadow-sm h-100 border-0 overflow-hidden bg-dark">
    <div class="position-relative text-center">
        <a href="{{ url("/channel/{$channel->id}/{$channel->name}") }}">
            <img src="{{ $channel->thumbnail }}" 
                 class="rounded-circle mx-auto d-block my-3 shadow-lg" 
                 style="width:120px; height:120px; object-fit:cover;" 
                 alt="{{ $channel->name }}">
        </a>
    </div>

    <div class="card-body text-center">
        <a href="{{ url("/channel/{$channel->id}/{$channel->name}") }}" class="text-decoration-none text-dark">
            <h5 class="mb-1 fw-bold">{{ $channel->name }}</h5>
        </a>

        <div class="d-flex justify-content-center gap-3 mt-2">
            @php
                $videoCount = \App\Models\Content::where('type', 'video')->where('channel_id', $channel->id)->count();
                $subscriberCount = $channel->subscribers()->count();
            @endphp
            <span class="badge bg-primary">
                <i class="lni lni-video"></i> {{ $videoCount }} Videos
            </span>
            <span class="badge bg-success">
                <i class="lni lni-user"></i> {{ $subscriberCount }} Subscribers
            </span>
        </div>
    </div>
</div>
