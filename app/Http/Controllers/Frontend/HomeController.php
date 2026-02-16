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

    protected $data = [];

    /**
     * Display the homepage with cached channels, streams, events, and videos.
     */

    public function index(Request $request)
    {
        $iso = strtoupper($request->country ?? 'KE'); // default country

        // Build a country lookup map (cached forever)
        $countryName = Cache::rememberForever('countries_by_iso', function () {
            $path = public_path('assets/json/Regions.json'); // your JSON path
            if (!File::exists($path))
                return [];

            $countries = json_decode(File::get($path), true);
            $map = [];
            foreach ($countries as $c) {
                $map[strtoupper($c['code'])] = $c['name'];
            }
            return $map;
        })[$iso] ?? 'Unknown Country';

        $this->data['country'] = $iso;
        $this->data['country_name'] = $countryName;

        // Homepage cache key per country
        $cacheKey = "homepage_data_{$countryName}";

        // Cache everything for 10 minutes (or adjust TTL)
        $cachedData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($countryName, $iso) {
            return [
                'country' => $iso,
                'country_name' => $countryName,
                'channels' => $this->get_channels(),
                'streams' => $this->get_streams(null, 6),
                'events' => $this->get_events(),
                'videos' => Content::where('content_group', 'video')->latest()->paginate(12),
                'top_videos' => Content::where('content_group', 'video')->orderByDesc('views')->paginate(12),
                'current_event' => Content::latest()->limit(1)->get(),
                'toptvs' => Content::where('content_group', 'tv')
                    ->whereNotNull('stream_url')
                    ->where('country', $countryName)
                    ->orderByDesc('views')->limit(16)->get(),
                'topradios' => Content::where('content_group', 'radio')
                    ->whereNotNull('stream_url')
                    ->where('country', $countryName)
                    ->where('status', 1)
                    ->orderByDesc('views')->limit(16)->get(),
                'podcasts' => $this->get_podcasts(16)->where('parent_id', null),
                'topPodcasts' => Content::where('content_group', 'podcast')
                    ->whereNull('parent_id')
                    ->orderByDesc('views')
                    ->limit(16)
                    ->get(),
                'categories' => Category::limit(6)->get(),
            ];
        });


        $this->data = $cachedData;

        return view('Frontend.index', $this->data);
    }


    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('Frontend.terms');
    }
}
