@foreach($podcasts as $podcast)
    @include('Frontend.includes.components.cards.podcast-card', ['podcast' => $podcast])
@endforeach
