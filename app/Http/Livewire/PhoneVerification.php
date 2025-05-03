<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\User;
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
    public $messageType = 'error';
    
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    public function mount()
    {
        $user = auth()->user();
        $this->phone = $user->phone;
        $this->country_id = $user->country_id;
    }
    
    public function render()
    {
        $user = auth()->user();
        $countries = Country::all();
        return view('livewire.phone-verification', [
            'countries' => $countries,
            'user' => $user
        ]);
    }
    
    public function updatedPhone()
    {
        // Clear any verification state when phone is updated
        $this->isVerifying = false;
        $this->verificationSent = false;
        $this->verificationCode = '';
        $this->verificationMessage = '';
    }
    
    public function savePhone()
    {
        $this->validate([
            'phone' => 'required|string|max:15',
            'country_id' => 'required|exists:countries,id'
        ]);
        
        $user = auth()->user();
        $phoneChanged = $user->phone != $this->phone;
        
        if ($phoneChanged) {
            $user->phone = $this->phone;
            $user->country_id = $this->country_id;
            $user->phone_verified_at = null;
            $user->save();
            
            $this->verificationMessage = 'Phone number updated. Please verify your new number.';
            $this->messageType = 'success';
            $this->emit('phoneUpdated');
        }
    }
    
    public function initiateVerification()
    {
        $user = auth()->user();
        
        if (!$user->phone || $user->phone_verified_at) {
            $this->verificationMessage = 'Your phone is already verified or not set.';
            $this->messageType = 'error';
            return;
        }
        
        $this->isVerifying = true;
        $this->verificationMessage = '';
        
        // Generate and send verification code
        try {
            // Generate a random verification code
            $verificationCode = rand(100000, 999999);
            
            // Delete any existing verification codes
            VerificationCode::where('user_id', $user->id)
                ->where('for', 'phone-verify')
                ->delete();
            
            // Create a new verification code record
            VerificationCode::create([
                'user_id' => $user->id,
                'code' => $verificationCode,
                'for' => 'phone-verify',
                'expire_at' => now()->addMinutes(10),
            ]);
            
            // Send the verification code via SMS
            \App\Helpers\SmsSend::send($user->phone, "Your verification code is: $verificationCode");
            
            $this->verificationSent = true;
            $this->verificationMessage = 'We sent a verification code to your phone.';
            $this->messageType = 'success';
            
        } catch (\Exception $e) {
            Log::error("Failed to send verification SMS: " . $e->getMessage());
            $this->verificationMessage = 'Unable to send verification code. Please try again.';
            $this->messageType = 'error';
        }
    }
    
    public function verifyCode()
    {
        $this->validate([
            'verificationCode' => 'required|numeric|digits:6',
        ]);
        
        $user = auth()->user();
        
        // Find valid verification code
        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('code', $this->verificationCode)
            ->where('for', 'phone-verify')
            ->where('expire_at', '>', now())
            ->first();
        
        if (!$verificationCode) {
            $this->addError('verificationCode', 'Invalid or expired verification code.');
            return;
        }
        
        // Mark phone as verified
        $user->phone_verified_at = now();
        $user->save();
        
        // Delete used verification codes
        VerificationCode::where('user_id', $user->id)
            ->where('for', 'phone-verify')
            ->delete();
        
        $this->isVerifying = false;
        $this->verificationSent = false;
        $this->verificationCode = '';
        $this->verificationMessage = 'Your phone number has been verified successfully!';
        $this->messageType = 'success';
        
        $this->emit('phoneVerified');
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
} 