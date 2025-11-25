<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Taggable extends Pivot
    {
        use HasFactory;

        /*public function type(): Attribute
            {
                return Attribute::make(

                    set: fn ($value) => strtolower(string: str_replace('App\\Models\\','',$this->taggable_type)),

                );
            }*/
    }
