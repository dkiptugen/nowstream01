
@if(isset($podcasts) && $podcasts->count())
    @foreach($podcasts as $podcast) 
    @include('Frontend.includes.components.cards.podcast-card')  
    @endforeach
@endif
