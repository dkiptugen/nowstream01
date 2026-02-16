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
        // Top videos (cache 10 min)
        $top_videos = Cache::remember('top_videos_home', now()->addMinutes(10), function () {
            return Content::where('content_group', 'video')
                ->orderByDesc('views')
                ->take(4)
                ->get();
        });

        // Paginated videos (cache per page)
        $page = request()->get('page', 1);
        $videos = Cache::remember("videos_page_{$page}", now()->addMinutes(10), function () {
            return Content::where('content_group', 'video')
                ->latest()
                ->paginate(12);
        });

        // Channels (cache)
        $channels = Cache::remember('channels_homepage', now()->addMinutes(10), function () {
            return Channel::where('status', 1)->get();
        });

        return view('Frontend.modules.videos.index', compact('top_videos', 'videos', 'channels'));
    }

    /**
     * Show single video by UUID
     */
    public function show(string $uuid, string $slug = null)
    {
        try {
            // Video detail (cache per video)
            $video = Cache::remember("video_{$uuid}", now()->addMinutes(30), function () use ($uuid) {
                return Content::where('content_group', 'video')
                    ->where('uuid', $uuid)
                    ->with(['comments.user'])
                    ->firstOrFail();
            });

            // Increment views live
            $video->increment('views');

            // Record watch history per user
            if (Auth::check() && $this->watchHistoryService) {
                try {
                    $this->watchHistoryService->record($video);
                } catch (\Exception $e) {
                    Log::warning('Watch history failed: ' . $e->getMessage());
                }
            }

            // Channels (cache)
            $channels = Cache::remember('channels_homepage_preview', now()->addMinutes(10), function () {
                return Channel::where('status', 1)->take(8)->get();
            });

            // Related videos per video (cache 30 min)
            $related = Cache::remember("related_videos_{$uuid}", now()->addMinutes(30), function () use ($video) {
                return Content::where('content_group', 'video')
                    ->where('status', 1)
                    ->where('channel_id', $video->channel_id)
                    ->where('uuid', '!=', $video->uuid)
                    ->latest()
                    ->take(6)
                    ->get();
            });
            $comments = $video->comments()->latest()->get();

             // Country name mapping (cache)
             $iso = strtoupper($video->country ?? 'KE');
             $countryName = Cache::remember("country_name_{$iso}", now()->addDay(), function () use ($iso) {
            $path = resource_path('data/countries.json');
            if (!File::exists($path)) {
                return [];
            });
            return view('Frontend.modules.videos.video', compact('video', 'channels', 'related', 'comments'));
        } catch (ModelNotFoundException $e) {
            abort(404, 'Video not found');
        } catch (\Exception $e) {
            Log::error('Video show error: ' . $e->getMessage());
            abort(500, 'Server error');
        }
    }

    /**
     * Secure video file streaming
     */
    public function getVideo(string $filename)
    {
        if (!Auth::check()) abort(403, 'Unauthorized');

        $path = storage_path('app/videos/' . $filename);
        if (!File::exists($path)) abort(404, 'Video not found');

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
