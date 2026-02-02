<?php

    namespace App\Models;

    use Cviebrock\EloquentSluggable\Sluggable;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    class Stream extends Model
        {
            use HasFactory;
			use Sluggable;
			use SoftDeletes;
		    public function sluggable(): array
			    {
				    return [
					    'slug' => [
						    'source' => 'title'
					    ]
				    ];
			    }
            public function bitrates(  )
                {
                    return $this->hasMany(StreamBitrate::class, 'stream_id');
            }
            public function comments()
            {
                return $this->morphMany(Comment::class, 'commentable');
            }
		    public function stream()
			    {
					return $this->belongsTo (Stream::class);
			    }
		    
                public function watch ()
			    {
				    return $this->morphMany (WatchHistory::class,'watchable');
				}
                public function getEventRateAttribute()
			    {
                    $checkRate = EventRate::where([['event_id', $this->attributes['id']], ['status', true ]])->count();
				    return $checkRate;
				}
                public function eventRates()
                {
                    return $this->hasMany(EventRate::class, 'event_id', 'event_id');
                }
                public function event()
                {
                    return $this->belongsTo(Event::class, 'event_id');
                }
            
                public function channel()
                {
                    return $this->belongsTo(Channel::class, 'channel_id');
                }
		    public function tags ()
			    {
				    return $this->morphToMany (Tag::class, 'taggable');
			    }
            protected static function booted()
                {
                   /* static::created(function ($stream) {
                        // Add default bitrates for the new stream
                        $defaultBitrates = ['240p'=>300,'360p'=>800,'480p'=> 1200,'720p'=>2500, '1080p'=>5000]; // Define your default bitrates

                        foreach ($defaultBitrates as $key => $bitrate)
                            {
                                $stream->bitrates()->create(['resolution'=>$key,'bitrate' => $bitrate,'url'=>config('custom.STREAM.LIVESTREAM_LINK').'_transcoded/'.$stream->stream_key.'/'.$stream->stream_key.'_'.$key.'.m3u8']);
                            }
                    });*/
                   /* static::saved(function ($stream) {
                        $defaultBitrates = ['240p'=>300,'360p'=>800,'480p'=> 1200,'720p'=>2500, '1080p'=>5000]; // Define your default bitrates

                        foreach ($defaultBitrates as $key => $bitrate)
                            {
                                $stream->bitrates()->create(['resolution'=>$key,'bitrate' => $bitrate,'url'=>config('custom.STREAM.LIVESTREAM_LINK').'_transcoded/'.$stream->stream_key.'/'.$stream->stream_key.'_'.$key.'.m3u8']);
                            }
                    });*/
                }
        }
