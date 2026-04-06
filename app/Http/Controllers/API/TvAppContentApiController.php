<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TvAppCategoryResource;
use App\Http\Resources\TvAppContentResource;
use App\Http\Resources\TvAppEventResource;
use App\Http\Resources\TvAppRegionResource;
use App\Models\Category;
use App\Models\Content;
use App\Models\Event;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TvAppContentApiController extends Controller
    {
        private const DEFAULT_GROUPS  = ['livestream', 'tv', 'radio', 'podcast', 'video'];
        private const FEATURED_GROUPS = ['livestream', 'video', 'tv', 'radio', 'music', 'movie'];
        private const CACHE_TTL       = 600; // 10 minutes

        public function home(Request $request)
            {
               // Log::info('Fetching TV app home', $request->all());
                $limit    = $this->resolveLimit($request, 12, 50);
                $groups   = $this->resolveGroups($request->query('group'));
                $regionId = $this->resolveRegionId($request);

                $cacheKey = "home:groups:" . implode(',', $groups) . ":region:" . ($regionId ?? 'all') . ":limit:$limit";

                $payload = Cache::tags(['contents', 'categories', 'events', 'regions'])->remember(
                    $cacheKey,
                    self::CACHE_TTL,
                    fn() => [
                        'featured'   => $this->buildFeaturedPayload($limit, $regionId),
                        'categories' => TvAppCategoryResource::collection($this->fetchCategories($groups, $regionId)),
                        'regions'    => TvAppRegionResource::collection($this->fetchRegions($groups)),
                        'events'     => TvAppEventResource::collection($this->fetchEvents($limit)),
                        'podcasts'   => $this->get_podcasts($request),
                    ]
                );

                return response()->api($payload, 'TV app home fetched successfully.', 200, [
                    'groups'    => $groups,
                    'region_id' => $regionId,
                    'limit'     => $limit,
                ]);
            }

        public function featured(Request $request)
            {
                $limit    = $this->resolveLimit($request, 12, 50);
                $groups   = $this->resolveGroups($request->query('group'), self::FEATURED_GROUPS);
                $regionId = $this->resolveRegionId($request);

                $cacheKey = "featured:groups:" . implode(',', $groups) . ":region:" . ($regionId ?? 'all') . ":limit:$limit";

                $payload = Cache::tags(['contents', 'events'])->remember(
                    $cacheKey,
                    self::CACHE_TTL,
                    fn() => [
                        'featured' => $this->buildFeaturedPayload($limit, $regionId),
                        'podcasts' => $this->get_podcasts($request),
                        'events'   => TvAppEventResource::collection($this->fetchEvents($limit)),
                    ]
                );

                return response()->api($payload, 'Featured TV app content fetched successfully.', 200, [
                    'region_id' => $regionId,
                ]);
            }

        public function categories(Request $request)
            {
                $groups   = $this->resolveGroups($request->query('group'));
                $regionId = $this->resolveRegionId($request);

                $cacheKey = "categories:groups:" . implode(',', $groups) . ":region:" . ($regionId ?? 'all');

                $categories = Cache::tags(['categories', 'contents'])->remember(
                    $cacheKey,
                    self::CACHE_TTL,
                    fn() => TvAppCategoryResource::collection($this->fetchCategories($groups, $regionId))
                );

                return response()->api($categories, 'Categories fetched successfully.', 200, [
                    'groups'    => $groups,
                    'region_id' => $regionId,
                ]);
            }

    // Other endpoints (categoryContents, eventContents, regionContents, events, regions) can also use caching similarly:
    // Just wrap the final payload in Cache::tags([...])->remember(key, ttl, fn() => payload)

        private function resolveGroups(?string $rawGroups, array $default = self::DEFAULT_GROUPS): array
            {
                $groups = collect(explode(',', (string)$rawGroups))
                    ->map(fn(string $group) => trim($group))
                    ->filter()
                    ->intersect(self::DEFAULT_GROUPS)
                    ->values()
                    ->all();

                return empty($groups) ? $default : $groups;
            }

        private function resolveLimit(Request $request, int $default, int $max): int
            {
                $value = (int)$request->query('per_page', $request->query('limit', $default));
                return max(1, min($value, $max));
            }

        private function resolveRegionId(Request $request): ?int
            {
                $regionId = $request->query('region_id');
                if ($regionId === null || $regionId === '')
                    {
                        return null;
                    }
                return (int)$regionId;
            }

        private function paginationMeta($paginator): array
            {
                return [
                    'current_page'   => $paginator->currentPage(),
                    'last_page'      => $paginator->lastPage(),
                    'per_page'       => $paginator->perPage(),
                    'total'          => $paginator->total(),
                    'has_more_pages' => $paginator->hasMorePages(),
                    'next_page_url'  => $paginator->nextPageUrl(),
                    'prev_page_url'  => $paginator->previousPageUrl(),
                ];
            }

        private function buildFeaturedPayload(int $limit, ?int $regionId): array
            {
                // Log::info('region',[$regionId,self::FEATURED_GROUPS]);
                return collect(self::FEATURED_GROUPS)->mapWithKeys(function (string $group) use ($limit, $regionId)
                    {
                        $query = Content::query()
                                        ->with(['categories', 'region'])
                                        ->where('content_group', $group)
                                        ->where('status', 1)
                                        ->whereNotNull('stream_url');

                        /*if ($regionId !== null)
                            {
                                $query->where('region_id', $regionId);
                            }*/
                        $query->orderByDesc('views');

                        return [$group => TvAppContentResource::collection($query->limit($limit)->get())];
                    })->all();
            }

        public function get_podcasts(Request $request)
            {
                $query = Content::query()
                                ->with(['categories', 'region','children'])
                                ->where('content_group', 'podcast')
                                ->where('status', 1)
                                ->whereNotNull('stream_url');

                $query->orderByDesc('views');
                $query->limit($request->query('per_page', 12));

                return  $query->get();
            }
        public function get_podcast_episodes( string $slug)
            {
                $podcast = Content::with('children')
                                ->findBySlug($slug);

                return  $podcast;
            }

        private function fetchCategories(array $groups, ?int $regionId)
            {
                return Category::query()
                               ->whereHas('contents', function ($query) use ($groups, $regionId)
                                   {
                                       $query->where('status', 1)->whereIn('content_group', $groups);
                                       if ($regionId !== null)
                                           {
                                               $query->where('region_id', $regionId);
                                           }
                                   })
                               ->withCount(['contents' => function ($query) use ($groups, $regionId)
                                   {
                                       $query->where('status', 1)->whereIn('content_group', $groups);
                                       if ($regionId !== null)
                                           {
                                               $query->where('region_id', $regionId);
                                           }
                                   }])
                               ->orderBy('name')
                               ->get();
            }

        private function fetchRegions(array $groups)
            {
                return Region::query()
                             ->whereHas('contents', function ($query) use ($groups)
                                 {
                                     $query->where('status', 1)->whereIn('content_group', $groups);
                                 })
                             ->withCount(['contents' => function ($query) use ($groups)
                                 {
                                     $query->where('status', 1)->whereIn('content_group', $groups);
                                 }])
                             ->orderBy('name')
                             ->get();
            }

        private function fetchEvents(int $limit)
            {
                return Event::query()
                            ->where('status', 1)
                            ->withCount(['streams' => fn($q) => $q->where('status', 1)])
                            ->orderByDesc('views')
                            ->limit($limit)
                            ->get();
            }

        public function regions(Request $request)
            {
                $code = strtoupper($request->query('code', ''));

                if (empty($code))
                    {
                        return response()->json([
                            'success' => false,
                            'message' => 'Region code is required.',
                        ], 400);
                    }

                $cacheKey = "region_{$code}";

                $region = Cache::tags(['region'])->rememberForever($cacheKey, function () use ($code)
                    {
                        return Region::query()
                                     ->whereRaw('UPPER(code) = ?', [$code])
                                     ->first();
                    });

                if (!$region)
                    {
                        return response()->json([
                            'success' => false,
                            'message' => 'Region not found.',
                        ], 404);
                    }

                return response()->json([
                    'success' => true,
                    'data'    => $region,
                ]);
            }

    }
