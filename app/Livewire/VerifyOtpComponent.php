<?php

namespace App\Livewire;

use App\Helpers\SendVerification;
use Livewire\Component;
use App\Models\VerificationCode as ModelsVerificationCode;
use App\Providers\RouteServiceProvider;

class VerifyOtpComponent extends Component
{
    public $authwith;
    public $phone;
    public $email;
    public $country;

    public $verificationCode;
    public $verificationFor;
    public $options;
    public function mount() {
        $authflowData = session('authflow');
       
        $this->authwith = $authflowData['authwith']; 
        $this->phone    = $authflowData['phone']; 
        $this->email    = $authflowData['email'];
        $this->country  = $authflowData['country']; 

        $this->verificationFor = $authflowData['otpIsFor'];
       

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

        
        if($this->verificationFor === 'must-verify'){
            $this->resend();
        }
       
    }
    public function resend(){
        if($this->authwith == 'phone'){
            $this->resendSMS();
        }
        else if($this->authwith == 'email'){
            $this->resendEmail();
        }
    }
    public function resendSMS(){
        $fullPhone = $this->country->dial_code . $this->phone;
        SendVerification::make()->via('sms')->receiver($fullPhone)->send();
    }
    public function resendEmail(){
        SendVerification::make()->via('mail')->receiver($this->email)->send();
    }
    public function verify() {
        
        $this->validate(['verificationCode' => 'required|numeric|digits:6']);

        $candidate = $this->authwith == 'email'?$this->email: $this->country->dial_code . $this->phone;

        
        $verification = ModelsVerificationCode::where('candidate', $candidate)->latest()->first();


        if($verification){
            if($verification->verification_code == $this->verificationCode){
                $verification->status = 'verified';
                $verification->save();

                if($this->verificationFor == 'must-verify' && auth()->check()){
                    $user = auth()->user();
                    $verifyColumn = "{$this->authwith}_verified_at";
                    $user->{$verifyColumn} = now();
                    $user->save();
                    $verification->delete();
                    session()->forget('authflow');

                    return redirect()->intended(RouteServiceProvider::HOME);
                }
                else if($this->verificationFor == 'reset-password'){
                    return redirect()->route('password.reset');
                }
                return redirect()->route('register');
            }
            session()->flash('authstatus', ['message' => 'Incorrect code!', 'type' => 'error']);
        }
        else{
            session()->flash('authstatus', ['message' => 'Code wasn\'t sent correctly, please try again!', 'type' => 'error']);
        }
   
    }
    public function render()
    {
        return view('livewire.verify-otp-component');
    }
}
