<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Categories landing page
     */
    public function index()
    {
        $categories = Category::limit(16)->get(); 
        $tvs = Content::where('content_group', 'tv')
            ->whereNotNull('stream_url')
            ->where('category_id', 1)
            ->orderBy('views', 'desc')
            ->limit(6)
            ->get();

        return view('Frontend.modules.categories.index', compact('categories', 'tvs'));
    }

    /**
     * Single category (all content)
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $contents = Content::whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->latest()
            ->paginate(12);

        $podcasts = Content::where('content_group', 'podcast')
            ->whereNull('parent_id')
            ->orderBy('views', 'desc')
            ->limit(6)
            ->get();

        return view('Frontend.modules.categories.show', compact(
            'category',
            'contents',
            'podcasts'
        ));
    }

    /**
     * Category filtered by content group
     * Example:
     * /category/movies/tv
     * /category/music/podcast
     */
    public function contentCategory($slug, $contentGroup)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $contents = Content::where('content_group', $contentGroup)
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->latest()
            ->paginate(12);

        return view('Frontend.modules.categories.content-category', [
            'category' => $category,
            'contents' => $contents,
            'contentGroup' => $contentGroup
        ]);
    }
}
