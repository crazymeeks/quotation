<?php

namespace App\Models;


use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutItem extends Model
{
    use HasFactory;

    protected $table = 'pull_out_items';

    protected $fillable = [
        'product_id',
        'quantity'
    ];

    /**
     * Get product of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
