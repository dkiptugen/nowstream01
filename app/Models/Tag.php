<?php
	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Cviebrock\EloquentSluggable\Sluggable;

	class Tag extends Model
		{
			protected $table = 'tags';
			public $timestamps = false;
			use HasFactory, Sluggable;

			protected $fillable = ["name"];

			public function sluggable(): array
				{
					return [
						'slug' => [
							'source' => 'name'
						]
					];
				}


            public function contents()
                {
                    return $this->morphedByMany(Content::class, 'taggable');
                }

            public function categories()
                {
                    return $this->morphedByMany(Category::class, 'taggable');
                }
		}
