<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    public function streams()
    {
        return $this->hasMany(Stream::class);
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function system_users()
    {
        return $this->belongsToMany(SystemUser::class)->using(SystemUserChannel::class);
    }
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'channel_user');
    }
    public function getSubscriberCountAttribute()
    {
        return $this->subscribers()->count();
    }
}
