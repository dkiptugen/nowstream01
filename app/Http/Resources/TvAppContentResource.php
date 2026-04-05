<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class TvAppContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $playbackUrl = null;

        if (in_array($this->content_group, ['livestream', 'tv', 'radio', 'podcast_episode'], true)
            && ($this->stream_video_link || $this->stream_url)) {
            $playbackUrl = URL::temporarySignedRoute('stream.view', now()->addMinutes(30), [
                'streamId' => $this->uuid,
            ]);
        } elseif ($this->content_group === 'video' && !empty($this->content_path)) {
            $playbackUrl = Storage::disk(config('filesystems.default'))->url($this->content_path);
        }

        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content_group' => $this->content_group,
            'thumbnail_url' => $this->thumbnail_url,
            'duration' => $this->duration,
            'language' => $this->language,
            'country' => $this->country,
            'author' => $this->author,
            'views' => (int) ($this->views ?? 0),
            'viewers' => (int) ($this->viewers ?? 0),
            'status' => (int) $this->status,
            'start_time' => optional($this->start_time)->toISOString(),
            'end_time' => optional($this->end_time)->toISOString(),
            'stream_url' => $this->stream_url,
            'stream_video_link' => $this->stream_video_link,
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
}
