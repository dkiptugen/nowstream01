<?php
	
	namespace App\Http\Controllers\Frontend;
	
	use App\Models\Comment;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	use App\Models\CommentLike;

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
				
public function like($commentId)
{
    $userId = Auth::id();

    $existing = CommentLike::where('comment_id', $commentId)
        ->where('user_id', $userId)
        ->first();

    // If already liked → remove like
    if ($existing && $existing->type === 'like') {
        $existing->delete();
    } else {
        // If disliked → remove dislike then add like
        if ($existing) $existing->delete();

        CommentLike::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'type' => 'like',
        ]);
    }

    return $this->likeResponse($commentId);
}

public function dislike($commentId)
{
    $userId = Auth::id();

    $existing = CommentLike::where('comment_id', $commentId)
        ->where('user_id', $userId)
        ->first();

    // If already disliked → remove dislike
    if ($existing && $existing->type === 'dislike') {
        $existing->delete();
    } else {
        // If liked → remove like then add dislike
        if ($existing) $existing->delete();

        CommentLike::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'type' => 'dislike',
        ]);
    }

    return $this->likeResponse($commentId);
}

private function likeResponse($commentId)
{
    $comment = Comment::findOrFail($commentId);

    return response()->json([
        'success' => true,
        'likes' => $comment->likes()->where('type', 'like')->count(),
        'dislikes' => $comment->likes()->where('type', 'dislike')->count(),
    ]);
}
		}
