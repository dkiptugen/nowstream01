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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
            $event = Cache::remember(
                'event_checkout_' . $eventId,
                now()->addMinutes(10),
                fn() => Event::where('uuid', $eventId)->where('status', 1)->firstOrFail()
            );

            $rate = Cache::remember(
                'event_rate_' . $eventId . '_' . $rateId,
                now()->addMinutes(10),
                fn() => Product::query()
                    ->whereKey($rateId)
                    ->where('payable_id', $eventId)
                    ->where('payable_type', Event::class)
                    ->where('type', 'ticket')
                    ->where('is_active', 1)
                    ->first()
            );

            if (is_null($rate)) {
                return redirect()->back()->with('error', 'Event rate not found.');
            }

            $user   = Auth::user();
            $ticket = Ticket::where('user_id', $user->id)
                ->where('event_id', $event->uuid)
                ->latest()
                ->first();
            $paidOrder = Order::query()
                ->where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->whereHas('items.product', fn($query) => $query
                    ->where('payable_id', $event->uuid)
                    ->where('payable_type', Event::class))
                ->latest('paid_at')
                ->first();

            if ($ticket || $paidOrder) {
                return redirect()
                    ->route('event.success', ['eventId' => $event->uuid])
                    ->with('success', 'You already have a paid ticket for this event.');
            }

            $events = Cache::rememberOnce('events', now()->addDay(), fn() => $this->get_events());
            $videos = Cache::rememberOnce('videos', now()->addDay(), fn() => $this->get_videos());
            $country = session('country', 'US');

            return view('Frontend.modules.payments.plans', compact('event', 'rate', 'user', 'events', 'videos', 'country'));
        } catch (\Exception $e) {
            abort(404, 'Event not found.');
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
        $event = Cache::rememberOnce(
            'event_' . $eventId,
            now()->addDay(),
            fn() => $this->get_events($eventId)
        );

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
                ->where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->whereHas('items.product', fn($query) => $query
                    ->where('payable_id', $data['event']->uuid)
                    ->where('payable_type', Event::class))
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
