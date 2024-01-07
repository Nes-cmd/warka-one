<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expire_at' => 'datetime',
    ];
}
