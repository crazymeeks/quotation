<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    use HasFactory;

    const ADMIN_ROLE = 'Admin';
    const USERIN_ROLE = 'UserIn';
    const USEROUT_ROLE = 'UserOut';

    protected $fillable = [
        // 'permission_id',
        'uuid',
        'title',
    ];

    /**
     * Get users of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
