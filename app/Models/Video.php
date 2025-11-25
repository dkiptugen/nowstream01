<?php
	
	namespace App\Models;
	
	use Cviebrock\EloquentSluggable\Sluggable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class Video extends Model
		{
			use HasFactory;
			use Sluggable;
			
			protected $fillable
				= [
					'title', 'description', 'slug', 'thumbnail', 'video_path', 'system_user_id', 'channel_id', 'event_id', 'tags',
				];
		
		/**
		 * Return the sluggable configuration array for this model.
		 *
		 * @return array
		 */
			public function sluggable ()
			: array
				{
					return [
						'slug' => [
							'source' => 'title',
						],
					];
				}
		
		/**
		 * Define a one-to-many relationship with WatchHistory model.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
			public function watchHistory ()
				{
					return $this->hasMany (WatchHistory::class);
				}
		
				public function watchHistories()
				{
					return $this->morphMany(WatchHistory::class, 'watchable');
				}
		/**
		 * Define a morphMany relationship with Comment model.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
		 */
			public function comments ()
				{
					return $this->morphMany (Comment::class, 'commentable');
				}
		
		/**
		 * Define a many-to-many relationship with User model (favoritedByUsers).
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
			public function favoritedByUsers ()
				{
					return $this->belongsToMany (User::class, 'favorites')->withTimestamps ();
				}
		
		/**
		 * Define a morphToMany relationship with Tag model.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
		 */
			
			public function tags ()
				{
					return $this->morphToMany (Tag::class, 'taggable');
				}
			public function watch ()
				{
					return $this->morphMany (WatchHistory::class,'watchable');
				}
		/**
		 * Define a belongsTo relationship with Channel model.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
			public function channel ()
				{
					return $this->belongsTo (Channel::class);
				}
		
		/**
		 * Define a belongsTo relationship with Event model.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
			public function event ()
				{
					return $this->belongsTo (Event::class);
				}
		}
