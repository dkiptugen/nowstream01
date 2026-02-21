<?php

namespace App\Http\Controllers\Frontend;

use App\Events\NewComment;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Content;
use App\Services\WatchHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StreamVideoController extends Controller
{
    protected $watchHistoryService;

    public function __construct(WatchHistoryService $watchHistoryService)
    {
        $this->watchHistoryService = $watchHistoryService;
    }

    /**
     * Videos homepage
     */
  public function index()
{
    $page = request()->get('page', 1);

    $top_videos = Cache::remember('videos:top', 600, function () {
        return Content::select('uuid','slug','title','thumbnail_url','views')
            ->where('content_group', 'video') 
            ->orderByDesc('views')
            ->limit(4)
            ->get();
    });

    $videos = Cache::remember("videos:page:{$page}", 600, function () {
        return Content::select('uuid','slug','title','thumbnail_url','created_at')
            ->where('content_group', 'video') 
            ->latest()
            ->paginate(12);
    });

    $channels = Cache::remember('channels:active', 1800, function () {
        return Channel::select('uuid','name','cover_image', 'thumbnail')
            ->where('status', 1)
            ->get();
    });

    return view('Frontend.modules.videos.index', compact('top_videos','videos','channels'));
}

    /**
     * Show single video by UUID
     */
   public function show(string $uuid, string $slug = null)
{
    try {

        $video = Cache::remember("video:{$uuid}", 1800, function () use ($uuid) {
            return Content::select('*')
                ->where('content_group', 'video')
                ->where('uuid', $uuid)
                ->where('status', 1)
                ->firstOrFail();
        });

        // Increment views without invalidating cache
        Content::where('uuid', $uuid)->increment('views');

        // Watch history
        if (Auth::check()) {
            $this->watchHistoryService?->record($video);
        }

        // Comments (separate cache – better isolation)
        $comments = Cache::remember("video:comments:{$uuid}", 600, function () use ($uuid) {
            return Comment::with('user:id,name,avatar')
                ->where('uuid', $uuid)
                ->orderBy('created_at', 'asc')
                ->get();
        });

        // Related videos (index friendly)
        $relatedVideos = Cache::remember("video:related:{$uuid}", 1800, function () use ($uuid) {
            return Content::select('uuid','slug','title','thumbnail_url')
                ->where('content_group', 'video')
                ->where('status', 1)
                ->where('uuid', '!=', $uuid)
                ->latest()
                ->limit(6)
                ->get();
        });

        // Channels preview
        $channels = Cache::remember('channels:preview', 1800, function () {
            return Channel::select('uuid','name','thumbnail')
                ->where('status', 1)
                ->limit(8)
                ->get();
        });

        // Load countries file ONCE globally (not per ISO)
        $countryName = Cache::remember('countries:all', 86400, function () {
            $path = resource_path('data/countries.json');
            if (!File::exists($path)) {
                return [];
            }
            return json_decode(file_get_contents($path), true);
        });

        $iso = strtoupper($video->country ?? 'KE');
        $countryName = $countryName[$iso] ?? $iso;

        return view('Frontend.modules.videos.video', compact(
            'video',
            'channels',
            'relatedVideos',
            'comments',
            'countryName'
        ));

    } catch (ModelNotFoundException $e) {
        abort(404);
    } catch (\Throwable $e) {
        Log::error('Video show error: '.$e->getMessage());
        abort(500);
    }
}


    /**
     * Secure video file streaming
     */
    public function getVideo(string $filename)
    {
        if (!Auth::check())
            abort(403, 'Unauthorized');

        $path = storage_path('app/videos/' . $filename);
        if (!File::exists($path))
            abort(404, 'Video not found');

        $fileSize = File::size($path);
        $mimeType = File::mimeType($path);

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 8192);
                flush();
            }
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline',
        ]);
    }

    // Comments, watch history AJAX, continue watching, fetch comments
    // remain the same as original since they are user-specific
}
