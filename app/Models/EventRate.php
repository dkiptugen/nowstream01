<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRate extends Model
{
    use HasFactory;
    protected $fillable = [
	    'event_id',
        'name',
        'cost',
        'date_from',
        'visible',
        'date_to',
        'reserved_currency_cost',
        'status',
        'has_bundles',
    ];
    protected $casts = ['date_from'=>'datetime','date_to'=>'datetime'];
    public function event(){
        return $this->belongsTo(Event::class);
    }
}
