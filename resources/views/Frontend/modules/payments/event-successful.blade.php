@extends('Frontend.includes.layout')
@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mx-auto mt-4">
                        <div class="card w-100 radius-10 mt-4" style="background: #f7f7f7;">
                            <div class="card-body text-center text-success">
                                @php
                                    $product = optional($accessOrder?->items->first())->product;
                                    $isStreamPurchase = optional($product)->type === 'content';
                                @endphp
                                <h1 class="text-center text-success mt-2">Payment Successful!</h1>
                                <img src="{{ asset('/success.png') }}" height="150" width="150" alt="">
                                <p class="mt-3 mb-0 text-dark">
                                    @if($isStreamPurchase)
                                        Your stream access for <strong>{{ $event->event_name }}</strong> is now active.
                                    @else
                                        Your ticket for <strong>{{ $event->event_name }}</strong> is ready.
                                    @endif
                                </p>
                                <div class="text-center my-4">
                                    @if($ticket)
                                        <a target="_blank" href="{{ route('ticket.download', ['uuid' => $ticket->uuid]) }}" class="btn btn-dark">
                                            Download Ticket
                                        </a>
                                    @elseif($isStreamPurchase && $eventStream)
                                        <a href="{{ route('stream.show', ['uuid' => $eventStream->uuid, 'slug' => $eventStream->slug]) }}" class="btn btn-dark">
                                            Watch Stream
                                        </a>
                                    @endif
                                    <a href="{{ route('event.show', ['slug' => $event->slug]) }}" class="btn btn-outline-dark ms-2">
                                        Back to Event
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('header')
<style>
    .header-wrapper,
    .page-footer {
        display: none;
    }

    .overlay {
        display: none !important;
    }
</style>
@endsection
