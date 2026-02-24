<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'watchable_id',
        'watchable_type',
        'watch_duration',
        'watched_at'
    ];

    protected $casts = [
        'watched_at' => 'datetime',
    ];

    // Polymorphic relation: video, podcast, episode
    public function watchable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'uuid');
    }
}