<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    // Specify the new table
    protected $table = 'new_comment_likes';

    // Allow mass assignment
    protected $fillable = ['comment_id', 'user_id', 'type'];

    /**
     * Relation to the comment
     * Since comment_id is now a UUID (char 36), specify it explicitly
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'uuid');
    }

    /**
     * Relation to the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
