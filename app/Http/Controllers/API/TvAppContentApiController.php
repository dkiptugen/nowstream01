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

class TvAppContentApiController extends Controller
    {
        private const DEFAULT_GROUPS  = ['livestream', 'tv', 'radio', 'podcast', 'video'];
        private const FEATURED_GROUPS = ['livestream', 'tv', 'podcast', 'video'];

        public function featured(Request $request)
            {
                $limit  = $this->resolveLimit($request, 12, 50);
                $groups = $this->resolveGroups($request->query('group'), self::FEATURED_GROUPS);
                $regionId = $this->resolveRegionId($request);

                $payload = collect($groups)->mapWithKeys(function (string $group) use ($limit, $regionId)
                    {
                        $query = Content::query()
                                        ->where('content_group', $group)
                                        ->where('status', 1)
                                        ->with(['event', 'categories', 'region'])
                                        ->orderByDesc('views');

                        if ($regionId !== null) {
                            $query->where('region_id', $regionId);
                        }

                        $items = $query->limit($limit)->get();

                        return [$group => TvAppContentResource::collection($items)];
                    })->all();

                $events = Event::query()
                               ->where('status', 1)
                               ->orderByDesc('views')
                               ->limit($limit)
                               ->get();

                return response()->api([
                    'featured' => $payload,
                    'events'   => TvAppEventResource::collection($events),
                ], 'Featured TV app content fetched successfully.', 200, [
                    'region_id' => $regionId,
                ]);
            }

        public function categories(Request $request)
            {
                $groups = $this->resolveGroups($request->query('group'));
                $regionId = $this->resolveRegionId($request);

                $categories = Category::query()
                                      ->whereHas('contents', function ($query) use ($groups, $regionId)
                                          {
                                              $query
                                                  ->where('status', 1)
                                                  ->whereIn('content_group', $groups);

                                              if ($regionId !== null) {
                                                  $query->where('region_id', $regionId);
                                              }
                                          })
                                      ->withCount(['contents' => function ($query) use ($groups, $regionId)
                                          {
                                              $query
                                                  ->where('status', 1)
                                                  ->whereIn('content_group', $groups);

                                              if ($regionId !== null) {
                                                  $query->where('region_id', $regionId);
                                              }
                                          }])
                                      ->orderBy('name')
                                      ->get();

                return response()->api(
                    TvAppCategoryResource::collection($categories),
                    'Categories fetched successfully.',
                    200,
                    [
                        'groups' => $groups,
                        'region_id' => $regionId,
                    ]
                );
            }

        public function categoryContents(Request $request, string $slug)
            {
                $groups  = $this->resolveGroups($request->query('group'));
                $perPage = $this->resolveLimit($request, 20, 100);
                $regionId = $this->resolveRegionId($request);

                $category = Category::where('slug', $slug)->firstOrFail();

                $contentsQuery = Content::query()
                                   ->where('status', 1)
                                   ->whereIn('content_group', $groups)
                                   ->whereHas('categories', function ($query) use ($category)
                                       {
                                           $query->where('categories.uuid', $category->uuid);
                                       })
                                   ->with(['event', 'categories', 'region'])
                                   ->orderByDesc('views');

                if ($regionId !== null) {
                    $contentsQuery->where('region_id', $regionId);
                }

                $contents = $contentsQuery->paginate($perPage);

                return response()->api(
                    TvAppContentResource::collection($contents->items()),
                    'Category content fetched successfully.',
                    200,
                    [
                        'category'   => (new TvAppCategoryResource($category))->resolve(),
                        'pagination' => $this->paginationMeta($contents),
                        'groups'     => $groups,
                        'region_id'  => $regionId,
                    ]
                );
            }

        public function events(Request $request)
            {
                $perPage = $this->resolveLimit($request, 20, 100);

                $events = Event::query()
                               ->where('status', 1)
                               ->withCount(['streams' => function ($query)
                                   {
                                       $query->where('status', 1);
                                   }])
                               ->orderByDesc('views')
                               ->orderBy('start_time')
                               ->paginate($perPage);

                return response()->api(
                    TvAppEventResource::collection($events->items()),
                    'Events fetched successfully.',
                    200,
                    ['pagination' => $this->paginationMeta($events)]
                );
            }

        public function eventContents(Request $request, string $slug)
            {
                $groups  = $this->resolveGroups($request->query('group'));
                $perPage = $this->resolveLimit($request, 20, 100);
                $regionId = $this->resolveRegionId($request);

                $event = Event::where('slug', $slug)->where('status', 1)->firstOrFail();

                $contentsQuery = Content::query()
                                   ->where('event_id', $event->uuid)
                                   ->where('status', 1)
                                   ->whereIn('content_group', $groups)
                                   ->with(['event', 'categories', 'region'])
                                   ->orderByDesc('views');

                if ($regionId !== null) {
                    $contentsQuery->where('region_id', $regionId);
                }

                $contents = $contentsQuery->paginate($perPage);

                return response()->api(
                    TvAppContentResource::collection($contents->items()),
                    'Event content fetched successfully.',
                    200,
                    [
                        'event'      => (new TvAppEventResource($event))->resolve(),
                        'pagination' => $this->paginationMeta($contents),
                        'groups'     => $groups,
                        'region_id'  => $regionId,
                    ]
                );
            }

        public function regions(Request $request)
            {
                $groups = $this->resolveGroups($request->query('group'));

                $regions = Region::query()
                                 ->whereHas('contents', function ($query) use ($groups)
                                     {
                                         $query
                                             ->where('status', 1)
                                             ->whereIn('content_group', $groups);
                                     })
                                 ->withCount(['contents' => function ($query) use ($groups)
                                     {
                                         $query
                                             ->where('status', 1)
                                             ->whereIn('content_group', $groups);
                                     }])
                                 ->orderBy('name')
                                 ->get();

                return response()->api(
                    TvAppRegionResource::collection($regions),
                    'Regions fetched successfully.',
                    200,
                    ['groups' => $groups]
                );
            }

        public function regionContents(Request $request, int $regionId)
            {
                $groups  = $this->resolveGroups($request->query('group'));
                $perPage = $this->resolveLimit($request, 20, 100);

                $region = Region::findOrFail($regionId);

                $contents = Content::query()
                                   ->where('status', 1)
                                   ->where('region_id', $region->id)
                                   ->whereIn('content_group', $groups)
                                   ->with(['event', 'categories', 'region'])
                                   ->orderByDesc('views')
                                   ->paginate($perPage);

                return response()->api(
                    TvAppContentResource::collection($contents->items()),
                    'Region content fetched successfully.',
                    200,
                    [
                        'region'     => (new TvAppRegionResource($region))->resolve(),
                        'pagination' => $this->paginationMeta($contents),
                        'groups'     => $groups,
                    ]
                );
            }

        private function resolveGroups(?string $rawGroups, array $default = self::DEFAULT_GROUPS): array
            {
                $groups = collect(explode(',', (string)$rawGroups))
                    ->map(fn(string $group) => trim($group))
                    ->filter()
                    ->intersect(self::DEFAULT_GROUPS)
                    ->values()
                    ->all();

                return $groups === [] ? $default : $groups;
            }

        private function resolveLimit(Request $request, int $default, int $max): int
            {
                $value = (int)$request->query('per_page', $request->query('limit', $default));

                return max(1, min($value, $max));
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

        private function resolveRegionId(Request $request): ?int
            {
                $regionId = $request->query('region_id');

                if ($regionId === null || $regionId === '') {
                    return null;
                }

                return (int) $regionId;
            }
    }
