<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected static ?string $activeColumn = null;

    protected $fillable = [
        'microsite_id',
        'type',
        'free_pass',
        'name',
        'description',
        'image_path',
        'price',
        'currency',
        'stock_total',
        'stock_sold',
        'sales_start_at',
        'sales_end_at',
        'is_active',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeTickets($query)
    {
        return $query->where('type', 'ticket');
    }

    public function scopeActive($query)
    {
        return $query->where($this->resolveActiveColumn(), 1);
    }

    public function scopeMerch($query)
    {
        return $query->where('type', 'merch');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return Storage::disk(config('filesystems.default'))->url($this->image_path);
    }

    protected function resolveActiveColumn(): string
    {
        if (static::$activeColumn !== null) {
            return static::$activeColumn;
        }

        static::$activeColumn = Schema::hasColumn($this->getTable(), 'is_active') ? 'is_active' : 'status';

        return static::$activeColumn;
    }
}
