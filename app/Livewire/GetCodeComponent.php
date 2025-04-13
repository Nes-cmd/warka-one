<?php

namespace App\Livewire;

use App\Helpers\SendVerification;
use App\Models\Country;
use Livewire\Component;

class GetCodeComponent extends Component
{
    public $authwith = 'email';

    public $phone;

    public $email;

    public $selectedCountry;
    public $countries;

    public $otpIsFor;
    public $options;

    public function mount($otpIsFor)
    {
        $this->selectedCountry = Country::first();
        $this->countries = Country::all();
        $this->otpIsFor = $otpIsFor;

        $intended = session()->get('url.intended');
        $clientId = null;
        if ($intended && strpos($intended, 'oauth/authorize') !== false) {
            $queryParams = [];
            parse_str(parse_url($intended, PHP_URL_QUERY), $queryParams);
            $clientId = $queryParams['client_id'] ?? null;
        }
        $client = null;
        $options = ['email', 'phone'];
        if($clientId && $client = \App\Models\Passport\Client::find($clientId)){
            $options = $client->use_auth_types;
        }
        $this->options = $options;
    }
    public function changeCountry($id)
    {
        $this->selectedCountry = Country::find($id);
    }


    public function getCode()
    {
       
        if ($this->authwith == 'phone') {
            $this->validate([
                'phone' => ['required', 'min:9', 'max:9', $this->otpIsFor == 'register' ? 'unique:users,phone' : 'exists:users,phone']
            ]);
            $fullPhone = $this->selectedCountry->dial_code . $this->phone;
            $status = SendVerification::make()->via('sms')->receiver($fullPhone)->send();
        }
        if ($this->authwith == 'email') {
            $this->validate([
                'email' => ['required', 'email', $this->otpIsFor == 'register' ? 'unique:users,email' : 'exists:users,email']
            ]);
           
            $status = SendVerification::make()->via('mail')->receiver($this->email)->send();
        }
       
        if ($status) {
           
            session()->put('authflow', [
                'authwith' => $this->authwith,
                'phone' => $this->phone,
                'email' => $this->email,
                'country' => $this->selectedCountry,
                'otpIsFor' => $this->otpIsFor,
            ]);

            return redirect()->route('verify-otp');
        }

    }
    public function render()
    {
        return view('livewire.get-code-component');
    }
}
