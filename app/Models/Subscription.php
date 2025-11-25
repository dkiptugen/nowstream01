<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class Subscription extends Model
		{
			use HasFactory;
			
			protected $fillable = [
				'stream_token',
				'identifier',
				'user_id',
				'type',
				'currency',
				'cost',
				'amount_paid',
				'balance',
				'status',
				'event_id',
				'event_rate_id',
				'channel_id',
				'activated_by',
				'activation_reason'
			];
			public function transactions()
				{
					return $this->hasMany(Transaction::class);
				}
			public function user()
				{
					return $this->belongsTo(User::class);
				}
			public function channel()
				{
					return $this->belongsTo(Channel::class);
				}
			public function event()
				{
					return $this->belongsTo(Event::class);
				}
		}
