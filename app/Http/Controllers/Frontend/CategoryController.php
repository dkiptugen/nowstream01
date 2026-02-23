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
        // Get the category by slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Fetch TVs where content_group is 'tv' and genre JSON contains the category name
        $tvs = Content::where('content_group', 'tv')
            ->whereJsonContains('genre', strtolower($category->name)) // store genres in lowercase
            ->orderBy('views', 'desc')
            ->get();

        // Similarly, fetch radios and podcasts
        $radios = Content::where('content_group', 'radio')
            ->whereJsonContains('genre', strtolower($category->name))
            ->orderBy('views', 'desc')
            ->get();

        $podcasts = Content::where('content_group', 'podcast')
            ->whereJsonContains('genre', strtolower($category->name))
            ->orderBy('views', 'desc')
            ->get();

        return view('Frontend.modules.categories.show', compact(
            'category',
            'tvs',
            'radios',
            'podcasts'
        ));
    }
    public function genreTvs($genre)
    { 
        $contents = Content::whereJsonContains('genre', $genre)
            ->orderBy('views', 'desc')
            ->where('content_group', 'tv')
            ->paginate(12); 

        return view('Frontend.modules.genres.show', compact('genre', 'contents'));
    }
  public function genreRadios($genre)
{
    $contents = Content::where('content_group', 'radio')
        ->whereJsonContains('genre', $genre)
        ->orderBy('views', 'desc')
        ->paginate(12);

    return view('Frontend.modules.genres.show', compact('genre', 'contents'));
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
