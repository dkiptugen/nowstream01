<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Event;
use App\Models\EventRate;
use App\Models\Stream;
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
        $this->data['channels'] = Cache::rememberOnce('channels', now()->addDay(), $this->get_channels());
        $this->data['streams'] = Cache::rememberOnce('streams_not_6', now()->addDay(), $this->get_streams(null, 6));
        $this->data['events'] = Cache::rememberOnce('events', now()->addDay(), $this->get_events());
        $this->data['videos'] = Cache::rememberOnce('videos', now()->addDay(), $this->get_videos());
        $this->data['current_event'] = Stream::latest()->take(1)->get();
        $this->data['top_videos'] = Video::orderBy('views', 'DESC')->take(4)->get();

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
        $this->data['streams'] = Stream::latest()->take(4)->get();
        $this->data['events'] = Event::where('status', 1)->take(4)->get();
        $this->data['videos'] = Video::take(4)->get();

        $currentEvent = Event::find(7);
        $this->data['current_event'] = $currentEvent;
        $this->data['rates'] = $currentEvent
            ? EventRate::where('event_id', $currentEvent->id)->take(5)->get()
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
