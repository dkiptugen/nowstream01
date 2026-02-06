<?php

	namespace App\Http\Controllers\Frontend;

	use Illuminate\Support\Facades\Auth;
	use App\Models\WatchHistory;
	use App\Models\Video;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use App\Models\Channel;
	use App\Models\Comment;
	use Illuminate\Database\Eloquent\ModelNotFoundException;

	class OldStreamVideoController extends Controller
		{
			public function index()
				{
					$videos                   = Content::where('type', 'video')->skip(4)->take(14)->get();
					$this->data['videos']     = $videos;
					$top_videos               = Content::where('type', 'video')->take(4)->get();
					$this->data['top_videos'] = $top_videos;
					return view('Frontend.modules.videos.index', $this->data);
				}

			public function continue()
				{
					$user         = Auth::user();
					$watchHistory = $user->watchHistory()->with('video')->latest()->limit(10)->get();
					return view('Frontend.modules.videos.continue', compact('user', 'watchHistory'));
				}

				public function newvideo()
					{

								return view('Frontend.modules.videos.newvideo', $this->data);

					}
					public function show($id)
						{
							try
								{
									$this->data['video'] =  Content::where('type', 'video')->where('id', $id)->first();


									$this->data['channels']      = Channel::where('status', 1)->take(8)->get();
									$this->data['relatedVideos'] = Content::where('type', 'video')->where('id', '!=', $id)->take(4)->get();
									$this->data['comments']      = $this->data['video']->comments()->with('user')->get();

									// Record watch history
									$this->recordWatchHistory($this->data['video']);

									return view('Frontend.modules.videos.video', $this->data);
								}
							catch (ModelNotFoundException $e)
								{
									abort(404, 'Video not found');
								}
						}

				protected function recordWatchHistory($video)
				{
					$user = Auth::user();
					if ($user && $video) {
						WatchHistory::updateOrCreate(
							[
								'user_id' => $user->id,
								'video_id' => $video->id,
							],
							[
								'watched_at' => now(),
							]
						);
					}
				}
				public function recordWatchHistoryAjax(Request $request)
				{
					$user = Auth::user();

					if ($user)
						{
							$video = Content::where('type', 'video')->findOrFail($request->input('video_id'));

							WatchHistory::updateOrCreate(
								[
									'user_id'  => $user->id,
									'video_id' => $video->id,
								],
								[
									'watched_at'     => now(),
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

					if ($user)
						{
							$watchHistory = WatchHistory::where('user_id', $user->id)
							                            ->with('video')
							                            ->latest('watched_at')
							                            ->paginate(10); // Adjust pagination as needed

							return view('Frontend.modules.videos.continue', compact('watchHistory'));
						}

					return redirect()->route('user.login')->with('error', 'You must be logged in to view watched videos.');
				}

			public function postComment(Request $request, $commentableType, $commentableId)
				{
					$user = Auth::user();

					$comment                   = new Comment();
					$comment->user_id          = $user->id;
					$comment->commentable_type = 'App\\Models\\' . ucfirst($commentableType);
					$comment->commentable_id   = $commentableId;
					$comment->comment          = $request->input('comment');
					$comment->save();

					if ($request->ajax())
						{
							return response()->json([
								                        'success'    => true,
								                        'comment'    => $comment->comment,
								                        'user_name'  => $user->name,
								                        'user_image' => $user->image ? asset('storage/' . $user->image) : asset('assets/images/avatars/avatar-2.png')
							                        ]);
						}

					return redirect()->back()->with('success', 'Comment posted successfully!');
				}

			public function fetchComments($commentableType, $commentableId)
				{
					$comments = Comment::where('commentable_type', 'App\\Models\\' . ucfirst($commentableType))
					                   ->where('commentable_id', $commentableId)
					                   ->orderBy('created_at', 'desc')
					                   ->get();

					return view('Frontend.modules.videos.video', ['comments' => $comments])->render();
				}
		}
