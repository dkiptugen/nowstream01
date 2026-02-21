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

            protected $fillable = ['name', 'slug', 'colorscheme', 'banner', 'cover', 'favicon', 'logo', 'description', 'image', 'status', 'keywords', 'social_links', 'views', 'followers', 'system_user_id'];
            protected $casts    = ['social_links' => JsonCast::class, 'colorscheme' => JsonCast::class, 'status' => 'bool', 'keywords' => JsonCast::class];

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
                            $baseDomain = config('app.base_domain');
                            if (empty($model->domain))
                                {
                                    $base   = Str::substr(Str::slug(trim($model->name), ''), 0, 8);
                                    $domain = $base;
                                    $count  = 1;
                                    while (self::where('domain', $domain . '.' . $baseDomain)->exists())
                                        {
                                            $domain = $base . $count++;
                                        }
                                    $model->domain = $domain . '.' . $baseDomain;
                                }
                        });
                }

        /**
         * Find microsite by slug
         *
         * @param string $slug
         * @param bool $fail
         * @return self|null
         */
            public static function findBySlug(string $slug, bool $fail = true)
            : ?self
                {
                    if ($fail)
                        {
                            return self::where('slug', $slug)->firstOrFail();
                        }

                    return self::where('slug', $slug)->first();
                }
        }
