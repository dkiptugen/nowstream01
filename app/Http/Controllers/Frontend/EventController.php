<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Content;
use App\Models\Order;
use App\Traits\CacheHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    use CacheHelper;

    /**
     * Display a listing of all active events.
     */
    public function index()
    {
        $events = Event::where('status', 1)->orderBy('created_at', 'desc')->get();
        $events->load('eventRates');
        $topevents = Event::where('status', 1)
                          ->orderByDesc('views')
                          ->get();
        $topevents->load('eventRates');


        return view('Frontend.modules.events.index', compact('events', 'topevents'));
    }

    /**
     * Display payment plans for a specific event and rate.
     */
    public function pay(Request $request, $eventId, $rateId)
    {
        try {
            $event = Event::where('uuid', $eventId)->where('status', 1)->firstOrFail();
            $rate = $event->eventRates()->whereKey($rateId)->first();

            if (is_null($rate)) {
                return redirect()
                    ->route('event.show', ['slug' => $event->slug])
                    ->with('error', 'That ticket option is no longer available.');
            }

            $user = Auth::user();
            $paidOrder = Order::query()
                ->forPaidEvent($user->id, $event->uuid)
                ->latest('paid_at')
                ->first();

            $ticket = null;
            if (!$paidOrder) {
                $ticket = Ticket::where('user_id', $user->id)
                    ->where('event_id', $event->uuid)
                    ->latest()
                    ->first();
            }

            if ($ticket || $paidOrder) {
                return redirect()
                    ->route('event.success', ['eventId' => $event->uuid])
                    ->with('success', 'You already have a paid ticket for this event.');
            }

            $events = $this->get_events();
            $videos = $this->get_videos();
            $country = session('country', 'US');

            return view('Frontend.modules.payments.plans', compact('event', 'rate', 'user', 'events', 'videos', 'country'));
        } catch (ModelNotFoundException $e) {
            abort(404, 'Event not found.');
        } catch (\Throwable $e) {
            Log::error('Event payment page failed.', [
                'event_id' => $eventId,
                'rate_id' => $rateId,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('events')
                ->with('error', 'Unable to load the payment page right now.');
        }
    }

    /**
     * Display Mpesa payment page.
     */
    public function mpesa()
    {
        try {
            $user = Auth::user();
            return view('Frontend.modules.payments.mpesa', compact('user'));
        } catch (\Exception $e) {
            abort(404, 'Event not found.');
        }
    }

    /**
     * Show success page after successful payment.
     */
    public function succeed($eventId)
    {
        $event = Event::where('uuid', $eventId)->where('status', 1)->firstOrFail();

        return view('Frontend.modules.payments.success', compact('event'));
    }

    /**
     * Display details for a single event.
     */

    public function show($slug)
    {
        // Cache key per event page
        $cacheKey = "event_page_{$slug}";

        $ticket = Auth::check()
            ? Ticket::where('user_id', Auth::id())->whereHas('event', fn($query) => $query->where('slug', $slug))->latest()->first()
            : null;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($slug) {
            $event = Event::where('slug', $slug)->firstOrFail();
            $eventId = $event->uuid;
            $event->load('eventRates'); // Load rates with the event 

            return [
                'event'  => $event,
                'events' => $this->get_events($eventId),
                'videos' => $this->get_videos(),
                'rates'  => $this->get_event_ticket_rates($eventId),

            ];
        });

        if (!$data) {
            abort(404, 'Event not found.');
        }

        // Increment views dynamically (not cached)
        Event::where('uuid', $data['event']->uuid)->increment('views');

        // Related events (cached separately)
        $relatedEvents = Cache::remember("related_events_{$data['event']->uuid}", now()->addDay(), function () use ($data) {
            return Content::where('status', 1)
                ->where('uuid', '<>', $data['event']->uuid)
                ->where('content_group', 'event')
                ->take(4)
                ->get();
        }); 
        // dd eventRates
        //  dd($data['event']->eventRates);
        $data['event']->eventRates = $data['event']->eventRates->sortBy('price')->values()->all();
        $paidOrder = Auth::check()
            ? Order::query()
                ->forPaidEvent(Auth::id(), $data['event']->uuid)
                ->latest('paid_at')
                ->first()
            : null;


        return view('Frontend.modules.events.event', [
            'event'          => $data['event'],
            'events'         => $data['events'],
            'videos'         => $data['videos'],
            'rates'          => $data['rates'],
            'ticket'         => $ticket,
            'eventRates'     => $data['event']->eventRates,
            'relatedEvents'  => $relatedEvents,
            'paidOrder'      => $paidOrder,
        ]);
    }
}
