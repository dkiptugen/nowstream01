<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TvAppContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $thumbnailUrl = $this->resolveAssetUrl($this->thumbnail_url);
        $streamUrl = $this->resolveStreamUrl();
        $playbackUrl = $this->resolvePlaybackUrl($streamUrl);

        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content_group' => $this->content_group,
            'thumbnail_url' => $thumbnailUrl,
            'duration' => $this->duration,
            'language' => $this->language,
            'country' => $this->country,
            'author' => $this->author,
            'views' => (int) ($this->views ?? 0),
            'viewers' => (int) ($this->viewers ?? 0),
            'status' => (int) $this->status,
            'start_time' => optional($this->start_time)->toISOString(),
            'end_time' => optional($this->end_time)->toISOString(),
            'stream_url' => $streamUrl,
            'stream_video_link' => $streamUrl,
            'playback_url' => $playbackUrl,
            'event' => $this->whenLoaded('event', function () {
                if (!$this->event) {
                    return null;
                }

                return [
                    'uuid' => $this->event->uuid,
                    'slug' => $this->event->slug,
                    'name' => $this->event->event_name,
                    'image' => $this->event->event_image,
                    'start_time' => optional($this->event->start_time)->toISOString(),
                    'end_time' => optional($this->event->end_time)->toISOString(),
                ];
            }),
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($category) {
                    return [
                        'uuid' => $category->uuid,
                        'name' => $category->name,
                        'slug' => $category->slug,
                    ];
                })->values();
            }),
            'region' => $this->whenLoaded('region', function () {
                if (!$this->region) {
                    return null;
                }

                return [
                    'id' => (int) $this->region->id,
                    'name' => $this->region->name,
                    'code' => $this->region->code,
                ];
            }),
        ];
    }

    private function resolvePlaybackUrl(?string $streamUrl): ?string
    {
        if (in_array($this->content_group, ['livestream', 'tv', 'radio', 'podcast_episode'], true)) {
            return $streamUrl;
        }

        if ($this->content_group === 'video' && !empty($this->content_path)) {
            return $this->resolveAssetUrl($this->content_path);
        }

        return null;
    }

    private function resolveStreamUrl(): ?string
    {
        $url = $this->stream_video_link ?: $this->stream_url;

        if (!is_string($url) || trim($url) === '') {
            return null;
        }

        return trim($url);
    }

    private function resolveAssetUrl(?string $path): ?string
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
}
