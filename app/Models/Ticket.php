<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'event_id',
        'ticket_number',
        'type',
        'price',
        'is_used',
        'used_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->uuid = Str::uuid();
            $ticket->ticket_number = 'EVT-' . date('Y') . '-' . strtoupper(Str::random(6));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
    return $this->belongsTo(Event::class, 'event_id', 'uuid');
    }
}
