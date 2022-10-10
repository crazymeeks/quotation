<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrderProduct;

class Order extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const DELIVERED = 'delivered';
    const RETURNED = 'returned';
    const PAID = 'paid';

    protected $fillable = [
        'customer_id',
        'user_id',
        'uuid',
        'reference_no',
        'grand_total',
        'percent_discount',
        'status',
    ];


    /**
     * Get orders of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
