<?php
	
	namespace App\Http\Controllers\Frontend;
	
	use App\Models\Comment;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	
	class CommentController extends Controller
		{
			public function fetchComments($commentableType, $commentableId)
				{
					$comments = Comment::where('commentable_type', 'App\\Models\\' . ucfirst($commentableType))
					                   ->where('commentable_id', $commentableId)
					                   ->orderBy('created_at', 'desc')
					                   ->get();
					
					return view('Frontend.modules.videos.video', ['comments' => $comments])->render();
				}
		}
