<?php

    namespace App\Models;

    use App\Casts\JsonCast;
    use Cviebrock\EloquentSluggable\Sluggable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Str;

    class Microsite extends Model
        {
            use Sluggable;
            use SoftDeletes;

            protected $fillable = ['name', 'slug', 'colorscheme', 'banner', 'cover', 'favicon', 'description', 'image', 'status', 'keywords', 'social_links', 'views', 'followers', 'system_user_id'];
            protected $casts    = ['social_links' => JsonCast::class, 'colorscheme' => JsonCast::class];

            public function sluggable()
            : array
                {
                    return [
                        'slug' => [
                            'source' => 'name'
                        ]
                    ];
                }

            protected static function booted()
                {
                    static::creating(function ($model)
                        {

                            if (empty($model->domain))
                                {
                                    $base   = Str::slug($model->name, '');
                                    $domain = $base;
                                    $count  = 1;
                                    while (self::where('domain', $domain . '.streamer.co.ke')->exists())
                                        {
                                            $domain = $base . $count++;
                                        }
                                    $model->domain = $domain . '.streamer.co.ke';
                                }
                        });
                }
        }
