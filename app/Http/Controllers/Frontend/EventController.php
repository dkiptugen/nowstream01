<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Event;
use App\Models\Content;
use App\Models\ContentRate;
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
        $events = Event::with(['eventRates' => function ($q) {
            $q->orderBy('price', 'asc');
        }])->where('status', 1)->orderBy('created_at', 'desc')->get();
        $topevents = Event::with(['eventRates' => function ($q) {
            $q->orderBy('price', 'asc');
        }])->where('status', 1)->orderByDesc('views')->get();

        return view('Frontend.modules.events.index', compact('events', 'topevents'));
    }

    /**
     * Display payment plans for a specific event and rate.
     */
    public function pay(Request $request, $eventId, $rateId)
    {
        try {
            $event = Cache::rememberOnce(
                'event_' . $eventId,
                now()->addDay(),
                fn() => $this->get_events($eventId)
            );

            $rate = Cache::rememberOnce(
                'rates_' . $eventId . '_' . $rateId,
                now()->addDay(),
                fn() => $this->get_event_rates($eventId, $rateId)
            );

            if (is_null($rate)) {
                return redirect()->back()->with('error', 'Event rate not found.');
            }

            $user   = Auth::user();
            $events = Cache::rememberOnce('events', now()->addDay(), fn() => $this->get_events());
            $videos = Cache::rememberOnce('videos', now()->addDay(), fn() => $this->get_videos());

            return view('Frontend.modules.payments.plans', compact('event', 'rate', 'user', 'events', 'videos'));
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
        $eventId = Event::where('slug', $slug)->first();
        $event  = $this->get_event($eventId, $slug);
        if (!$event) abort(404, 'Event not found.');
        // increment views
        
        $event->increment('views');
        $events = $this->get_events($eventId);
        $videos = $this->get_videos();
        $rates  = $this->get_event_ticket_rates($eventId);

        return view('Frontend.modules.events.event', compact('event', 'events', 'videos', 'rates'));
    }
}
