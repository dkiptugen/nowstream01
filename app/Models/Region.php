<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capital',
        'currency',
        'currency_code',
        'currency_symbol',
        'language',
        'language_code',
        'flag',
        'mnos',
    ];

    public function contents()
    {
        return $this->hasMany(Content::class, 'region_id');
    }
}
