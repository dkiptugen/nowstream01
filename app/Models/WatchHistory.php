<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'watchable_id', 'watched_at', 'watch_duration'];

  
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function watchable()
    {
        return $this->morphTo();
    }

    public function video()
    {
        return $this->belongsTo(Video::class)->withDefault();
    }
}
