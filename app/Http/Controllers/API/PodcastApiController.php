<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class PodcastApiController extends Controller
{
    private string $apiUrl;

    public function __construct()
    {
        // Base API URL for fetching podcasts
        $this->apiUrl = config('services.nowstream.api');
    }

    /**
     * Fetch all podcasts from external API
     */
    private function fetchPodcasts(): Collection
    {
        try {
            $response = Http::get("{$this->apiUrl}/podcasts");

            if (!$response->successful()) {
                return collect();
            }

            return collect($response->json()['data'] ?? []);
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Map API data into uniform objects
     */
    private function mapPodcastData(Collection $podcasts): Collection
    {
        return $podcasts->map(function ($p) {
            return (object)[
                'uuid'        => $p['uuid'] ?? null,
                'slug'        => $p['slug'] ?? null,
                'title'       => $p['title'] ?? null,
                'description' => $p['description'] ?? null,
                'thumbnail'   => $p['thumbnail_url'] ?? null,
                'stream_url'  => $p['stream_url'] ?? null,
                'source'      => $p['source'] ?? null,
                'author'      => $p['author'] ?? null,
                'duration'    => $p['duration'] ?? null,
                'views'       => $p['views'] ?? 0,
                'parent_id'   => $p['parent_id'] ?? null,
                'created_at'  => $p['created_at'] ?? null,
                'updated_at'  => $p['updated_at'] ?? null,
            ];
        });
    }

    /**
     * List all top-level podcasts with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $allPodcasts = $this->mapPodcastData($this->fetchPodcasts());

        // Only parent podcasts (parent_id = null)
        $podcasts = $allPodcasts->whereNull('parent_id')->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $paged = new \Illuminate\Pagination\LengthAwarePaginator(
            $podcasts->slice(($page - 1) * $perPage, $perPage)->values(),
            $podcasts->count(),
            $perPage,
            $page,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );

        return response()->json([
            'success'    => true,
            'data'       => $paged->items(),
            'pagination' => [
                'current_page' => $paged->currentPage(),
                'last_page'    => $paged->lastPage(),
                'per_page'     => $paged->perPage(),
                'total'        => $paged->total(),
            ],
        ]);
    }

    /**
     * Show single podcast and its episodes
     */
    public function show($slug)
    {
        $allPodcasts = $this->mapPodcastData($this->fetchPodcasts());

        // Find parent podcast
        $podcast = $allPodcasts->firstWhere('slug', $slug);

        if (!$podcast) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found'
            ], 404);
        }

        // Episodes: podcasts where parent_id equals current podcast uuid
        $episodes = $allPodcasts->filter(fn($p) => $p->parent_id === $podcast->uuid)->values();

        // Related podcasts: top-level podcasts excluding current
        $related = $allPodcasts
            ->whereNull('parent_id')
            ->where('slug', '!=', $slug)
            ->take(6)
            ->values();

        return response()->json([
            'success'  => true,
            'podcast'  => $podcast,
            'episodes' => $episodes,
            'related'  => $related,
        ]);
    }

    /**
     * Fetch episodes of a podcast separately
     */
    public function episodes($slug)
    {
        $allPodcasts = $this->mapPodcastData($this->fetchPodcasts());

        $podcast = $allPodcasts->firstWhere('slug', $slug);

        if (!$podcast) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found'
            ], 404);
        }

        $episodes = $allPodcasts->filter(fn($p) => $p->parent_id === $podcast->uuid)->values();

        return response()->json([
            'success'  => true,
            'podcast'  => $podcast,
            'episodes' => $episodes,
        ]);
    }
}