
@if(isset($tvs) && $tvs->count())
    @foreach($tvs as $tv) 
    @include('Frontend.includes.components.cards.tv-card')  
    @endforeach
@endif
