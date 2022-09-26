<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    use HasFactory;

    protected $table = 'quote_products';

    protected $fillable = [
        'product_id',
        'quantity',
    ];
}
