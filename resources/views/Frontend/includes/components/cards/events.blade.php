@php
use Carbon\Carbon;

$startDate = Carbon::parse($event->start_time);
$endTime = Carbon::parse($event->end_time);

$tickets = $event->tickets ?? collect(); // fallback to empty collection

$hasPaidTickets = $tickets->count() > 0;
$freeStream = !$hasPaidTickets;

$ticket = $tickets->sortBy('price')->first();

$url = $freeStream
? route('event.show', ['eventId' => $event->uuid, 'slug' => $event->slug])
: route('event.show', ['eventId' => $event->uuid, 'slug' => $event->slug]);
$item = $event;
$thumbnail = $event->event_image ? Storage::disk(config('filesystems.default'))->url($event->event_image) : asset('frontend-assets/images/default.png');
@endphp

@extends('Frontend.includes.components.cards.slider-card')