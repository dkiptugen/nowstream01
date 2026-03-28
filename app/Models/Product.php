<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected static array $activeColumnCache = [];

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
        $model = $query->getModel();
        $column = $this->resolveActiveColumn($model);

        if ($column === null) {
            return $query;
        }

        return $query->where($column, 1);
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

    protected function resolveActiveColumn(Model $model): ?string
    {
        $connectionName = $model->getConnectionName() ?: config('database.default');
        $cacheKey = $connectionName . ':' . $model->getTable();

        if (array_key_exists($cacheKey, static::$activeColumnCache)) {
            return static::$activeColumnCache[$cacheKey];
        }

        $schema = $model->getConnection()->getSchemaBuilder();

        if ($schema->hasColumn($model->getTable(), 'is_active')) {
            return static::$activeColumnCache[$cacheKey] = 'is_active';
        }

        if ($schema->hasColumn($model->getTable(), 'status')) {
            return static::$activeColumnCache[$cacheKey] = 'status';
        }

        return static::$activeColumnCache[$cacheKey] = null;
    }
}
