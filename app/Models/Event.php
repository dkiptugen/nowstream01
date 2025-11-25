<?php
	
	namespace App\Models;
	
	use Carbon\Carbon;
	use Cviebrock\EloquentSluggable\Sluggable;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Log;
	
	class Event extends Model
		{
			use HasFactory, Sluggable;
			
			protected $casts
				= [
					'created_at' => 'datetime', 'updated_at' => 'datetime', 'publish_date' => 'datetime'
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
							'source' => 'event_name'
						]
					];
				}
		
		/**
		 * Get the streams for the event.
		 */
			public function streamx ()
				{
					return $this->hasMany (Stream::class);
				}
		
		/**
		 * Get the channel that hosts the event.
		 */
			public function channel ()
				{
					return $this->belongsTo (Channel::class);
				}
			
			public function rates ()
				{
					return $this->hasMany (EventRate::class);
				}
		
		/**
		 * Get the videos for the event's streams.
		 */
			public function videos ()
			{
				return $this->hasMany (Video::class);
			} 
			
			public function system_user_id ()
			: Attribute
				{
					return Attribute::make (set: fn(string $value) => Auth::guard ('admin')->user ()->id);
				}
			
			public function tags ()
				{
					return $this->morphToMany (Tag::class, 'taggable');
				}
			public function getModelAttributes()
				{
					$attributes = $this->getAttributes(); // Using getAttributes() method
					return $attributes;
				}
			public function streams() :Attribute
				{
					$stream = Stream::whereEventId($this->attributes['id'])->first();
					return Attribute::make (get: fn($value) => $stream);
					
				}
		 
			public function getStartTimeAttribute($value)
				{
					return Carbon::parse($value);
				}
			
			public function getEndTimeAttribute($value)
				{
					return Carbon::parse($value);
				}
			
		}
