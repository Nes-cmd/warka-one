<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];

    /**
     * Shape this country the way every Inertia page expects it. Flags are served from
     * flagcdn.com keyed by ISO 3166-1 alpha-2 country_code — there's no full local flag
     * set, and a CDN means new countries never need an asset added to get a working flag.
     */
    public function toFrontendArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dial_code' => $this->dial_code,
            'country_code' => $this->country_code,
            'phone_length' => $this->phone_length,
            'flag_url' => $this->country_code
                ? 'https://flagcdn.com/' . strtolower($this->country_code) . '.svg'
                : asset('flags/et.svg'),
        ];
    }
}