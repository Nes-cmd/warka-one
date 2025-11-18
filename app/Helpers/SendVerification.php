<?php 

namespace App\Helpers;

use App\Mail\VerificationCode;
use App\Models\VerificationCode as ModelsVerificationCode;
use App\Helpers\SmsSend;
use Exception;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\info;

class SendVerification {

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
       
        $previos = ModelsVerificationCode::where('candidate', $this->receiver)->latest()->first();
        
        if($previos && $previos->expire_at->gte(now())){
            return $previos;
        }

        $verificationCode = rand(100000, 999999);
        $status = false;
       
        try {
        if($this->via == 'mail'){
            $status = Mail::to($this->receiver)->send(new VerificationCode($verificationCode));
        }
        elseif($this->via == 'sms'){
            $status = SmsSend::send($this->receiver, "Your verification code is $verificationCode");
           
           
            }
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('SendVerification error: ' . $e->getMessage());
            return false;
        }

        if ($status !== false) {
        return ModelsVerificationCode::create([
            'code_is_for' => $this->via == 'sms'?'phone':'email',
            'verification_code' => $verificationCode,
            'candidate' => $this->receiver,
            'expire_at' => now()->addMinutes(5),
        ]);
        }
        
        return false;
    }
}