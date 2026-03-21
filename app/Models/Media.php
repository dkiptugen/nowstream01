<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'name',
        'original_name',
        'disk',
        'directory',
        'path',
        'url',
        'mime_type',
        'extension',
        'size',
        'type',
        'is_image',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_image' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
