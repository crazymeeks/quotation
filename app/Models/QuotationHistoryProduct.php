<?php

namespace App\Models;

use App\Models\QuotationHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationHistoryProduct extends Model
{
    use HasFactory;

    protected $table = 'quotation_history_products';

    protected $fillable = [
        'quotation_history_id',
        'uuid',
        'version',
        'product_uuid',
        'company',
        'product_name',
        'unit_of_measure',
        'manufacturer_part_number',
        'purchase_description',
        'sales_description',
        'price',
        'quantity',
    ];

    /**
     * Get history of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quotation_history()
    {
        return $this->belongsTo(QuotationHistory::class, 'quotation_history_id');
    }
}
