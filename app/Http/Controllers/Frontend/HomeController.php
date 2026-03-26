<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Models\Event;
use App\Models\Microsite;
use App\Traits\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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

        $countries = Cache::rememberForever('countries_by_iso', function () {
            $path = public_path('assets/json/Regions.json');
            if (!File::exists($path)) {
                return [];
            }

            $data = json_decode(File::get($path), true);

            return collect($data)
                ->pluck('name', 'code')
                ->mapWithKeys(fn ($name, $code) => [strtoupper($code) => $name])
                ->toArray();
        });

        $countryName = $countries[$iso] ?? 'Kenya';

        $cacheKey = "homepage_{$iso}";
        $data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($iso, $countryName) {
            $topVideos = Cache::remember('home_top_videos', 1800, function () {
                return Content::where('content_group', 'video')
                    ->orderByDesc('views')
                    ->limit(4)
                    ->get();
            });

            $genres = Cache::remember('tv_genres', now()->addHours(6), function () {
                $genreViews = [];

                Content::where('content_group', 'tv')
                    ->whereNotNull('genre')
                    ->select('genre', 'views')
                    ->chunk(500, function ($contents) use (&$genreViews) {
                        foreach ($contents as $content) {
                            $genres = $content->genre;

                            if (is_array($genres)) {
                                $list = $genres;
                            } else {
                                $genres = trim($genres, '"');

                                if (str_starts_with($genres, '[')) {
                                    $decoded = json_decode($genres, true);
                                    $list = is_array($decoded) ? $decoded : [];
                                } elseif (str_contains($genres, ',')) {
                                    $list = array_map('trim', explode(',', $genres));
                                } else {
                                    $list = [$genres];
                                }
                            }

                            foreach ($list as $genre) {
                                $genre = trim($genre);
                                if (!$genre) {
                                    continue;
                                }

                                $genreViews[$genre] = ($genreViews[$genre] ?? 0) + (int) $content->views;
                            }
                        }
                    });

                arsort($genreViews);

                return collect($genreViews)->keys()->take(30)->values();
            });

            $events = $this->get_events();
            $topEvents = Event::with('eventRates')
                ->where('status', 1)
                ->orderByDesc('views')
                ->limit(12)
                ->get();

            $videos = Content::where('content_group', 'video')
                ->latest()
                ->limit(12)
                ->get();

            $topTvs = Content::where('content_group', 'tv')
                ->whereNotNull('stream_url')
                ->where('country', $countryName)
                ->orderByDesc('views')
                ->limit(16)
                ->get();

            $topRadios = Content::where('content_group', 'radio')
                ->whereNotNull('stream_url')
                ->where('country', $countryName)
                ->where('status', 1)
                ->orderByDesc('views')
                ->limit(16)
                ->get();

            $podcasts = $this->get_podcasts(16)->where('parent_id', null)->values();
            $topPodcasts = Content::where('content_group', 'podcast')
                ->whereNull('parent_id')
                ->orderByDesc('views')
                ->limit(16)
                ->get();

            $heroEvents = $topEvents->take(3)->values();
            if ($heroEvents->isEmpty() && $events->isNotEmpty()) {
                $heroEvents = $events->take(3)->values();
            }

            $heroGenres = $genres->filter()->unique()->take(8)->values();

            return [
                'country' => $iso,
                'country_name' => $countryName,
                'streams' => $this->get_streams(null, 6),
                'events' => $events,
                'topevents' => $topEvents,
                'videos' => $videos,
                'top_videos' => $topVideos,
                'current_event' => Content::latest()->first(),
                'toptvs' => $topTvs,
                'topradios' => $topRadios,
                'podcasts' => $podcasts,
                'topPodcasts' => $topPodcasts,
                'genres' => $genres ?? collect(),
                'categories' => Category::limit(6)->get(),
                'heroEvents' => $heroEvents,
                'heroGenres' => $heroGenres,
                'heroLiveChannels' => $topTvs->take(4)->values(),
                'eventShelf' => $topEvents->take(8)->values(),
                'tvShelf' => $topTvs->take(12)->values(),
                'radioShelf' => $topRadios->take(12)->values(),
                'videoFeatureShelf' => $topVideos->take(4)->values(),
                'videoShelf' => $videos->take(8)->values(),
                'podcastShelf' => $podcasts->take(12)->values(),
                'latestPodcastShelf' => $topPodcasts->take(12)->values(),
                'quickLinks' => [
                    ['title' => 'Live TV', 'meta' => 'Top channels in ' . $countryName, 'route' => route('tvs')],
                    ['title' => 'Radio', 'meta' => 'Streaming stations and talk audio', 'route' => route('radios')],
                    ['title' => 'Videos', 'meta' => 'Fresh clips, replays, and on-demand', 'route' => route('videos')],
                    ['title' => 'Podcasts', 'meta' => 'Interviews, stories, and series', 'route' => route('podcasts')],
                    ['title' => 'Events', 'meta' => 'Major live nights and ticketed streams', 'route' => route('events')],
                ],
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
