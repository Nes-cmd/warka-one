<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SmsSend {
    public static function send($to, $message){
        // if()
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer '. env('AFRO_KEY'),
            'Content-type'  => 'application/json',
        ])->post('https://api.afromessage.com/api/send', [
            'from'     =>  env('AFRO_ID'), //
            'sender'   => env('AFRO_SENDER'), // sender short code 
            'to'       => $to, 
            'message'  => $message,
            'callback' => url('/')
        ]);

        return $response->json();
    }
}