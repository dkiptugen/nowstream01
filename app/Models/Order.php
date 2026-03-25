<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'order_number',
        'subtotal',
        'tax',
        'fees',
        'total_amount',
        'currency',
        'is_subscribable',
        'is_recurrent',
        'next_payment',
        'subscription_start_at',
        'subscription_end_at',
        'payment_status',
        'latest_transaction_id',
        'subscription_token',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'next_payment' => 'datetime',
        'subscription_start_at' => 'datetime',
        'subscription_end_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id', 'subscription_token');
    }
}
