<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    use HasUuid;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey='uuid';
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
        return $this->hasMany(Content::class, 'channel_id', 'uuid');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'channel_id', 'id');
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
