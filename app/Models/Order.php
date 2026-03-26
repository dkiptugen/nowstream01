<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeForPaidEvent(Builder $query, int $userId, string $eventId): Builder
    {
        return $this->scopeForPaidEventProductType($query, $userId, $eventId, 'ticket');
    }

    public function scopeForPaidEventProductType(Builder $query, int $userId, string $eventId, string|array $productTypes): Builder
    {
        return $query
            ->select('orders.*')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.user_id', $userId)
            ->where('orders.payment_status', 'paid')
            ->where('products.payable_id', $eventId)
            ->where('products.payable_type', Event::class)
            ->whereIn('products.type', (array) $productTypes)
            ->distinct();
    }

    public function scopeForPendingEventRate(Builder $query, int $userId, int $rateId): Builder
    {
        return $query
            ->select('orders.*')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', $userId)
            ->where('orders.payment_status', 'pending')
            ->where('order_items.product_id', $rateId)
            ->distinct();
    }
}
