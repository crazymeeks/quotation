<?php

namespace App\Models;

use App\Models\PullOutRequestProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutRequest extends Model
{
    use HasFactory;

    const DEMO_ITEMS = 'Demo Items';
    const REPLACED_UNITS = 'Replaced Units';
    const BACKUP_UNITS = 'Backup Units';
    const OTHERS = 'Others';

    protected $table = 'pull_out_requests';

    protected $fillable = [
        'type',
        'por_no',
        'business_name',
        'address',
        'contact_person',
        'phone',
        'salesman',
        'requested_by',
        'approved_by',
        'returned_by',
        'counter_checked_by',
    ];

    /**
     * Get pull out requests of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pull_out_request_products()
    {
        return $this->hasMany(PullOutRequestProduct::class);
    }
}
