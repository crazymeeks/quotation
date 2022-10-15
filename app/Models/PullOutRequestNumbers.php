<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutRequestNumbers extends Model
{
    use HasFactory;

    protected $table = 'pull_out_request_numbers';

    protected $fillable = [
        'num',
    ];
}
