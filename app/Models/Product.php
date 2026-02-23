<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Product extends Model
        {
            public function payable()
                {
                    return $this->morphTo();
                }

            public function scopeTickets($query)
                {
                    return $query->where('type', 'ticket');
                }

            public function scopeActive($query)
                {
                    return $query->where('is_active', 1);
                }
        }
