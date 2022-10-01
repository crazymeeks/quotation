<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_id',
        'product_uuid',
        'unit_of_measure',
        'company',
        'product_name',
        'manufacturer_part_number',
        'purchase_description',
        'sales_description',
        'price',
        'quantity',
        'percent_discount',
        'final_price',
    ];


    /**
     * Get order of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
