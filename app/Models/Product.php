<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Storage;

    class Product extends Model
        {
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
                    return $query->where('is_active', 1);
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
        }
