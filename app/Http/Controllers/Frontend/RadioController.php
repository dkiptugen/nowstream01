<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheHelper;


class RadioController extends Controller
{
    public function index()
    {
        // Latest radios (paginated style alternative)

        $this->data['radios'] = Content::where('content_group', 'radio')
                ->whereNotNull('stream_url') 
            ->where('status', 1)
            ->paginate(30);
          
        $this->data['categories'] = Category::where('type', 'radio')->limit(6)->get(); 
        $this->data['topradios'] = Content::where('content_group', 'radio') 
        ->whereNotNull('stream_url')
        ->where('status', 1)
        ->orderBy('views', 'desc')
        ->limit(value: 16)
        ->get();

        return view('Frontend.modules.radios.index', $this->data);
    }
    public function show($uuid, $slug)
    {
        try {
            $radio = Cache::remember("radio_{$uuid}_{$slug}", now()->addDay(), function () use ($uuid, $slug) {
                return Content::where('uuid', $uuid)
                    ->where('slug', $slug)
                    ->where('content_group', 'radio')
            ->where('status', 1)
                    ->first();
            });
            $radio->increment('views'); // Increment view count 
            if (!$radio) {
                abort(404, 'radio not found');
            }

            // Related radios (exclude current)
            $related = Cache::remember("radio_related_{$uuid}", now()->addDay(), function () use ($uuid) {
                return Content::where('content_group', 'radio')
                    ->where('uuid', '!=', $uuid)
                    ->whereNotNull('stream_url')
                    ->where('status', 1)
                    ->latest()
                    ->take(6)
                    ->get();
            });
 

            return view('Frontend.modules.radios.show', [
                'radio'  => $radio,
                'related'  => $related, 
            ]);
        } catch (\Exception $e) {
            abort(404, 'radio not found');
        }
    }
}
