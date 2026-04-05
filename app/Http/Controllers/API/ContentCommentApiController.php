<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContentCommentApiController extends Controller
{
    private const JSON_FLAGS = JSON_INVALID_UTF8_SUBSTITUTE;

    public function index(Request $request, string $contentId)
    {
        $content = Content::findOrFail($contentId);
        $limit = max(1, min((int) $request->query('limit', 50), 100));

        $comments = Comment::query()
            ->where('commentable_type', Content::class)
            ->where('commentable_id', $content->uuid)
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json(
            [
                'success' => true,
                'content' => [
                    'uuid' => $content->uuid,
                    'title' => $this->sanitizeString($content->title),
                    'content_group' => $content->content_group,
                ],
                'comments' => $comments->map(fn (Comment $comment) => $this->serializeComment($comment))->values(),
            ],
            200,
            [],
            self::JSON_FLAGS
        );
    }

    public function store(Request $request, string $contentId)
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $content = Content::findOrFail($contentId);
        $user = Auth::user();

        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_type' => Content::class,
            'commentable_id' => $content->uuid,
            'comment' => trim($request->input('comment')),
        ])->load('user');

        return response()->json(
            [
                'success' => true,
                'message' => 'Comment posted successfully.',
                'comment' => $this->serializeComment($comment),
            ],
            201,
            [],
            self::JSON_FLAGS
        );
    }

    private function serializeComment(Comment $comment): array
    {
        $user = $comment->user;

        return [
            'id' => $comment->getKey(),
            'comment' => $this->sanitizeString($comment->comment) ?? '',
            'created_at' => optional($comment->created_at)?->toISOString(),
            'created_at_human' => optional($comment->created_at)?->diffForHumans(),
            'user' => [
                'id' => $user?->getKey(),
                'name' => $this->sanitizeString($user?->name) ?? 'Anonymous',
                'image' => $this->resolveUserImage($user?->image),
            ],
        ];
    }

    private function sanitizeString($value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        return iconv('UTF-8', 'UTF-8//IGNORE', trim($value)) ?: null;
    }

    private function resolveUserImage(?string $path): ?string
    {
        if (!is_string($path) || trim($path) === '') {
            return null;
        }

        $path = trim($path);
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return Storage::disk(config('filesystems.default'))->url($path);
    }
}
