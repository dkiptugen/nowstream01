<?php

    namespace App\Models;

    use App\Casts\JsonCast;
    use App\Traits\HasUuid;
    use Cviebrock\EloquentSluggable\Sluggable;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Laravel\Scout\Searchable;

    class Content extends Model
        {
            use HasFactory;
            use Sluggable;
            use SoftDeletes;
            use HasUuid;
            use Searchable;

            protected $keyType      = 'string';
            public    $incrementing = false;
            protected $primaryKey   = 'uuid';
            protected $casts        = ['genre' => JsonCast::class];

            public function sluggable()
            : array
                {
                    return [
                        'slug' => [
                            'source' => 'title'
                        ]
                    ];
                }

            protected $fillable
                = [
                    'old_id',
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
                    'deleted_at'
                ];

            public function categories()
                {
                    return $this
                        ->belongsToMany(
                            Category::class,
                            'content_category',
                            'content_id',
                            'category_id'
                        )
                        ->using(ContentCategory::class)
                        ->withTimestamps();
                }
            public function shouldBeSearchable()
                {
                    return $this->status == 1 && is_null($this->deleted_at);
                }
            public function toSearchableArray()
            : array
                {


                    return [
                        'title'             => $this->title,
                        'thumbnail'         => $this->thumbnail_url,
                        'duration'          => $this->duration,
                        'stream_key'        => $this->stream_key,
                        'stream_url'        => $this->stream_url,
                        'stream_video_link' => $this->stream_video_link,
                        'content_group'     => $this->content_group,
                        'content_path'      => $this->content_path,
                        'start_time'        => $this->start_time,
                        'end_time'          => $this->end_time,
                        'slug'              => $this->slug,
                        'description'       => $this->description,
                        'channel'           => optional($this->channel)->name,
                        'event'             => optional($this->event)->title,
                        'category'          => optional($this->category)->name,
                        'source'            => $this->source,
                        'type'              => $this->type,
                        'explicit'          => (bool)$this->is_explicit,
                        'language'          => $this->language,
                        'country'           => $this->country,
                        'publishdate'       => $this->publishdate,
                    ];
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


            public function watch()
                {
                    return $this->morphMany(WatchHistory::class, 'watchable');
                }

            public function favoritedBy()
                {
                    return $this->belongsToMany(User::class, 'favorites', 'content_uuid', 'user_id');
                }

            public function rates()
                {
                    return $this->hasMany(ContentRate::class, 'content_id');
                }

            public function isFree()
                {
                    return $this->rates->isEmpty();
                }


            public function event()
                {
                    return $this->belongsTo(Event::class, 'event_id', 'uuid');
                }


            public function channel()
                {
                    return $this->belongsTo(Channel::class, 'channel_id');
                }


            protected static function booted()
                {

                    static::created(function ($content)
                        {

                            if ($content->content_group !== 'stream')
                                {
                                    return;
                                }

                            // Prevent duplicate generation
                            if ($content->bitrates()->exists())
                                {
                                    return;
                                }

                            // Original stream
                            $content->bitrates()->create([
                                                             'resolution' => 'original',
                                                             'bitrate'    => 0,
                                                             'url'        => $content->stream_url
                                                         ]);

                            $defaultBitrates = [
                                '240p'  => 300,
                                '360p'  => 800,
                                '480p'  => 1200,
                                '720p'  => 2500,
                                '1080p' => 5000
                            ];

                            foreach ($defaultBitrates as $resolution => $bitrate)
                                {

                                    $url = config('custom.STREAM.LIVESTREAM_LINK')
                                           . '_transcoded/'
                                           . $content->stream_key
                                           . '/'
                                           . $content->stream_key
                                           . "_{$resolution}.m3u8";

                                    $content->bitrates()->create([
                                                                     'resolution' => $resolution,
                                                                     'bitrate'    => $bitrate,
                                                                     'url'        => $url
                                                                 ]);
                                }
                        });

                }
        }
