<?php

namespace App\Models;

use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'product_uuid',
        'unit_of_measure',
        'company',
        'product_name',
        'manufacturer_part_number',
        'purchase_description',
        'sales_description',
        'price',
        'quantity',
    ];


    /**
     * Get quotation of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
