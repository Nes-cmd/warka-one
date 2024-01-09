<?php

namespace App\Livewire;

use App\Helpers\SendVerivication;
use App\Mail\VerificationCode;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class GetCodeComponent extends Component
{
    public $authwith = 'phone';

    public $phone;

    public $email;

    public $selectedCountry;
    public $countries;

    public $otpIsFor;

    public function mount($otpIsFor)
    {
        $this->selectedCountry = Country::first();
        $this->countries = Country::all();
        $this->otpIsFor = $otpIsFor;
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
            $status = SendVerivication::make()->via('sms')->receiver($fullPhone)->send();
        }
        if ($this->authwith == 'email') {
            $this->validate([
                'email' => ['required', 'email', $this->otpIsFor == 'register' ? 'unique:users,email' : 'exists:users,email']
            ]);

            $status = SendVerivication::make()->via('mail')->receiver($this->email)->send();
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
