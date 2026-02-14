<?php

namespace App\Models;

use App\Casts\JsonCast;
use App\Traits\HasUuid;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
    {
        use HasFactory;
        use Sluggable;
        use SoftDeletes;
        use HasUuid;

        protected $keyType      = 'string';
        public    $incrementing = false;
        protected $primaryKey   = 'uuid';
        protected $casts        = ['genre' => JsonCast::class];

        public function sluggable(): array
            {
                return [
                    'slug' => [
                        'source' => 'title'
                    ]
                ];
            }

        protected $fillable
            = ['old_id',
               'title',
               'thumbnail_url',
               'duration',
               'stream_key',
               'stream_url',
               'stream_video_link',
               'parent_id',
               'content_group',
               'content_path',
               'start_time',
               'end_time',
               'slug',
               'description',
               'thumbnail',
               'channel_id',
               'event_id',
               'source',
               'type',
               'event_id',
               'language_id',
               'language',
               'region_id',
               'country',
               'system_user_id',
               'source',
               'author',
               'category_id',
               'channel_id',
               'status',
               'viewers',
               'views',
               'last_published',
               'is_explicit',
               'created_at',
               'updated_at',
               'deleted_at'];

        public function categories()
            {
                return $this
                    ->belongsToMany(Category::class,
                        'content_category',
                        'content_id',
                        'category_id')
                    ->using(ContentCategory::class)
                    ->withTimestamps();
            }

        public function children()
            {
                return $this->hasMany(Content::class, 'parent_id', 'uuid');
            }

        public function parent()
            {
                return $this->belongsTo(Content::class, 'parent_id', 'uuid');
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

        public function bitrates()
            {
                return $this->hasMany(ContentBitrate::class, 'content_id');
            }


        public function comments()
            {
                return $this->morphMany(Comment::class, 'commentable');
            }


        public function stream()
            {
                return $this->belongsTo(Content::class);
            }

        public function watch()
            {
                return $this->morphMany(WatchHistory::class, 'watchable');
            }

        public function favoritedBy()
            {
                return $this->belongsToMany(User::class, 'favorites', 'content_uuid', 'user_id');
            }


        public function getEventRateAttribute()
            {
                $checkRate = ContentRate::where([['event_id', $this->attributes['uuid']], ['status', true]])->count();
                return $checkRate;
            }

        public function rates()
            {
                return $this->hasMany(ContentRate::class, 'event_id', 'uuid');
            }

        public function event()
            {
                return $this->belongsTo(Event::class, 'event_id');
            }


        public function channel()
            {
                return $this->belongsTo(Channel::class, 'channel_id');
            }


        protected static function booted()
            {
                static::created(function ($content)
                    {
                        if ($content->content_group == 'stream')
                            {
                                $content->bitrates()->create(['resolution' => 'original', 'bitrate' => 0, 'url' => $content->stream_url]);
                                // Add default bitrates for the new stream
                                $defaultBitrates = ['240p' => 300, '360p' => 800, '480p' => 1200, '720p' => 2500, '1080p' => 5000]; // Define your default bitrates

                                foreach ($defaultBitrates as $key => $bitrate)
                                    {
                                        $content->bitrates()->create(['resolution' => $key, 'bitrate' => $bitrate, 'url' => config('custom.STREAM.LIVESTREAM_LINK') . '_transcoded/' . $stream->stream_key . '/' . $stream->stream_key . '_' . $key . '.m3u8']);
                                    }
                            }

                    });
                static::saved(function ($content)
                    {
                        if ($content->content_group == 'stream')
                            {
                                $defaultBitrates = ['240p' => 300, '360p' => 800, '480p' => 1200, '720p' => 2500, '1080p' => 5000]; // Define your default bitrates

                                foreach ($defaultBitrates as $key => $bitrate)
                                    {
                                        $content->bitrates()->create(['resolution' => $key, 'bitrate' => $bitrate, 'url' => config('custom.STREAM.LIVESTREAM_LINK') . '_transcoded/' . $stream->stream_key . '/' . $stream->stream_key . '_' . $key . '.m3u8']);
                                    }
                            }
                    });
            }
    }
