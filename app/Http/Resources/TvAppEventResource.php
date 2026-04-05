<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvAppEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->event_name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->event_image,
            'venue' => $this->venue,
            'status' => (int) $this->status,
            'views' => (int) ($this->views ?? 0),
            'is_featured' => (bool) ($this->is_featured ?? false),
            'publish_date' => optional($this->publish_date)->toISOString(),
            'start_time' => optional($this->start_time)->toISOString(),
            'end_time' => optional($this->end_time)->toISOString(),
            'streams_count' => isset($this->streams_count) ? (int) $this->streams_count : null,
        ];
    }
}
