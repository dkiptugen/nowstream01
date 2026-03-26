<?php

	namespace App\Models;

	use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Subscription extends Model
		{
			use HasFactory;
            use HasUuid;
            protected $keyType = 'string';
            public $incrementing = false;
            protected $primaryKey='uuid';
			protected $fillable = [
				'identifier',
				'stream_token',
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
				'latest_transaction_id',
				'activated_by',
				'activation_reason'
			];
			public function transactions()
				{
					return $this->hasMany(Transaction::class, 'subscription_id', 'id');
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
					return $this->belongsTo(Event::class, 'event_id', 'uuid');
				}
		}
