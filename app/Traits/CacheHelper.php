<?php

namespace App\Traits;

use App\Models\Channel;
use App\Models\Event;
use App\Models\Content;
use App\Models\Microsite;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

trait CacheHelper
{
    /**
     * Get channels, optionally a single channel by ID.
     */
    public function get_channels($id = null)
    {
        return Cache::tags(['microsites', 'contents'])->remember("microsites_{$id}", now()->addDay(), function () use ($id) {
            if (is_null($id)) {
                return Microsite::with(['contents'])
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            return Microsite::with([ 'contents'])->find($id);
        });
    }

    /**
     * Get a single event by ID and slug.
     */
    public function get_event($id, $slug)
    {
        return Cache::tags(['event'])->remember("event_{$id}_{$slug}", now()->addDay(), function () use ($id, $slug) {
            return Event::where('uuid', $id)
                ->where('slug', $slug)
                ->where('status', 1)
                ->with(['eventRates' => fn($q) => $q->orderBy('price', 'asc')])
                ->first();
        });
    }

    /**
     * Get multiple events, optionally excluding one event.
     */
    public function get_events($excludeId = null)
    {
        $key = $excludeId ? "events_except_{$excludeId}" : "events_all";

        return Cache::tags([ 'events'])->remember($key, now()->addDay(), function () use ($excludeId) {
            $query = Event::where('status', 1)->orderBy('created_at', 'desc');
            if ($excludeId) {
                $query->where('uuid', '!=', $excludeId);
            }
            return $query->get();
        });
    }

    /**
     * Get latest videos with optional limit.
     */
    public function get_videos($limit = 6)
    {
        return Cache::tags(['videos', 'contents'])->remember("latest_videos_{$limit}", now()->addDay(), function () use ($limit) {
            return Content::where('content_group', 'video')
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get latest podcasts with optional limit.
     */
    public function get_podcasts($limit = 6)
    {
        return Cache::tags(['podcasts', 'contents'])->remember("latest_podcasts_{$limit}", now()->addDay(), function () use ($limit) {
            return Content::where('content_group', 'podcast')
                ->latest()
                ->take($limit)
                ->get();
        });
    }
    /**
     * Get latest tvs with optional limit.
     */
    public function get_tvs($limit = 6)
    {
        return Cache::tags(['tvs', 'contents'])->remember("latest_tvs_{$limit}", now()->addDay(), function () use ($limit) {
            return Content::where('content_group', 'tv')
                ->latest()
                ->take($limit)
                ->get();
        });
    }
    /**
     * Get latest radios with optional limit.
     */
    public function get_radios($limit = 6)
    {
        return Cache::tags(['radios', 'contents'])->remember("latest_radios_{$limit}", now()->addDay(), function () use ($limit) {
            return Content::where('content_group', 'radio')
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get ticket-type products for an event.
     */
    public function get_event_ticket_rates($eventId)
    {
        return Cache::tags(['events', 'tickets'])->remember("event_{$eventId}_tickets", now()->addDay(), function () use ($eventId) {
            return Product::where('payable_id', $eventId)
                ->where('payable_type', Event::class)
                ->where('type', 'ticket')
                ->where('is_active', 1)
                ->orderBy('price', 'asc')
                ->get();
        });
    }

  	public function get_streams ($uuid = null, $not = 0)
				{
					if (is_null ($uuid))
						{
							$stream = Content::when ($not != 0, function ($query) use ($not)
								{
									return $query->where ('uuid', '!=', $not);
								})->orderBy ("created_at", "asc")->where('content_group', 'livestream')->get ()
							;
						}
					else
						{
							$stream = Content::where('uuid', $uuid)->first();
						}
					return $stream;
				}
		}
