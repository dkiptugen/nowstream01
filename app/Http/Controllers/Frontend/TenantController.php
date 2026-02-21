<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Content;
use App\Models\Microsite;
use App\Traits\CacheHelper;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class TenantController extends Controller
{
    use CacheHelper;

    protected $data = [];

    public function index(Request $request)
    {
        $iso = strtoupper($request->country ?? 'KE');

        // Country map (cached forever)
        $countries = Cache::rememberForever('countries_by_iso', function () {
            $path = public_path('assets/json/Regions.json');
            if (!File::exists($path)) return [];

            $data = json_decode(File::get($path), true);
            return collect($data)->pluck('name', 'code')
                ->mapWithKeys(fn($name, $code) => [strtoupper($code) => $name])
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

                'topevents'     => Event::with(['eventRates' => function ($q) {
                    $q->orderBy('price', 'asc');
                }])
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
        return view('Frontend.modules.microsites.index', $data);
    }
   public function single_event($slug)
{
    try {
        /*
        |--------------------------------------------------------------------------
        | 1. Cache Event Core (longer cache – rarely changes)
        |--------------------------------------------------------------------------
        */
        $event = Cache::remember("event_core_{$slug}", now()->addHours(6), function () use ($slug) {
            return Event::select(
                    'uuid',
                    'title',
                    'slug',
                    'description',
                    'start_date',
                    'end_date',
                    'thumbnail_url',
                    'views',
                    'status'
                )
                ->where('slug', $slug)
                ->where('status', 1)
                ->firstOrFail();
        });

        $eventId = $event->uuid;

        /*
        |--------------------------------------------------------------------------
        | 2. Cache Heavy Page Data (shorter cache)
        |--------------------------------------------------------------------------
        */
        $pageData = Cache::remember("event_page_data_{$eventId}", now()->addMinutes(10), function () use ($eventId) {
            return [
                'events' => $this->get_events($eventId),            // related listings
                'videos' => $this->get_videos(),                    // global videos
                'rates'  => $this->get_event_ticket_rates($eventId) // ticket pricing
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Increment Views (not cached)
        |--------------------------------------------------------------------------
        */
        Event::where('uuid', $eventId)->increment('views');

        /*
        |--------------------------------------------------------------------------
        | 4. Related Events (long cache)
        |--------------------------------------------------------------------------
        */
        $relatedEvents = Cache::remember("related_events_{$eventId}", now()->addHours(12), function () use ($eventId) {
            return Content::select('uuid', 'title', 'slug', 'thumbnail_url')
                ->where('content_group', 'event')
                ->where('status', 1)
                ->where('uuid', '!=', $eventId)
                ->latest()
                ->limit(4)
                ->get();
        });

        return view('Frontend.modules.events.event', [
            'event'         => $event,
            'events'        => $pageData['events'],
            'videos'        => $pageData['videos'],
            'rates'         => $pageData['rates'],
            'relatedEvents' => $relatedEvents,
        ]);

    } catch (\Exception $e) {
        abort(404, 'Event not found.');
    }
}

}
