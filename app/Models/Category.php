<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
        use HasUuid;
        protected $keyType = 'string';
        public $incrementing = false;
        protected $primaryKey='uuid';
}
