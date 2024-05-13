<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCity extends Model
{
    protected $guarded = [];

    public function city(){
        return $this->belongsTo(City::class);
    }
    public function getLocationAttribute(): array
    {
        return [
            "lat" => (float)$this->lat,
            "lng" => (float)$this->lng,
        ];
    }
    public function setLocationAttribute(?array $location): void
    {
        if (is_array($location))
        {
            $this->attributes['lat'] = $location['lat'];
            $this->attributes['lng'] = $location['lng'];
            unset($this->attributes['location']);
        }
    }
    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'lat',
            'lng' => 'lng',
        ];
    }
    public static function getComputedLocation(): string
    {
        return 'location';
    }
}
