<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PodcastApiController extends Controller
{
    private const JSON_FLAGS = JSON_INVALID_UTF8_SUBSTITUTE;

    /**
     * List Podcasts
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $podcasts = Content::where('content_group', 'podcast')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->latest()
            ->paginate($perPage);

        return response()->json(
            [
                'success' => true,
                'data' => collect($podcasts->items())
                    ->map(fn (Content $podcast) => $this->serializePodcast($podcast))
                    ->values(),
                'pagination' => [
                    'current_page' => $podcasts->currentPage(),
                    'last_page' => $podcasts->lastPage(),
                    'per_page' => $podcasts->perPage(),
                    'total' => $podcasts->total(),
                ],
            ],
            200,
            [],
            self::JSON_FLAGS
        );
    }


    /**
     * Single Podcast
     */
    public function show($slug)
    {
        $podcast = Content::where('slug', $slug)
            ->where('content_group', 'podcast')
            ->firstOrFail();

        // Increment views
        Content::where('uuid', $podcast->uuid)->increment('views');

        $episodes = Content::where('parent_id', $podcast->uuid)
            ->where('content_group', 'podcast_episode')
            ->orderByDesc('created_at')
            ->get();

        $related = Content::where('content_group', 'podcast')
            ->where('uuid', '!=', $podcast->uuid)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return response()->json(
            [
                'success' => true,
                'podcast' => $this->serializePodcast($podcast),
                'episodes' => $episodes
                    ->map(fn (Content $episode) => $this->serializeEpisode($episode, $podcast))
                    ->values(),
                'related' => $related
                    ->map(fn (Content $item) => $this->serializePodcast($item))
                    ->values(),
            ],
            200,
            [],
            self::JSON_FLAGS
        );
    }


    /**
     * Podcast Episodes
     */
    public function episodes($slug)
    {
        $podcast = Content::where('slug', $slug)
            ->where('content_group', 'podcast')
            ->firstOrFail();

        $episodes = Content::where('parent_id', $podcast->uuid)
            ->where('content_group', 'podcast_episode')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json(
            [
                'success' => true,
                'data' => collect($episodes->items())
                    ->map(fn (Content $episode) => $this->serializeEpisode($episode, $podcast))
                    ->values(),
                'pagination' => [
                    'current_page' => $episodes->currentPage(),
                    'last_page' => $episodes->lastPage(),
                    'per_page' => $episodes->perPage(),
                    'total' => $episodes->total(),
                ],
            ],
            200,
            [],
            self::JSON_FLAGS
        );
    }


    /**
     * Record Watch History
     */
    public function recordWatchHistory(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $podcast = Content::findOrFail($request->podcast_id);

        WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $podcast->uuid,
            ],
            [
                'watched_at' => now(),
                'watch_duration' => $request->watch_duration ?? 0
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }

    private function serializePodcast(Content $podcast): array
    {
        return [
            'uuid' => $podcast->uuid,
            'slug' => $podcast->slug,
            'title' => $this->sanitizeString($podcast->title),
            'description' => $this->sanitizeString($podcast->description),
            'thumbnail_url' => $this->assetUrl($podcast->thumbnail_url),
            'content_group' => $podcast->content_group,
            'author' => $this->sanitizeString($podcast->author),
            'language' => $this->sanitizeString($podcast->language),
            'country' => $this->sanitizeString($podcast->country),
            'views' => (int) ($podcast->views ?? 0),
        ];
    }

    private function serializeEpisode(Content $episode, Content $podcast): array
    {
        $thumbnail = $this->assetUrl($episode->thumbnail_url) ?? $this->assetUrl($podcast->thumbnail_url);

        return [
            'uuid' => $episode->uuid,
            'slug' => $episode->slug,
            'title' => $this->sanitizeString($episode->title),
            'description' => $this->sanitizeString($episode->description),
            'thumbnail_url' => $thumbnail,
            'content_group' => $episode->content_group,
            'src' => $this->episodePlaybackUrl($episode),
            'playback_url' => $this->episodePlaybackUrl($episode),
            'podcast_title' => $this->sanitizeString($podcast->title),
            'author' => $this->sanitizeString($episode->author ?: $podcast->author),
            'type' => 'audio',
        ];
    }

    private function episodePlaybackUrl(Content $episode): ?string
    {
        if ($episode->stream_video_link || $episode->stream_url) {
            return URL::temporarySignedRoute('stream.view', now()->addMinutes(30), [
                'streamId' => $episode->uuid,
            ]);
        }

        if (!empty($episode->content_path)) {
            return $this->assetUrl($episode->content_path);
        }

        return null;
    }

    private function assetUrl(?string $path): ?string
    {
        if (!is_string($path) || trim($path) === '') {
            return null;
        }

        $path = trim($path);
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return Storage::disk(config('filesystems.default'))->url($path);
    }

    private function sanitizeString($value): ?string
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        return iconv('UTF-8', 'UTF-8//IGNORE', $value) ?: null;
    }
}
