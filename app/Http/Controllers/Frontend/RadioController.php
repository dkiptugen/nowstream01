<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class RadioController extends Controller
{
    protected $data = [];

    /**
     * Radio listing page
     */
    public function index(Request $request)
    {
        $perPage = 30;
        $page = $request->get('page', 1); // pagination page

        // Paginated radios per page (cached)
        $radios = Cache::remember("radios_page_{$page}", now()->addMinutes(10), function () use ($perPage) {
            return Content::where('content_group', 'radio')
                ->whereNotNull('stream_url')
                ->where('status', 1)
                ->paginate($perPage);
        });

        // Categories (cache)
        $categories = Cache::remember('radio_categories', 3600, function () {
            return Category::where('type', 'radio')
                ->limit(6)
                ->get();
        });

        // Top radios (cache)
        $topradios = Cache::remember('top_radios', 600, function () {
            return Content::where('content_group', 'radio')
                ->whereNotNull('stream_url')
                ->where('status', 1)
                ->orderByDesc('views')
                ->limit(16)
                ->get();
        });

        $this->data = compact('radios', 'categories', 'topradios');

        return view('Frontend.modules.radios.index', $this->data);
    }

    /**
     * Single radio page
     */
    public function show($slug)
    {
        try {
            // Cache single radio detail
            $radio = Cache::remember("radio_{$slug}", now()->addDay(), function () use ($slug) {
                return Content::where('slug', $slug)
                    ->where('slug', $slug)
                    ->where('content_group', 'radio')
                    ->where('status', 1)
                    ->first();
            });

            if (!$radio) abort(404, 'Radio not found');

            // Increment live views (not cached)
            $radio->increment('views');
            $uuid = $radio->uuid ?? null;
            // Related radios (cache)
            $related = Cache::remember("radio_related_{$uuid}", now()->addDay(), function () use ($uuid) {
                return Content::where('content_group', 'radio')
                    ->where('uuid', '!=', $uuid)
                    ->whereNotNull('stream_url')
                    ->where('status', 1)
                    ->latest()
                    ->take(6)
                    ->get();
            });

            return view('Frontend.modules.radios.show', compact('radio', 'related'));
        } catch (\Exception $e) {
            abort(404, 'Radio not found');
        }
    }
}
