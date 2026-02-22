<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Event;
use App\Models\ContentRate;
use App\Models\Content;
use App\Models\Microsite;
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
    $iso = strtoupper($request->country ?? 'KE');

    // Country map (cached forever)
    $countries = Cache::rememberForever('countries_by_iso', function () {
        $path = public_path('assets/json/Regions.json');
        if (!File::exists($path)) return [];

        $data = json_decode(File::get($path), true);
        return collect($data)->pluck('name', 'code')
            ->mapWithKeys(fn ($name, $code) => [strtoupper($code) => $name])
            ->toArray();
    });

    $countryName = $countries[$iso] ?? 'Kenya';

    $cacheKey = "homepage_{$iso}";
    $data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($iso, $countryName) {

        // Heavy sections cached individually
        $topVideos = Cache::remember('home_top_videos', 1800, function () {
            return Content::where('content_group', 'video')
                ->orderByDesc('views')
                ->limit(4)
                ->get();
        });

        return [
            'country'       => $iso,
            'country_name'  => $countryName,

            // Streams & Channels
            'channels'      => $this->get_channels(),
            'streams'       => $this->get_streams(null, 6),

            // Events
            'events'        => $this->get_events(),

            'topevents'     => Event::with(['eventRates'])
                ->where('status', 1)
                ->orderByDesc('views')
                ->limit(12)
                ->get(),

            // Latest videos (no paginate inside cache)
            'videos' => Content::where('content_group', 'video')
                ->latest()
                ->limit(12)
                ->get(),



            'top_videos' => $topVideos,

            // Current event
            'current_event' => Content::latest()->first(),

            // Country specific content
            'toptvs' => Content::where('content_group', 'tv')
                ->whereNotNull('stream_url')
                ->where('country', $countryName)
                ->orderByDesc('views')
                ->limit(16)
                ->get(),

            'topradios' => Content::where('content_group', 'radio')
                ->whereNotNull('stream_url')
                ->where('country', $countryName)
                ->where('status', 1)
                ->orderByDesc('views')
                ->limit(16)
                ->get(),

            // Podcasts
            'podcasts' => $this->get_podcasts(16)->where('parent_id', null),

            'topPodcasts' => Content::where('content_group', 'podcast')
                ->whereNull('parent_id')
                ->orderByDesc('views')
                ->limit(16)
                ->get(),

            'categories' => Category::limit(6)->get(),
        ];
    });
$this->data['microsites'] = Microsite::all();
    return view('Frontend.index', $data);
}


    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('Frontend.terms');
    }
}
