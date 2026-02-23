<?php

    namespace App\Models;

    use App\Casts\JsonCast;
    use App\Traits\HasUuid;
    use Cviebrock\EloquentSluggable\Sluggable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Str;

    class Microsite extends Model
        {
            use Sluggable;
            use SoftDeletes;
            use HasUuid;


            protected $keyType      = 'string';
            public    $incrementing = false;
            protected $primaryKey   = 'uuid';

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
            public function user()
                {
                    return $this->belongsTo(SystemUser::class, 'system_user_id');
                }

            public function users()
                {
                    return $this->belongsToMany(
                        SystemUser::class,
                        'system_user_microsite', // pivot table
                        'microsite_id',          // foreign key on pivot referencing this model
                        'system_user_id'         // foreign key on pivot referencing related model
                    )
                                ->using(SystemUserMicrosite::class)
                                ->withPivot('role_id')
                                ->withTimestamps();
                }
            public function contents()
                {
                    return $this->hasMany(Content::class, 'microsite_id', 'uuid');
                }
        }
