<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Comment;
use App\Models\Content;
use App\Models\Category;
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
        $user = Auth::user();
        $modelClass = 'App\\Models\\' . ucfirst($commentableType);
$item = Content::where('uuid', $commentableId)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Content not found.'], 404);
        }
        dd($item);
         $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_type' => $modelClass,
            'commentable_id' => $commentableId,
            'comment' => $request->input('comment'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->comment,
                'user_name' => $user->name,
                'user_image' => $user->image ? asset('storage/' . $user->image) : asset('assets/images/avatars/avatar-2.png'),
            ]);
        }

        return redirect()->back()->with('success', 'Comment posted.');
    }
}
