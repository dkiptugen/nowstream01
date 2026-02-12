<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function watchHistory()
    {
        return $this->hasMany(WatchHistory::class);
    } 
    public function subscribedChannels()
{
    return $this->belongsToMany(Channel::class, 'channel_user', 'user_id', 'channel_id');
}

    public function favoriteVideos()
{
    return $this->belongsToMany(Content::class, 'favorites')
                ->where('type', 'video')
                ->withTimestamps();
}
// User.php
public function channels()
{
    return $this->belongsToMany(Channel::class, 'channel_user', 'user_id', 'channel_id');
}


}
