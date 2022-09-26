<?php

namespace App\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    protected $fillable = [
        'unit_of_measure_id',
        'company_id',
        'uuid',
        'name',
        'manufacturer_part_number',
        'purchase_description',
        'sales_description',
        'cost',
        'inventory',
        'percent_discount',
        'status',
        'deleted_at',
    ];

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }

    /**
     * Get company of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
