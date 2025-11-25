<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SystemUserChannel extends Pivot
    {
        use HasFactory;
        protected $table = 'system_user_channel';
        protected $fillable = ['created_by'];
        public function channel()
            {
                return $this->belongsTo(Channel::class);
            }
        public function system_user()
            {
                return $this->belongsTo(SystemUser::class);
            }
    }
