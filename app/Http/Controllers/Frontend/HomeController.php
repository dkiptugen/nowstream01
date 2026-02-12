<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Event;
use App\Models\ContentRate;
use App\Models\Content;
use App\Models\Video;
use App\Traits\CacheHelper;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    use CacheHelper;

    /**
     * Display the homepage with cached channels, streams, events, and videos.
     */
  public function index()
{
    // Channels
    $this->data['channels'] = $this->get_channels();

    // Streams (excluding a specific UUID if needed)
    $this->data['streams'] = $this->get_streams(null, 6);

    // Events
    $this->data['events'] = $this->get_events();

    // Latest videos (limit 6)
    $this->data['videos'] = $this->get_videos(6);

    // Current event — just the latest one
    $this->data['current_event'] = Content::latest()->limit(1)->get();

    // Top videos by views — not cached (optional: can cache using helper)
    $this->data['top_videos'] = Content::where('type', 'video')
        ->orderBy('views', 'desc')
        ->limit(4)
        ->get();

    // Podcasts (limit 4)
    $this->data['podcasts'] = $this->get_podcasts(4);

    return view('Frontend.index', $this->data);
}


    /**
     * Display the landing page with cached data.
     */
    public function landing()
    {
        $this->data['channels'] = Cache::rememberOnce('channels', now()->addDay(), $this->get_channels());
        $this->data['streams'] = Cache::rememberOnce('streams', now()->addDay(), $this->get_streams());
        $this->data['events'] = Cache::rememberOnce('events', now()->addDay(), $this->get_events());
        $this->data['videos'] = Cache::rememberOnce('videos', now()->addDay(), $this->get_videos());

        $this->data['current_event'] = Cache::rememberOnce('event_1', now()->addDay(), fn() => $this->get_events(1));

        $this->data['rates'] = $this->data['current_event']
            ? Cache::rememberOnce('rates_' . $this->data['current_event']->id, now()->addDay(), fn() => $this->get_event_rates($this->data['current_event']->id))
            : collect();

        return view('Frontend.landing', $this->data);
    }

    /**
     * Display the "Israel" page with specific channels, streams, events, videos, and rates.
     */
    public function israel()
    {
        $this->data['channels'] = Channel::where('status', 1)->take(12)->get();
        $this->data['streams'] = Content::latest()->take(4)->get();
        $this->data['events'] = Event::where('status', 1)->take(4)->get();
        $this->data['videos'] = Content::where('type', 'video')->take(4)->get();

        $currentEvent = Event::find(7);
        $this->data['current_event'] = $currentEvent;
        $this->data['rates'] = $currentEvent
            ? ContentRate::where('event_id', $currentEvent->id)->take(5)->get()
            : collect();

        return view('Frontend.israel', $this->data);
    }

    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('Frontend.terms');
    }
}
