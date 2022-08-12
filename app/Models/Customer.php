<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'customer_name',
        'contact_no',
        'address',
    ];


    /**
     * Get orders of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
