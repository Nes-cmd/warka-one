<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = [];

    protected $appends = ['effective_address'];

    public function getEffectiveAddressAttribute(){
        $subcity = $this->subcity?$this->subcity->name:'';
        $city = $this->city?$this->city->name:'';
        $location = ucfirst($this->specific_location);

        $effective = "$subcity $city, {$this->country->name}";
        $effective = $location? "$location in {$effective}":$effective;

        return $effective;
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }
    public function subcity(){
        return $this->belongsTo(SubCity::class, 'sub_city_id');
    }
}
