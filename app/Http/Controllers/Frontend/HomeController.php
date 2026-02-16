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
        $iso = strtoupper($request->country ?? 'KE');

        // Cache country name forever (JSON should not be read per request)
        $countryName = Cache::rememberForever("country_name_{$iso}", function () use ($iso) {
            return $this->getCountryNameByIso($iso) ?? 'Unknown Country';
        });

        $this->data['country'] = $iso;
        $this->data['country_name'] = $countryName;

        /*
        |--------------------------------------------------------------------------
        | Page Cache Key (country-specific)
        |--------------------------------------------------------------------------
        */
        $cacheKey = "homepage_data_{$countryName}";
dd($cacheKey);
        $this->data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($countryName, $iso) {

            return [
                'country' => $iso,
                'country_name' => $countryName,

                // Channels
                'channels' => Cache::remember("channels_{$countryName}", 600, function () {
                    return $this->get_channels();
                }),

                // Streams
                'streams' => Cache::remember("streams_{$countryName}", 600, function () {
                    return $this->get_streams(null, 6);
                }),

                // Events
                'events' => Cache::remember("events_{$countryName}", 600, function () {
                    return $this->get_events();
                }),

                // Videos
                'videos' => Cache::remember("videos_{$countryName}", 600, function () {
                    return $this->get_videos(6);
                }),

                // Latest Event
                'current_event' => Cache::remember("current_event", 300, function () {
                    return Content::latest()->limit(1)->get();
                }),

                // Top Videos
                'top_videos' => Cache::remember("top_videos_{$countryName}", 600, function () use ($countryName) {
                    return Content::where('content_group', 'video')
                        ->where('country', $countryName)
                        ->orderByDesc('views')
                        ->limit(14)
                        ->get();
                }),

                // Top TVs
                'toptvs' => Cache::remember("top_tvs_{$countryName}", 600, function () use ($countryName) {
                    return Content::where('content_group', 'tv')
                        ->whereNotNull('stream_url')
                        ->where('country', $countryName)
                        ->orderByDesc('views')
                        ->limit(16)
                        ->get();
                }),

                // Top Radios
                'topradios' => Cache::remember("top_radios_{$countryName}", 600, function () use ($countryName) {
                    return Content::where('content_group', 'radio')
                        ->whereNotNull('stream_url')
                        ->where('country', $countryName)
                        ->where('status', 1)
                        ->orderByDesc('views')
                        ->limit(16)
                        ->get();
                }),

                // Podcasts
                'podcasts' => Cache::remember("podcasts_{$countryName}", 600, function () {
                    return $this->get_podcasts(16)->where('parent_id', null);
                }),

                // Top Podcasts
                'topPodcasts' => Cache::remember("top_podcasts_{$countryName}", 600, function () use ($countryName) {
                    return Content::where('content_group', 'podcast')
                        ->whereNull('parent_id')
                        ->where('country', $countryName)
                        ->orderByDesc('views')
                        ->limit(16)
                        ->get();
                }),

                // Categories (rarely change)
                'categories' => Cache::remember('homepage_categories', 3600, function () {
                    return Category::limit(6)->get();
                }),
            ];
        });

        return view('Frontend.index', $this->data);
    }

  private function getCountryNameByIso($iso)
{
    $countries = Cache::rememberForever('countries_json', function () {
        $path = public_path('assets/json/Regions.json'); // Updated path
        if (!File::exists($path)) {
            return [];
        }
        return json_decode(File::get($path), true);
    });

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
