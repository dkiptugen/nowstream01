<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheHelper;

class TVController extends Controller
{
    public function index()
    {
        // Latest tvs (paginated style alternative)
        $perPage = 6;

        $this->data['tvs'] = Content::where('content_group', 'tv')
            ->where('status', 1)
            ->paginate($perPage);
          
        $this->data['categories'] = Category::where('type', 'tv')->limit(6)->get(); 
        $this->data['toptvs'] = Content::where('type', 'tv')
        ->limit(6)
        ->get();

        return view('Frontend.modules.tvs.index', $this->data);
    }
    public function show($uuid, $slug)
    {
        try {
            $tv = Cache::remember("tv_{$uuid}_{$slug}", now()->addDay(), function () use ($uuid, $slug) {
                return Content::where('uuid', $uuid)
                    ->where('slug', $slug)
                    ->where('content_group', 'tv')
                    ->first();
            });
            $tv->increment('views'); // Increment view count 
            if (!$tv) {
                abort(404, 'tv not found');
            }

            // Related tvs (exclude current)
            $related = Cache::remember("tv_related_{$uuid}", now()->addDay(), function () use ($uuid) {
                return Content::where('content_group', 'tv')
                    ->where('uuid', '!=', $uuid)
                    ->latest()
                    ->take(6)
                    ->get();
            });
 

            return view('Frontend.modules.tvs.show', [
                'tv'  => $tv,
                'related'  => $related, 
            ]);
        } catch (\Exception $e) {
            abort(404, 'tv not found');
        }
    }
}
