<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class StreamBitrate extends Model
        {
            use HasFactory;

            protected $fillable
                = [
                    'stream_id',
                    'resolution',
                    'bitrate',
                    'url',
                ];

            public function stream()
                {
                    return $this->belongsTo( Stream::class );
                }
        }
