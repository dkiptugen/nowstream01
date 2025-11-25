<?php

namespace App\Http\Controllers\Frontend;

use App\Events\NewComment;
use App\Libs\Stream;
use Illuminate\Support\Facades\Auth;
use App\Models\WatchHistory;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

class StreamVideoController extends Controller
{
	public function index()
	{
		$videos = Video::skip(4)->take(14)->get();
		$this->data['videos'] = $videos;
		$top_videos = Video::take(4)->get();
		$this->data['top_videos'] = $top_videos;
		return view('Frontend.modules.videos.index', $this->data);
	}

	public function continue()
	{
		$user = Auth::user();
		$watchHistory = $user->watchHistory()->with('video')->latest()->limit(10)->get();
		return view('Frontend.modules.videos.continue', compact('user', 'watchHistory'));
	}

	public function newvideo()
	{

		return view('Frontend.modules.videos.newvideo', $this->data);

	}

	// public function get_video ( $filename)
	// 	{
	// 		// Ensure the user is authenticated
	// 		if (!auth ()->check ())
	// 			{
	// 				abort (403, 'Unauthorized action.');
	// 			}

	// 		$path = storage_path('app/videos/'. $filename);

	// 		if (!File::exists($path)) {
	// 			abort(404);
	// 		}

	// 		$stream = new Stream($path);

	// 		return response()->file($path,$stream); //
	// 	}

	public function get_video($filename)
	{
		// Ensure the user is authenticated
		if (!auth()->check()) {
			abort(403, 'Unauthorized action.');
		}

		// Construct the full path to the video file
		$path = storage_path('app/videos/' . $filename);

		// Check if the file exists
		if (!File::exists($path)) {
			abort(404, 'Video not found.');
		}

		// Get the file size and MIME type
		$fileSize = File::size($path);
		$mimeType = File::mimeType($path);

		// Stream the video file
		return response()->stream(function () use ($path) {
			$stream = fopen($path, 'rb');
			while (!feof($stream)) {
				echo fread($stream, 8192);
				flush(); // Flush the buffer
			}
			fclose($stream);
		}, 200, [
			'Content-Type' => $mimeType,
			'Content-Length' => $fileSize,
			'Content-Disposition' => 'inline; filename="' . $filename . '"',
		]);
	}
	public function show($id)
	{
		try {
			$video = Video::with('comments.user')->findOrFail($id);
			$this->data = [
				'video' => $video,
				'channels' => Channel::where('status', 1)->take(8)->get(),
				'relatedVideos' => Video::where('id', '!=', $id)->take(4)->get(),
				'comments' => $video->comments()->with('user')->get(),
			];

			// Record watch history
			$this->recordWatchHistory($video);

			return view('Frontend.modules.videos.video', $this->data);
		} catch (ModelNotFoundException $e) {
			abort(404, 'Video not found');
		}
	}

	protected function recordWatchHistory($video)
	{
		$user = Auth::user();
		if ($user && $video) {
			$video->watch()->updateOrCreate(
				['user_id' => $user->id],
				['watchable_id' => $video->id],
				['watched_at' => now()]
			);
		}
	}

	public function recordWatchHistoryAjax(Request $request)
	{
		$user = Auth::user();

		if ($user) {
			$video = Video::findOrFail($request->input('video_id'));
			$video->watch()->updateOrCreate(
				['user_id' => $user->id],
				[
					'watched_at' => now(),
					'watch_duration' => $request->input('watch_duration', 0),
				]
			);

			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false], 401);
	}

	public function watchedVideos()
	{
		$user = Auth::user();

		if ($user) {
			$watchHistory = WatchHistory::where('user_id', $user->id)
				->with('watchable')  // Eager load the watchable relationship
				->latest('watched_at')
				->where('watchable_type', 'App\Models\Video')
				->paginate(10); 

			$streamWatchHistory = WatchHistory::where('user_id', $user->id)
				->with('watchable')  // Eager load the watchable relationship
				->latest('watched_at')
				->where('watchable_type', 'App\Models\Stream')
				->paginate(10); // Adjust pagination as needed

				

			return view('Frontend.modules.videos.continue', compact('watchHistory', 'streamWatchHistory'));
		}

		return redirect()->route('login')->with(
			'error',
			'You must be logged in to view watched videos.'
		);
	}


	public function postComment(Request $request, $commentableType, $commentableId)
	{
		$user = Auth::user();

		$comment = new Comment();
		$comment->user_id = $user->id;
		$comment->commentable_type = 'App\\Models\\' . ucfirst($commentableType);
		$comment->commentable_id = $commentableId;
		$comment->comment = $request->input('comment');
		$comment->save();

		if ($request->ajax()) {
			event(new NewComment($comment));
			return response()->json([
				'success' => true,
				'comment' => $comment->comment,
				'user_name' => $user->name,
				'user_image' => $user->image ? asset('storage/' . $user->image) : asset('assets/images/avatars/avatar-2.png')
			]);
		}

		return redirect()->back()->with('success', 'Comment posted successfully!');
	}

	public function fetchComments($commentableType, $commentableId)
	{
		$comments = Comment::where(
			'commentable_type',
			'App\\Models\\' . ucfirst($commentableType)
		)->where(
				'commentable_id',
				$commentableId
			)->orderBy(
				'created_at',
				'desc'
			)->get();

		return view('Frontend.modules.videos.video', ['comments' => $comments])->render();
	}
}
