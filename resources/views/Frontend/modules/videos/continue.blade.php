@extends('Frontend.includes.layout')

@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <h4 class="section-title">Watched Videos</h4>
        @if($items->isEmpty())
        <p>You haven't watched any videos yet.</p>
        @else
        <div class="row">
            @foreach ($items as $item)
                        @include('Frontend.includes.components.cards.slider-card')
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $items->links() }}
        </div>
        @endif

    </div>
</div>
@endsection 