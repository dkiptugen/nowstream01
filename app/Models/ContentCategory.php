<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ContentCategory extends Pivot
    {
        protected $table = 'content_category';

        protected $fillable
            = [
                'content_id',
                'category_id',
            ];
    }
