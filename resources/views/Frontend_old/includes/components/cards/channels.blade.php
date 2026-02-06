<div class="card radius-5 h-100 border">
    <div class="circle-image text-center">
        <a href="{{ url("/channel/{$channel->id}/{$channel->name}") }}">
            <img src="{{$channel->thumbnail}}" class="w-100 mx-auto d-block my-3 aspect1 bg-dark shadow" alt="{{$channel->name}}">
        </a>
        @if(Auth::check())
            <div id="subscription-controls-{{ $channel->id }}">
                @if(Auth::user()->subscribedChannels->contains($channel->id))
                    <div id="subscribe-bt n-{{ $channel->id }}">
                        <button class="btn btn-danger btn-sm"
                            onclick="toggleSubscription({{ $channel->id }}, false)">Unsubscribe</button>
                    </div>
                @else
                    <div id="subscribe-btn-{{ $channel->id }}">
                        <button class="btn btn-outline-primary btn-sm"
                            onclick="toggleSubscription({{ $channel->id }}, true)">Subscribe</button>
                    </div>
                @endif
            </div>
        @endif
    </div>
    <div class="card-body text-center pb-0">
        <a href="{{ url("/channel/{$channel->id}/{$channel->name}") }}">
            <strong>
                {{ $channel->name }}
            </strong>
        </a>
        <br>
        <small class="text-muted">
            <i class="lni lni-video"></i>
            @php
                $videoCount = \App\Models\Content::where('type', 'video')->where('channel_id', $channel->id)->count();
            @endphp
            {{ $videoCount }} Videos

            <div>
                <span>
                    <i class="lni lni-user"></i> <span id="subscriber-count-{{ $channel->id }}">
                        {{ $channel->subscribers()->count() }}
                    </span> Subscribers
                </span>
            </div>
        </small>
    </div>
</div>