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
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{
    use CacheHelper;

    /**
     * Display a listing of all active events.
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $events = Cache::remember("events:index:page:{$page}", now()->addMinutes(10), function () {
            return Event::where('status', 1)
                ->with('tickets')
                ->latest('created_at')
                ->paginate(12);
        });
        $events->appends($request->all());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Frontend.includes.components.partials.event-items', compact('events'))->render(),
                'hasMore' => $events->hasMorePages(),
                'nextPageUrl' => $events->nextPageUrl(),
            ]);
        }

        $topevents = Cache::remember('events:top', now()->addMinutes(10), function () {
            return Event::where('status', 1)
                ->with('tickets')
                ->orderByDesc('views')
                ->limit(20)
                ->get();
        });


        return view('Frontend.modules.events.index', compact('events', 'topevents'));
    }

    /**
     * Display payment plans for a specific event and rate.
     */
    public function pay(Request $request, $eventId, $rateId)
    {
        try {
            $event = Event::where('uuid', $eventId)->where('status', 1)->firstOrFail();
            $rate = $event->products()
                ->active()
                ->whereKey($rateId)
                ->whereIn('type', ['ticket', 'content'])
                ->first();

            if (is_null($rate)) {
                return redirect()
                    ->route('event.show', ['slug' => $event->slug])
                    ->with('error', 'That purchase option is no longer available.');
            }

            $user = Auth::user();
            $paidOrder = Order::query()
                ->forPaidEventProductType($user->id, $event->uuid, $rate->type)
                ->latest('paid_at')
                ->first();

            $ticket = null;
            if ($rate->type === 'ticket' && !$paidOrder) {
                $ticket = Ticket::where('user_id', $user->id)
                    ->where('event_id', $event->uuid)
                    ->latest()
                    ->first();
            }

            if ($ticket || $paidOrder) {
                if ($rate->type === 'content') {
                    $eventStream = $event->streams()
                        ->where('content_group', 'livestream')
                        ->where('status', 1)
                        ->first();

                    if ($eventStream) {
                        return redirect()
                            ->route('stream.show', ['uuid' => $eventStream->uuid, 'slug' => $eventStream->slug])
                            ->with('success', 'You already have stream access for this event.');
                    }
                }

                return redirect()
                    ->route('event.success', ['eventId' => $event->uuid])
                    ->with('success', $rate->type === 'ticket'
                        ? 'You already have a paid ticket for this event.'
                        : 'You already have stream access for this event.');
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
        $cacheKey = "event_page_v2_{$slug}";

        $ticket = Auth::check()
            ? Ticket::where('user_id', Auth::id())->whereHas('event', fn($query) => $query->where('slug', $slug))->latest()->first()
            : null;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($slug) {
            $event = Event::where('slug', $slug)->firstOrFail();
            $eventId = $event->uuid;
            $event->load(['eventRates', 'streamRates', 'merchProducts']);
            $eventStream = $event->streams()
                ->where('content_group', 'livestream')
                ->where('status', 1)
                ->first();

            return [
                'event'  => $event,
                'events' => $this->get_events($eventId),
                'videos' => $this->get_videos(),
                'rates'  => $this->get_event_ticket_rates($eventId),
                'stream' => $eventStream,
                'merch'  => $event->merchProducts,

            ];
        });

        if (!$data) {
            abort(404, 'Event not found.');
        }

        if (!array_key_exists('stream', $data)) {
            $data['stream'] = $data['event']->streams()
                ->where('content_group', 'livestream')
                ->where('status', 1)
                ->first();
        }

        if (!array_key_exists('merch', $data)) {
            $data['merch'] = $data['event']->merchProducts()->get();
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
        $data['event']->eventRates = $data['event']->eventRates->sortBy('price')->values();
        $data['event']->streamRates = $data['event']->streamRates->sortBy('price')->values();
        $data['event']->merchProducts = collect($data['merch'])->values();
        $paidOrder = Auth::check()
            ? Order::query()
                ->forPaidEvent(Auth::id(), $data['event']->uuid)
                ->latest('paid_at')
                ->first()
            : null;
        $paidStreamOrder = Auth::check()
            ? Order::query()
                ->forPaidEventProductType(Auth::id(), $data['event']->uuid, 'content')
                ->latest('paid_at')
                ->first()
            : null;
        $legacyStreamAccess = null;
        if (Auth::check() && Schema::hasTable('subscriptions')) {
            $legacyStreamAccess = \App\Models\Subscription::where('user_id', Auth::id())
                ->where('event_id', $data['event']->uuid)
                ->where('status', 1)
                ->where('type', 'stream')
                ->latest()
                ->first();
        }


        return view('Frontend.modules.events.event', [
            'event'          => $data['event'],
            'events'         => $data['events'],
            'videos'         => $data['videos'],
            'rates'          => $data['rates'],
            'ticket'         => $ticket,
            'eventRates'     => $data['event']->eventRates,
            'streamRates'    => $data['event']->streamRates,
            'merchProducts'  => $data['event']->merchProducts,
            'eventStream'    => $data['stream'],
            'relatedEvents'  => $relatedEvents,
            'paidOrder'      => $paidOrder,
            'paidStreamOrder'=> $paidStreamOrder,
            'legacyStreamAccess' => $legacyStreamAccess,
        ]);
    }
}
