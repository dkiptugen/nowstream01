<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
        public function index()
        {
            $categories = Category::limit(16)->get();
            $tvs = Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->orderBy('views', 'desc')
            ->where('category_id', 1)  
            ->limit(6)
            ->get();
            return view('Frontend.modules.categories.index', compact('tvs', 'categories'));
        }
        public function show($slug)
        {
            $category = Category::where('slug', $slug)->firstOrFail();
            $contents = Content::whereHas('categories', function ($query) use ($category) {
                $query->where('id', $category->id);
            })->paginate(12);
            $podcasts = Content::where('content_group', 'podcast')
            ->whereNull('parent_id')
            ->orderBy('views', 'desc')
            ->limit(6)
            ->get();
    
            return view('Frontend.modules.categories.show', compact('category', 'contents', 'podcasts'));
         }
}
