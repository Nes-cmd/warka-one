<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\SubCity;
use Livewire\Component;

class AddressComponent extends Component
{
    public $countries = [];
    public $cities = [];
    public $sub_cities = [];

    public $country_id;
    public $city_id;
    public $sub_city_id;
    public $specific_location;

    public $addressId;
    public $updated = false;

    public function mount()
    {
        $this->countries = Country::all();

        $addressId = auth()->user()->userDetail->address_id;
        if($addressId){
            $address = Address::find($addressId);
            $this->country_id = $address->country_id;
            $this->city_id = $address->city_id;
            $this->sub_city_id = $address->sub_city_id;
            $this->specific_location = $address->specific_location;
            $this->addressId = $addressId;

            $this->cities = City::where('country_id', $this->country_id)->get();
            $this->sub_cities = SubCity::where('city_id', $this->city_id)->get();
        }

    }

    public function loadCites()
    {

        $this->cities = City::where('country_id', $this->country_id)->get();
        $this->city_id = null;
        $this->sub_city_id =  null;
    }

    public function loadSubCites()
    {
        $this->sub_cities = SubCity::where('city_id', $this->city_id)->get();
        $this->sub_city_id =  null;
    }

    public function save()
    {
        $this->validate([
            'country_id' => 'required',
            'city_id' => 'required',
            'specific_location' => 'required',
        ]);

        $address = Address::updateOrCreate(
            [
                'id' => $this->addressId
            ],
            [
                'country_id' => $this->country_id,
                'city_id' => $this->city_id,
                'sub_city_id' => $this->sub_city_id,
                'specific_location' => $this->specific_location,
            ]
        );

        $userDetail = auth()->user()->userDetail;
        $userDetail->address_id = $address->id;
        $userDetail->save();

        $this->updated = true;
    }

    public function render()
    {
        return view('livewire.address-component');
    }
}
