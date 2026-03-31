<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Content;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) ($request->input('query') ?? $request->input('q') ?? ''));
        $sections = collect();
        $totalResults = 0;

        if ($query !== '') {
            $sections = collect([
                $this->searchEvents($query),
                $this->searchContents($query, 'livestream', 'Live Streams'),
                $this->searchContents($query, 'video', 'Videos'),
                $this->searchContents($query, 'podcast', 'Podcasts', true),
                $this->searchContents($query, 'radio', 'Radio'),
                $this->searchContents($query, 'tv', 'Live TV'),
                $this->searchProducts($query),
                $this->searchChannels($query),
            ])->filter(fn (array $section) => $section['count'] > 0)->values();

            $totalResults = (int) $sections->sum('count');
        }

        return view('Frontend.search', [
            'query' => $query,
            'sections' => $sections,
            'totalResults' => $totalResults,
        ]);
    }

    private function searchEvents(string $query): array
    {
        $items = Event::query()
            ->where('status', 1)
            ->where(function ($search) use ($query) {
                $search
                    ->where('event_name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('venue', 'like', "%{$query}%");
            })
            ->orderByDesc('start_time')
            ->limit(8)
            ->get()
            ->map(function (Event $event) {
                return [
                    'title' => $event->event_name,
                    'description' => $event->description ?: $event->venue,
                    'meta' => collect([$event->venue, optional($event->start_time)->format('d M Y')])
                        ->filter()
                        ->implode(' | '),
                    'url' => route('event.show', ['slug' => $event->slug]),
                    'image' => $event->event_image,
                    'type' => 'Event',
                ];
            });

        return $this->makeSection('Events', $items);
    }

    private function searchContents(string $query, string $group, string $label, bool $onlyParents = false): array
    {
        $items = Content::query()
            ->where('content_group', $group)
            ->where('status', 1)
            ->when($onlyParents, fn ($content) => $content->whereNull('parent_id'))
            ->where(function ($search) use ($query) {
                $search
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%");
            })
            ->orderByDesc('views')
            ->limit(8)
            ->get()
            ->map(fn (Content $content) => [
                'title' => $content->title,
                'description' => $content->description,
                'meta' => collect([$content->author, ucfirst(str_replace('_', ' ', $content->content_group))])
                    ->filter()
                    ->implode(' | '),
                'url' => $this->contentUrl($content),
                'image' => $content->thumbnail_url ?: $content->thumbnail,
                'type' => ucfirst(str_replace('_', ' ', $content->content_group)),
            ])
            ->filter(fn (array $item) => !empty($item['url']))
            ->values();

        return $this->makeSection($label, $items);
    }

    private function searchProducts(string $query): array
    {
        $items = Product::query()
            ->merch()
            ->active()
            ->with('payable')
            ->where(function ($search) use ($query) {
                $search
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(8)
            ->get()
            ->map(function (Product $product) {
                $payable = $product->payable?->event_name ?? $product->payable?->title;

                return [
                    'title' => $product->name,
                    'description' => $product->description,
                    'meta' => collect([
                        $payable,
                        ($product->currency ?? 'KES') . ' ' . number_format((float) $product->price, 2),
                    ])->filter()->implode(' | '),
                    'url' => route('shop.show', $product),
                    'image' => $product->image_url,
                    'type' => 'Merchandise',
                ];
            });

        return $this->makeSection('Merchandise', $items);
    }

    private function searchChannels(string $query): array
    {
        $channelQuery = Channel::query()
            ->where('name', 'like', "%{$query}%");

        if (Schema::hasColumn('channels', 'status')) {
            $channelQuery->where('status', 1);
        }

        $items = $channelQuery
            ->limit(8)
            ->get()
            ->map(function (Channel $channel) {
                return [
                    'title' => $channel->name,
                    'description' => $channel->description ?? null,
                    'meta' => 'Channel',
                    'url' => url("/channel/{$channel->id}/{$channel->name}"),
                    'image' => $channel->thumbnail ?? null,
                    'type' => 'Channel',
                ];
            });

        return $this->makeSection('Channels', $items);
    }

    private function contentUrl(Content $content): ?string
    {
        return match ($content->content_group) {
            'livestream' => $content->slug ? route('stream.show', ['slug' => $content->slug]) : null,
            'video' => route('video.show', ['uuid' => $content->uuid, 'slug' => $content->slug]),
            'podcast' => $content->slug ? route('podcast.show', ['slug' => $content->slug]) : null,
            'radio' => $content->slug ? route('radio.show', ['slug' => $content->slug]) : null,
            'tv' => $content->slug ? route('tv.show', ['slug' => $content->slug]) : null,
            default => null,
        };
    }

    private function makeSection(string $title, Collection $items): array
    {
        return [
            'title' => $title,
            'count' => $items->count(),
            'items' => $items,
        ];
    }
}
