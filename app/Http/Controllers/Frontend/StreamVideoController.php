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
     * Top videos + paginated list
     */
    public function index()
    {
        $topVideos = Content::where('content_group', 'video')
            ->orderBy('views', 'DESC')
            ->take(4)
            ->get();

        $videos = Content::where('content_group', 'video')
            ->latest()
            ->paginate(12);

        $channels = Channel::where('status', 1)->get();

        return view('Frontend.modules.videos.index', [
            'top_videos' => $topVideos,
            'videos' => $videos,
            'channels' => $channels,
        ]);
    }

    /**
     * Show single video by UUID
     */
  public function show(string $uuid, string $slug = null)
{
    try {
        $video = Content::where('content_group', 'video')
            ->where('uuid', $uuid) 
            ->with(['comments.user'])
            ->firstOrFail();

        // Increment views
        $video->increment('views');

        // Record watch history safely
        if (Auth::check() && $this->watchHistoryService) {
            try {
                $this->watchHistoryService->record($video);
            } catch (\Exception $e) {
                Log::warning('Watch history failed: ' . $e->getMessage());
            }
        }

        // Channels
        $channels = Channel::where('status', 1)->take(8)->get();

        // Related videos (same channel)
        $relatedVideos = Content::where('content_group', 'video')
            ->where('status', 1)
            ->where('channel_id', $video->channel_id)
            ->where('uuid', '!=', $video->uuid)
            ->latest()
            ->take(6)
            ->get();

        return view('Frontend.modules.videos.video', [
            'video' => $video,
            'channels' => $channels,
            'relatedVideos' => $relatedVideos,
            'comments' => $video->comments,
        ]);

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
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $path = storage_path('app/videos/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'Video not found');
        }

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

    /**
     * Post comment (video or stream)
     */
public function postComment(Request $request, string $commentableType, string $commentableId)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $modelClass = 'App\\Models\\' . ucfirst($commentableType);

        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_type' => $modelClass,
            'commentable_id' => $commentableId,
            'comment' => $request->comment,
        ]);

        // Broadcast if using websockets
        if ($request->ajax()) {
            broadcast(new NewComment($comment))->toOthers();

            return response()->json([
                'success' => true,
                'comment' => $comment->comment,
                'user_name' => $user->name,
                'user_image' => $user->image
                    ? asset('storage/' . $user->image)
                    : asset('assets/images/avatars/avatar-2.png'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment posted',
        ]);
    }

    /**
     * Record watch duration via AJAX
     */
    public function recordWatchHistoryAjax(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer',
            'watch_duration' => 'nullable|integer',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        try {
            $video = Content::where('type', 'video')
                ->findOrFail($request->video_id);

            $this->watchHistoryService->record(
                $video,
                $request->input('watch_duration', 0)
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::warning('AJAX watch history error: ' . $e->getMessage());
            return response()->json(['success' => false]);
        }
    }

    /**
     * Continue Watching page
     */
    public function continueWatching()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to continue watching.');
        }

        $watchHistory = $this->watchHistoryService
            ->getUserHistory(Content::class, 12);

        return view('Frontend.modules.videos.continue', compact('watchHistory'));
    }

    /**
     * Fetch comments via AJAX
     */
    public function fetchComments(string $commentableType, int $commentableId)
    {
        $modelClass = 'App\\Models\\' . ucfirst($commentableType);

        $comments = Comment::where('commentable_type', $modelClass)
            ->where('commentable_id', $commentableId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($comments);
    }
}
