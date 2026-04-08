@foreach($events as $event)
    <div class="col-6 col-lg-4 col-xl-3 grid-item grid-sizer">
        @include('Frontend.includes.components.cards.events')
    </div>
@endforeach
