<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrderProduct;

class Order extends Model
{
    use HasFactory;

    const TYPE_ORDER = 'order';
    const TYPE_QUOTATION = 'quotation';

    const PENDING = '0';
    const DELIVERED = '1';
    const RETURNED = '3';

    protected $fillable = [
        'customer_id',
        'user_id',
        'uuid',
        'reference_no',
        'grand_total',
        'percent_discount',
        'type',
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
