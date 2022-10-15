<?php

namespace App\Models;

use App\Models\PullOutRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutRequestProduct extends Model
{
    use HasFactory;

    protected $table = 'pull_out_request_products';

    protected $fillable = [
        'pull_out_request_id',
        'quantity',
        'unit',
        'product_uuid',
        'product_name',
        'code',
        'purchase_description',
        'size',
        'color',
        'remarks',
    ];


    /**
     * Get pull out request of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pull_out_request()
    {
        return $this->belongsTo(PullOutRequest::class);
    }
}
