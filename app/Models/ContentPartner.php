<?php

namespace App\Models;

use App\Casts\JsonCast;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPartner extends Model
{
    use HasFactory;
    protected $casts =['legal_documents'=>JsonCast::class];
}
