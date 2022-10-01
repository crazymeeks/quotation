<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;


    const PENDING = 'pending';
    const DELIVERED = 'delivered';
    const RETURNED = 'returned';
    const CONVERTED = 'converted to order';

    protected $fillable = [
        'customer_id',
        'user_id',
        'uuid',
        'code',
        'percent_discount',
        'status',
    ];
}
