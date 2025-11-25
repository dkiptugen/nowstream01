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
			
			public function events()
				{
					return $this->morphedByMany(Event::class, 'taggable', 'taggables', 'tag_id');
				}
			
			public function streams()
				{
					return $this->morphedByMany(Stream::class, 'taggable', 'taggables', 'tag_id');
				}
			
			public function videos()
				{
					return $this->morphedByMany(Video::class, 'taggable', 'taggables', 'tag_id');
				}
			
			public function taggable()
				{
					return $this->morphTo();
				}
		}
