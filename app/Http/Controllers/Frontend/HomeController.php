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
        $iso = strtoupper($request->country);
        $countryName = $this->getCountryNameByIso($iso) ?? null;

        $cacheKey = 'home_' . ($countryName ?? 'kenya');

        $this->data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($countryName) {

            return [
                'country_name' => $countryName ?? 'Kenya',

                'channels' => $this->get_channels(),

                'streams' => $this->get_streams(null, 6),

                'events' => $this->get_events(),

                'videos' => $this->get_videos(6),

                'current_event' => Content::select('uuid', 'title', 'thumbnail_url', 'created_at')
                    ->latest()
                    ->first(),

                'top_videos' => $this->contentQuery('video', $countryName, 14),

                'toptvs' => $this->contentQuery('tv', $countryName, 16, true),

                'topradios' => $this->contentQuery('radio', $countryName, 16, true)
                    ->where('status', 1)
                    ->get(),

                'topPodcasts' => Content::select('uuid', 'title', 'thumbnail_url', 'views', 'country')
                    ->where('content_group', 'podcast')
                    ->whereNull('parent_id')
                    ->when(
                        $countryName,
                        fn($q) =>
                        $q->where(function ($sub) use ($countryName) {
                            $sub->where('country', $countryName)
                                ->orWhereNull('country');
                        })
                    )
                    ->orderByDesc('views')
                    ->limit(16)
                    ->get(),

                'podcasts' => $this->get_podcasts(16)->where('parent_id', null),

                'categories' => Category::select('id', 'name', 'slug')
                    ->limit(6)
                    ->get(),
            ];
        });

        $this->data['country'] = $iso;

        return view('Frontend.index', $this->data);
    }
    private function contentQuery($group, $countryName, $limit = 10, $requireStream = false)
    {
        $query = Content::select(
            'uuid',
            'title',
            'thumbnail_url',
            'stream_url',
            'views',
            'country'
        )
            ->where('content_group', $group)
            ->when($requireStream, fn($q) => $q->whereNotNull('stream_url'))
            ->when(
                $countryName,
                fn($q) =>
                $q->where(function ($sub) use ($countryName) {
                    $sub->where('country', $countryName)
                        ->orWhereNull('country');
                })
            )
            ->orderByDesc('views')
            ->limit($limit);

        return $query->get();
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
