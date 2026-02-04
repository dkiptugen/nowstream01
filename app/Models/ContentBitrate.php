<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class ContentBitrate extends Model
        {
            use HasFactory;

            protected $fillable
                = [
                    'content_id',
                    'resolution',
                    'bitrate',
                    'url',
                ];

            public function content()
                {
                    return $this->belongsTo( Content::class, 'content_id','uuid' );
                }
        }
