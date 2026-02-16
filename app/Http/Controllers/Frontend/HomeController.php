<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Event;
use App\Models\ContentRate;
use App\Models\Content;
use App\Models\Video;
use App\Traits\CacheHelper;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    use CacheHelper;

    /**
     * Display the homepage with cached channels, streams, events, and videos.
     */
    public function index(Request $request)
    {
        // --- Country handling ---
        $iso = strtoupper($request->country ?? '');
        $countryName = $this->getCountryNameByIso($iso);

        $this->data['country'] = $iso;
        $this->data['country_name'] = $countryName ?? 'Kenya';

        // Cache per country (affects only content that uses country)
        $cacheKey = 'homepage_' . ($iso ?: 'kenya');

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($countryName) {

            // Country filter (only for models that have country)
            $countryFilter = function ($query) use ($countryName) {
                if ($countryName) {
                    $query->where(function ($q) use ($countryName) {
                        $q->where('country', $countryName)
                            ->orWhereNull('country');
                    });
                }
            };

            return [

                // Channels
                'channels' => Channel::select('uuid', 'name', 'thumbnail')
                    ->where('status', 1)
                    ->limit(20)
                    ->get(),

                // Streams (live)
                'streams' => Content::select('uuid', 'title', 'slug', 'thumbnail_url', 'views')
                    ->where('content_group', 'livestream')
                    ->latest()
                    ->limit(6)
                    ->get(),

                // Events
                'events' => Content::select('uuid', 'title', 'slug', 'start_time')
                    ->where('content_group', 'event')
                    ->latest()
                    ->limit(6)
                    ->get(),

                // Latest videos
                'videos' => Content::with('channel:uuid,name')
                    ->select('uuid', 'title', 'slug', 'thumbnail_url', 'views', 'channel_id')
                    ->where('content_group', 'video')
                    ->latest()
                    ->limit(6)
                    ->get(),

                // Current event
                'current_event' => Content::select('uuid', 'title', 'slug')
                    ->where('content_group', 'event')
                    ->latest()
                    ->first(),

                // Top videos (country aware)
                'top_videos' => Content::select('uuid', 'title', 'slug', 'thumbnail_url', 'views')
                    ->where('content_group', 'video')
                    ->when($countryName, $countryFilter)
                    ->orderByDesc('views')
                    ->limit(14)
                    ->get(),

                // Top TVs (country aware)
                'toptvs' => Content::select(
                    'uuid',
                    'title',
                    'slug',
                    'stream_url',
                    'thumbnail as thumbnail_url', // alias
                    'views'
                )
                    ->where('content_group', 'tv')
                    ->whereNotNull('stream_url')
                    ->orderByDesc('views')
                    ->limit(16)
                    ->get(),



                // Top Radios (country aware)
                'topradios' => Content::select('uuid', 'title', 'slug', 'stream_url', 'views', 'thumbnail_url')
                    ->where('content_group', 'radio')
                    ->where('status', 1)
                    ->whereNotNull('stream_url')
                    ->when($countryName, $countryFilter)
                    ->orderByDesc('views')
                    ->limit(16)
                    ->get(),



                // Top Podcasts (NO country filter)
                'topPodcasts' => Content::select('uuid', 'title', 'slug', 'thumbnail_url', 'views')
                    ->where('content_group', 'podcast')
                    ->whereNull('parent_id')
                    ->orderByDesc('views')
                    ->limit(16)
                    ->get(),

                // Latest Podcasts (NO country filter)
                'podcasts' => Content::select('uuid', 'title', 'slug', 'thumbnail_url', 'views')
                    ->where('content_group', 'podcast')
                    ->whereNull('parent_id')
                    ->latest()
                    ->limit(8)
                    ->get(),

                // Categories
                'categories' => Category::select('uuid', 'name', 'slug')
                    ->limit(6)
                    ->get(),
            ];
        });
        $this->data = array_merge($this->data, $data);

        return view('Frontend.index', $this->data);
    }


    private function getCountryNameByIso($iso)
    {
        $path = app_path('Console/Commands/Regions.json');

        if (!File::exists($path)) {
            return null;
        }

        $countries = json_decode(File::get($path), true);

        foreach ($countries as $country) {
            if (strtoupper($country['code']) === $iso) {
                return $country['name'];
            }
        }

        return null;
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
