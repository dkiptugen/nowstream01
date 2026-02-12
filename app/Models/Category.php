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
        public function contents()
            {
                return $this->belongsToMany(Content::class,
                                            'content_category',
                                            'category_id',
                                            'content_id')
                            ->using(ContentCategory::class)
                            ->withTimestamps();
            }
        public function tags()
            {
                return $this->morphToMany(
                    Tag::class,
                    'taggable',
                    'taggables',
                    'taggable_id',
                    'tag_id'
                )->withTimestamps();
            }
    }
