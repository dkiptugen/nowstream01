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

    public function genretvs(Request $request, $genre)
    {
        $perPage = 30;
        $page = $request->get('page', 1);

        $genre = urldecode($genre);
        $genre = str_replace('-', ' ', $genre);
        $genre = ucwords($genre);

        $tvs = Content::where('content_group', 'tv')
            ->where(function ($q) use ($genre) {
                // Matches: ["Balada","Pop"]
                $q->where('genre', 'like', '%"' . $genre . '"%')

                    // Matches: Balada, Pop
                    ->orWhere('genre', 'like', '%' . $genre . '%');
            })
            ->orderBy('views', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        // AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'Frontend.includes.components.partials.tv-items',
                    compact('tvs')
                )->render(),
                'hasMore' => $tvs->hasMorePages()
            ]);
        }

        return view('Frontend.modules.genres.show', compact('genre', 'tvs'));
    }
    public function genreRadios(Request $request, $genre)
    {
        $perPage = 30;
        $page = $request->get('page', 1);

        $genre = urldecode($genre);
        $genre = str_replace('-', ' ', $genre);
        $genre = ucwords($genre);

        $radios = Content::where('content_group', 'radio')
            ->where(function ($q) use ($genre) {
                // Matches: ["Balada","Pop"]
                $q->where('genre', 'like', '%"' . $genre . '"%')

                    // Matches: Balada, Pop
                    ->orWhere('genre', 'like', '%' . $genre . '%');
            })
            ->orderBy('views', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        // AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'Frontend.includes.components.partials.radio-items',
                    compact('radios')
                )->render(),
                'hasMore' => $radios->hasMorePages()
            ]);
        }

        return view('Frontend.modules.genres.radio', compact('genre', 'radios'));
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
