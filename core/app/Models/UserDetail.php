<?php

namespace App\Models;

use App\Enum\GenderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gender' => GenderEnum::class,
        'birth_date' => 'datetime'
    ];

    public function address() : HasOne {
        return $this->hasOne(Address::class);
    }
}
