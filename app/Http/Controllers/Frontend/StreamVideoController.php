<?php

namespace App\Http\Controllers\Frontend;

use App\Events\NewComment;
use App\Libs\Stream;
use App\Models\WatchHistory;
use App\Models\Video;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Content;
use App\Services\WatchHistoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;

class StreamVideoController extends Controller
{
    protected WatchHistoryService $watchHistoryService;

    public function __construct(WatchHistoryService $watchHistoryService)
    {
        $this->watchHistoryService = $watchHistoryService;
    }

    /**
     * Display top and other videos
     */
    public function index()
    {
        $topVideos = Content::where('type', 'video')->orderBy('views', 'DESC')->take(4)->get();
        $videos = Content::where('type', 'video')->skip(4)->paginate(12);
		$channels = Channel::all();

        return view('Frontend.modules.videos.index', [
            'top_videos' => $topVideos,
            'videos' => $videos,
            'channels' => $channels,
        ]);
    }

    /**
     * Show new videos page
     */
    public function newvideo()
    {
        return view('Frontend.modules.videos.newvideo');
    }

    /**
     * Show continue watching page (last 10 watched)
     */
    public function continue()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view watched videos.');
        }

        $watchHistory = $user->watchHistory()->with('video')->latest()->limit(10)->get();
        return view('Frontend.modules.videos.continue', compact('user', 'watchHistory'));
    }

    /**
     * Content video file to authenticated users
     */
    public function get_video(string $filename)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        $path = storage_path('app/videos/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'Video not found.');
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
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Display a single video with comments, channels, and related videos
     */
    public function show(int $id)
    {
        try {
            // Load the video with comments and user relationship
            $video = Content::where('type', 'video')->with(['comments.user' => function ($q) {
                $q->oldest();
            }])->findOrFail($id);

            $comments = $video->comments;
            // Increment the views count
            $video->increment('views');

            // Load additional data
            $channels = Channel::where('status', 1)->take(8)->get();
            $relatedVideos = Content::where('type', 'video')->where('id', '!=', $id)->take(4)->get();

            // Record watch history (assuming you have a service for it)
            $this->watchHistoryService->record($video);

            return view('Frontend.modules.videos.video', compact('video', 'channels', 'relatedVideos', 'comments'));
        } catch (ModelNotFoundException) {
            abort(404, 'Video not found.');
        }
    }

    /**
     * Post a comment to a video or stream
     */
    public function postComment(Request $request, string $commentableType, int $commentableId)
    {
        $user = Auth::user();
        $modelClass = 'App\\Models\\' . ucfirst($commentableType);

        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_type' => $modelClass,
            'commentable_id' => $commentableId,
            'comment' => $request->input('comment'),
        ]);
        if ($request->ajax()) {
            broadcast(new NewComment($comment))->toOthers();

            return response()->json([
                'success' => true,
                'comment' => $comment->comment,
                'user_name' => $user->name,
                'user_image' => $user->image ? asset('storage/' . $user->image) : asset('assets/images/avatars/avatar-2.png'),
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Comment posted',
        ]);

    }

    /**
     * Record watch history via AJAX
     */
    public function recordWatchHistoryAjax(Request $request)
    {
        $video = Content::where('type', 'video')->findOrFail($request->input('video_id'));
        $this->watchHistoryService->record($video, $request->input('watch_duration', 0));

        return response()->json(['success' => true]);
    }

    /**
     * List all watched videos and streams
     */
    public function watchedVideos()
    {
        $videoHistory = $this->watchHistoryService->getUserHistory(Content::where('type', 'video')->class);
        $streamHistory = $this->watchHistoryService->getUserHistory('App\Models\Content');

        if (!$videoHistory && !$streamHistory) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view watched videos.');
        }

        return view('Frontend.modules.videos.continue', [
            'watchHistory' => $videoHistory,
            'streamWatchHistory' => $streamHistory,
        ]);
    }


    /**
     * Fetch comments for a given video or stream
     */
    public function fetchComments(string $commentableType, int $commentableId)
    {
        $modelClass = 'App\\Models\\' . ucfirst($commentableType);

        $comments = Comment::where('commentable_type', $modelClass)
        ->where('commentable_id', $commentableId)
        ->with('user')
        ->orderBy('created_at', 'asc') // oldest first, newest last
        ->get();


        return view('Frontend.modules.videos.video', compact('comments'))->render();
    }
}
