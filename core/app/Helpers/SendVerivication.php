<?php 

namespace App\Helpers;

use App\Mail\VerificationCode;
use App\Models\VerificationCode as ModelsVerificationCode;
use Exception;
use Illuminate\Support\Facades\Mail;

class SendVerivication {

    protected $via;
    protected $receiver;
    public static function make(){
        return new self;
    }

    public function via(string $via) {
        
        if($via == 'sms' || $via == 'mail'){
            $this->via = $via;
            return $this;
        }
        throw new Exception('unknown transmitter '. $via);
    }
    public function receiver(string $to) {
        $this->receiver = $to;
        return $this;
    }
    public function send() {
        $verificationCode = rand(1000, 9999);

        if($this->via == 'mail'){

            $status = Mail::to($this->receiver)->send(new VerificationCode($verificationCode));
        }
        elseif($this->via == 'sms'){
            $status = SmsSend::send($this->receiver, "Your verification code for WARKA is $verificationCode");
        }

        return ModelsVerificationCode::create([
            'code_is_for' => $this->via == 'sms'?'phone':'email',
            'verification_code' => $verificationCode,
            'candidate' => $this->receiver,
            'expire_at' => now()->addMinutes(5),
        ]);
        
    }
}