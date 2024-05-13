<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $guarded = [];

    public function country() : BelongsTo{
        return $this->belongsTo(Country::class);
    }
    public function subcities() : HasMany {
        return $this->hasMany(SubCity::class);
    }
}
