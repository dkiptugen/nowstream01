<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CommentLike;

class CommentController extends Controller
{
    /**
     * Fetch comments for a given commentable model (UUID supported)
     */
    public function fetchComments(string $commentableType, string $commentableId)
    {
        $modelClass = 'App\\Models\\' . ucfirst($commentableType);

        $comments = Comment::where('commentable_type', $modelClass)
            ->where('commentable_id', $commentableId)
            ->orderBy('created_at', 'desc')
            ->with('user') // eager load users to avoid N+1
            ->get();

        // Return rendered HTML (for AJAX partial refresh)
        return view('Frontend.includes.components.partials.video-comments', [
            'comments' => $comments
        ])->render();
    }

    /**
     * Like a comment
     */
    public function like(int $commentId)
    {
        return $this->handleReaction($commentId, 'like');
    }

    /**
     * Dislike a comment
     */
    public function dislike(int $commentId)
    {
        return $this->handleReaction($commentId, 'dislike');
    }

    /**
     * Handle like/dislike toggling
     */
    private function handleReaction(int $commentId, string $type)
    {
        $userId = Auth::id();
        $existing = CommentLike::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete(); // remove existing reaction
            } else {
                $existing->update(['type' => $type]); // toggle reaction
            }
        } else {
            CommentLike::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'type' => $type,
            ]);
        }

        return $this->likeResponse($commentId);
    }

    /**
     * Return like/dislike counts for a comment
     */
    private function likeResponse(int $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        return response()->json([
            'success' => true,
            'likes' => $comment->likes()->where('type', 'like')->count(),
            'dislikes' => $comment->likes()->where('type', 'dislike')->count(),
        ]);
    }

    /**
     * Store a new comment (supports UUIDs)
     */
  public function postComment(Request $request, string $commentableType, string $commentableId)
{
    // 1. Authentication check
    if (!Auth::check()) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        return redirect()->route('login')->with('error', 'Please login to post a comment.');
    }

    $user = Auth::user();

    // 2. Validate input
    $request->validate([
        'comment' => 'required|string|max:2000',
    ]);

    // 3. Determine model class
    $modelClass = 'App\\Models\\' . ucfirst($commentableType);
    if (!class_exists($modelClass)) {
        abort(400, 'Invalid commentable type.');
    }

    // 4. Create comment safely
    $comment = Comment::create([
        'user_id' => $user->id,
        'commentable_type' => $modelClass,
        'commentable_id' => $commentableId,
        'comment' => htmlspecialchars($request->input('comment')), // sanitize input
    ]);

    // 5. Broadcast event if AJAX (for real-time updates)
    if ($request->ajax()) {
        broadcast(new \App\Events\NewComment($comment))->toOthers();

        return response()->json([
            'success' => true,
            'comment' => $comment->comment,
            'user_name' => $user->name,
            'user_image' => $user->image 
                ? asset('storage/' . $user->image) 
                : asset('assets/images/avatars/avatar-2.png'),
            'created_at' => $comment->created_at->diffForHumans(),
        ]);
    }

    // 6. Normal request redirect
    return redirect()->back()->with('success', 'Comment posted.');
}

}
