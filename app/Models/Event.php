<?php

namespace App\Models;

use App\Traits\HasUuid;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
	use HasFactory, Sluggable, HasUuid;

	protected $keyType = 'string';
	public $incrementing = false;
	protected $primaryKey = 'uuid';

	protected $casts = [
		'created_at'  => 'datetime',
		'updated_at'  => 'datetime',
		'publish_date' => 'datetime',
		'start_time'  => 'datetime',
		'end_time'    => 'datetime',
	];

	/**
	 * Sluggable configuration
	 */
	public function sluggable(): array
	{
		return [
			'slug' => [
				'source' => 'event_name',
			],
		];
	}

	/**
	 * Event has many streams (Content)
	 */
	public function streams()
	{
		return $this->hasMany(Content::class, 'event_id', 'uuid');
	}

	/**
	 * Event belongs to a channel
	 */
	public function channel()
	{
		return $this->belongsTo(Channel::class);
	}

	/**
	 * Event has many rates
	 */
	public function rates()
	{
		return $this->hasMany(ContentRate::class, 'event_id', 'uuid');
	}

	/**
	 * Optional: Return system user ID automatically
	 */
	public function systemUserId(): Attribute
	{
		return Attribute::make(
			set: fn($value) => Auth::guard('admin')->id()
		);
	}

	/**
	 * Tags attached to the event
	 */
	public function tags()
	{
		return $this->morphToMany(Tag::class, 'taggable');
	}

	/**
	 * Accessor for start_time as Carbon instance
	 */
	public function getStartTimeAttribute($value)
	{
		return Carbon::parse($value);
	}

	/**
	 * Accessor for end_time as Carbon instance
	 */
	public function getEndTimeAttribute($value)
	{
		return Carbon::parse($value);
	}

	/**
	 * Optional: get all attributes as array
	 */
	public function getModelAttributes(): array
	{
		return $this->getAttributes();
	}
	public function eventRates()
	{
		return $this->hasMany(Product::class, 'event_id', 'uuid')
			->where('type', 'ticket')
			->where('is_active', 1);
	}
}
