<?php

namespace App\Models;


use App\Models\QuotationHistoryProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationHistory extends Model
{
    use HasFactory;

    protected $table = 'quotation_histories';
    
    protected $fillable = [
        'code'
    ];

    /**
     * Get quotation history products of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotation_history_products()
    {
        return $this->hasMany(QuotationHistoryProduct::class, 'quotation_history_id');
    }
}
