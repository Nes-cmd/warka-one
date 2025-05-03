<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PhoneVerification extends Component
{
    public $phone;
    public $country_id;
    public $verificationCode = '';
    public $isVerifying = false;
    public $verificationSent = false;
    public $verificationMessage = '';
    public $user;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->user = auth()->user();
        $this->phone = $this->user->phone;
        $this->country_id = $this->user->country_id;
    }


    public function initiateVerification()
    {
        if (!$this->user->phone || $this->user->phone_verified_at) {
            $this->verificationMessage = 'Your phone is already verified or not set.';
            return;
        }

        $this->isVerifying = true;
        $this->verificationMessage = '';

        // Generate and send verification code
        try {
            // Generate a random verification code
            $verificationCode = rand(100000, 999999);

            // Delete any existing verification codes
            VerificationCode::where('user_id', $this->user->id)
                ->where('for', 'phone-verify')
                ->delete();

            // Create a new verification code record
            VerificationCode::create([
                'user_id' => $this->user->id,
                'code' => $verificationCode,
                'for' => 'phone-verify',
                'expire_at' => now()->addMinutes(10),
            ]);

            // Send the verification code via SMS
            \App\Helpers\SmsSend::send($this->user->phone, "Your verification code is: $verificationCode");

            $this->verificationSent = true;
            $this->verificationMessage = 'We sent a verification code to your phone.';
        } catch (\Exception $e) {
            Log::error("Failed to send verification SMS: " . $e->getMessage());
            $this->verificationMessage = 'Unable to send verification code. Please try again.';
        }
    }

    public function verifyCode()
    {
        $this->validate([
            'verificationCode' => 'required|numeric|digits:6',
        ]);

        // Find valid verification code
        $verificationCode = VerificationCode::where('user_id', $this->user->id)
            ->where('code', $this->verificationCode)
            ->where('for', 'phone-verify')
            ->where('expire_at', '>', now())
            ->first();

        if (!$verificationCode) {
            $this->addError('verificationCode', 'Invalid or expired verification code.');
            return;
        }

        // Mark phone as verified
        $this->user->phone_verified_at = now();
        $this->user->save();

        // Delete used verification codes
        VerificationCode::where('user_id', $this->user->id)
            ->where('for', 'phone-verify')
            ->delete();

        $this->isVerifying = false;
        $this->verificationSent = false;
        $this->verificationCode = '';
        $this->verificationMessage = 'Your phone number has been verified successfully.';

        $this->emit('refreshComponent');
    }

    public function resendCode()
    {
        $this->initiateVerification();
    }

    public function cancelVerification()
    {
        $this->isVerifying = false;
        $this->verificationSent = false;
        $this->verificationCode = '';
    }

    public function render()
    {
        $countries = Country::all();
        
        $isVerifying = $this->isVerifying;
        return view('livewire.phone-verification')->with(['countries' => $countries]);
    }
}
