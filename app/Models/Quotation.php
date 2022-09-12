<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;


    const PENDING = '0';
    const DELIVERED = '1';
    const RETURNED = '3';

    protected $fillable = [
        'customer_id',
        'user_id',
        'uuid',
        'code',
        'percent_discount',
        'status',
    ];
}
