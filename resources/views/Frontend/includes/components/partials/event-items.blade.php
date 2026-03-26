@foreach($events as $event)
    <div class="col-xl-3 col-lg-4 col-sm-6 grid-item grid-sizer">
        @include('Frontend.includes.components.cards.events')
    </div>
@endforeach
