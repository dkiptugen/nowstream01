<?php

namespace App\Models;

use App\Traits\HasUuid;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
        use HasUuid;
        use Sluggable;
        protected $keyType = 'string';
        public $incrementing = false;
        protected $primaryKey='uuid';
        public function sluggable(): array
            {
                return [
                    'slug' => [
                        'source' => 'name'
                    ]
                ];
            }
}
