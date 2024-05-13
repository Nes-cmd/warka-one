<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserDetail extends Model
{
    protected $guarded = [];

    public function address() : HasOne {
        return $this->hasOne(Address::class);
    }
}
