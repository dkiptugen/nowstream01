@foreach($videos as $video)
    @php
        $channel = \App\Models\Channel::find($video->channel_id);
    @endphp
    @include('Frontend.includes.components.cards.video-card')
@endforeach
