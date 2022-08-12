<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'area',
        'name',
        'image',
        'short_description',
        'description',
        'price',
        'percent_discount',
        'deleted_at',
    ];
}
