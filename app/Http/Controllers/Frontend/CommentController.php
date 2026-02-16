<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Fetch comments for a given content item (UUID)
     */
  public function fetchComments(string $commentableType, string $commentableId)
{
    $modelClass = 'App\\Models\\Content'; // all commentables are Content
    $comments = Comment::where('commentable_type', $modelClass)
        ->where('commentable_id', $commentableId)
        ->with(['user', 'likes']) // eager load user and likes
        ->orderBy('created_at', 'desc')
        ->get();
dd($comments);  
    return view('Frontend.includes.components.partials.video-comments', [
        'comments' => $comments,
        'commentableType' => $commentableType,
        'commentableId' => $commentableId,
    ])->render();
}


    /**
     * Post a comment on a content item (UUID)
     */
  public function postComment(Request $request, string $contentGroup, string $uuid)
{
    // Ensure user is authenticated
    if (!Auth::check()) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        return redirect()->route('login')->with('error', 'Please login to post a comment.');
    }

    // Validate input
    $request->validate([
        'comment' => 'required|string|max:2000',
    ]);

    $user = Auth::user();

    // Find the content by UUID and content group
    $content = Content::where('uuid', $uuid)
        ->where('content_group', $contentGroup)
        ->firstOrFail();

    try {
        // Create the comment
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_type' => Content::class,
            'commentable_id' => $content->uuid,
            'comment' => htmlspecialchars($request->input('comment')),
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax()) {
            // Render the comment HTML partial
            $html = view('Frontend.includes.components.partials.single-comment', [
                'comment' => $comment
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html,
            ]);
        }

        return redirect()->back()->with('success', 'Comment posted.');
    } catch (\Exception $e) {
        Log::error('Failed to post comment: ' . $e->getMessage());
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to post comment'], 500);
        }
        return redirect()->back()->with('error', 'Failed to post comment.');
    }
}

    /**
     * Like a comment
     */
    public function like(string $commentId)
    {
        return $this->handleReaction($commentId, 'like');
    }

    /**
     * Dislike a comment
     */
    public function dislike(string $commentId)
    {
        return $this->handleReaction($commentId, 'dislike');
    }

    /**
     * Handle like/dislike toggling
     */
    private function handleReaction(string $commentId, string $type)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

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
    private function likeResponse(string $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        return response()->json([
            'success' => true,
            'likes' => $comment->likes()->where('type', 'like')->count(),
            'dislikes' => $comment->likes()->where('type', 'dislike')->count(),
        ]);
    }

}
