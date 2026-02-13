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
            ->whereNull('parent_id')
            ->orderByDesc('views')
            ->paginate($perPage);
          
        $this->data['categories'] = Category::where('type', 'tv')->limit(6)->get(); 
        $this->data['toptvs'] = Content::where('type', 'tv')
        ->whereNull('parent_id')
        ->orderBy('views', 'desc')
        ->limit(6)
        ->get();

        return view('Frontend.modules.tvs.index', $this->data);
    }
}
