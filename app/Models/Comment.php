<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;   
    protected $fillable = ['user_id', 'comment', 'commentable_id', 'commentable_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }
    public function likes()
{
    return $this->hasMany(CommentLike::class);
}

public function userLiked($userId)
{
    return $this->likes()->where('user_id', $userId)->first();
}

}
