<?php

namespace App\Models;

use App\Casts\JsonCast;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
    {
        use HasFactory;
        use HasUuid;

        protected $keyType      = 'string';
        public    $incrementing = false;
        protected $primaryKey   = 'uuid';
        protected $casts        = ['response' => JsonCast::class];

        protected $fillable
            = [
                'user_id',
                'name',
                'event_id',
                'channel_id',
                'currency',
                'payment_method',
                'subscription_id',
                'receipt',
                'cost',
                'amount_paid',
                'date_paid',
                'response',
                'transaction_token'
            ];

        public function subscription()
            {
                return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
            }

        public function order()
            {
                return $this->belongsTo(Order::class, 'order_id', 'subscription_token');
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
