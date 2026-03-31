<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RadioController extends Controller
{
    protected $data = [];

    /**
     * Radio listing page
     */

    public function index(Request $request)
    {
        $perPage = 30;
        $page = $request->get('page', 1);

        /**
         * Radios pagination (page cache)
         */
        $radios = Cache::remember(
            "radios_page_{$page}",
            now()->addMinutes(10),
            function () use ($perPage, $page) {
                return Content::where('content_group', 'radio')
                    ->whereNotNull('stream_url')
                    ->where('status', 1)
                    ->latest()
                    ->paginate($perPage, ['*'], 'page', $page); // FIX
            }
        );

        $radios->appends($request->all());


        /**
         * Categories (long cache)
         */
        $categories = Cache::remember(
            'radio_categories',
            now()->addHours(6),
            function () {
                return Category::where('type', 'radio')
                    ->limit(6)
                    ->get();
            }
        );

        $genres = Cache::remember('radio_genres', now()->addHours(6), function () {

            $genreViews = [];

            Content::where('content_group', 'radio')
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

                        foreach ($list as $g) {
                            $g = trim($g);
                            if (!$g) continue;
                            $genreViews[$g] = ($genreViews[$g] ?? 0) + (int) $content->views;
                        }
                    }
                });

            // Sort by total views descending
            arsort($genreViews);

            // Return only the top 20 genres
            return collect($genreViews)->keys()->take(20)->values();
        });
        /**
         * Top Radios Pool
         */
        $topRadiosPool = Cache::remember(
            'top_radios_pool',
            now()->addMinutes(10),
            function () {
                return Content::where('content_group', 'radio')
                    ->whereNotNull('stream_url')
                    ->where('status', 1)
                    ->orderByDesc('views')
                    ->limit(50)
                    ->get();
            }
        );

        $topradios = $topRadiosPool->shuffle()->take(16);

        // AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'Frontend.includes.components.partials.radio-items',
                    compact('radios')
                )->render(),
                'hasMore' => $radios->hasMorePages(),
                'nextPageUrl' => $radios->nextPageUrl(),
            ]);
        }

        return view('Frontend.modules.radios.index', compact(
            'radios',
            'categories',
            'topradios',
            'genres'
        ));
    }

    public function show($slug)
    {
        try {
            $cacheKey = "radio_detail_{$slug}";
            $radio = Cache::get($cacheKey);

            if (!$radio instanceof Content) {
                $radio = $this->resolveRadio($slug);

                if ($radio) {
                    Cache::put($cacheKey, $radio, now()->addHours(12));
                } else {
                    Cache::forget($cacheKey);
                }
            }

            if (!$radio) {
                abort(404, 'Radio not found');
            }

            $uuid = $radio->uuid;


            /**
             * Increment views (avoid cached model mutation)
             */
            Content::where('id', $radio->id)->increment('views');


            /**
             * Comments (short cache)
             */
            $comments = Cache::remember(
                "radio_comments_{$uuid}",
                now()->addMinutes(5),
                function () use ($radio) {
                    return $radio->comments()
                        ->with('user')
                        ->oldest()
                        ->get();
                }
            );


            /**
             * Genres (cache)
             */
            $genres = Cache::remember(
                "radio_genres_{$uuid}",
                now()->addHours(6),
                function () use ($radio) {
                    return collect($radio->genre ?? [])
                        ->filter()
                        ->unique()
                        ->values();
                }
            );


            /**
             * Related Radios Pool (global cache)
             * Then randomize per request
             */
            $relatedPool = Cache::remember(
                'radio_related_pool',
                now()->addHours(6),
                function () {
                    return Content::where('content_group', 'radio')
                        ->whereNotNull('stream_url')
                        ->where('status', 1)
                        ->latest()
                        ->limit(60)   // large pool
                        ->get();
                }
            );

            $related = $relatedPool
                ->where('uuid', '!=', $uuid)
                ->shuffle()
                ->take(16)
                ->values();


            return view('Frontend.modules.radios.show', compact(
                'radio',
                'related',
                'comments',
                'genres'
            ));
        } catch (\Throwable $e) {
            Log::error('Radio show failed.', [
                'slug' => $slug,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            abort(404, 'Radio not found');
        }
    }
    public function incrementViews($uuid)
    {
        $content = Content::findOrFail($uuid);

        $content->increment('views');

        if ($content->content_group === 'podcast' && ($content->parent_id ?? false)) {
            $podcast = Content::find($content->parent_id);
            if ($podcast) {
                $podcast->increment('views');
            }
        }

        return response()->json([
            'success' => true,
            'views' => $content->views,
            'content_group' => $content->content_group
        ]);
    }

    private function resolveRadio(string $slug): ?Content
    {
        $slugifiedTitle = Str::slug($slug);

        $baseQuery = Content::query()
            ->where('content_group', 'radio')
            ->where(function ($query) use ($slug, $slugifiedTitle) {
                $query
                    ->where('slug', $slug)
                    ->orWhere('old_id', $slug)
                    ->orWhereRaw('LOWER(slug) = ?', [strtolower($slug)])
                    ->orWhereRaw('LOWER(old_id) = ?', [strtolower($slug)])
                    ->orWhereRaw('LOWER(REPLACE(title, \" \", \"-\")) = ?', [strtolower($slug)])
                    ->orWhereRaw('LOWER(REPLACE(title, \" \", \"-\")) = ?', [strtolower($slugifiedTitle)]);
            });

        $activeRadio = (clone $baseQuery)
            ->where('status', 1)
            ->first();

        if ($activeRadio) {
            return $activeRadio;
        }

        return $baseQuery->first();
    }
}
