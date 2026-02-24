<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_id',
        'watch_duration',
        'watched_at'
    ];

    protected $casts = [
        'watched_at' => 'datetime',
    ];

    // Polymorphic relation: video, podcast, episode
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Content relation (uses content_id -> contents.uuid)
     */
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'uuid');
    }
}