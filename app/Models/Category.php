<?php

namespace App\Models;

use App\Casts\JsonCast;
use App\Traits\HasUuid;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
    {
        use HasUuid;
        use Sluggable;

        protected $keyType      = 'string';
        public    $incrementing = false;
        protected $primaryKey   = 'uuid';

        public function sluggable(): array
            {
                return [
                    'slug' => [
                        'source' => 'name'
                    ]
                ];
            }

        protected $casts    = ['type' => JsonCast::class];
        protected $fillable = ['uuid', 'name', 'slug', 'description', 'top_menu', 'parent_id', 'is_brand', 'thumburl', 'type', 'position', 'system_user_id'];
    }
