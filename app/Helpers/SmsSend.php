<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SmsSend {
    public static function send(string $to, string $message, string $via = 'afro'){
        if(env('SMS_VIA') == 'afro'){
            return self::sendThroughAfro($to, $message);
        }
        
        return self::sendThroughFaris($to, $message);
    }

    public static function sendThroughFaris(string $toPhone, string $message){
        if($toPhone[0] == '+') $toPhone = substr($toPhone, 1);
        $postdata =  json_encode([
            "accessKey" => env('SMS_ACCESS'),
            "secretKey" => env('SMS_SECRET'),
            "from" => "Ker Labs",
            "to" => $toPhone,
            "message" => $message,
            "callbackUrl" =>"https://kertech.co"
         ]);

        $opts = array('http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            // 'header'  => 'Content-Type: application/x-www-form-urlencoded', if want to send request like form
            'content' => $postdata
        ]);

        $context = stream_context_create($opts);

        $result = file_get_contents('http://api.kmicloud.com/sms/send/v1/notify', false, $context);

        return json_decode($result);
    }

    public static function  sendThroughAfro(string $to, string $message, string $callback = null){
        
        $data = [
            'from'     => env('AFRO_ID'), //
            'sender'   => env('AFRO_SENDER'), // sender short code 
            'to'       => $to, 
            'message'  => $message,
            'callback' => $callback ?? route('sms.callback')
        ];

        info('Afro Bulk Send Data', $data);
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer '. env('AFRO_KEY'),
            'Content-type'  => 'application/json',
        ])->post('https://api.afromessage.com/api/send', $data);

        return $response;
    }

    public static function sendBulkAfro(array $to, string $message, string $campaign = null, string $createCallback = null, string $statusCallback = null){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. env('AFRO_KEY'),
            'Content-type'  => 'application/json',
        ])->post('https://api.afromessage.com/api/bulk_send', [
            'from'            => env('AFRO_ID'),
            'sender'          => env('AFRO_SENDER'),
            'to'              => $to, // Array of phone numbers
            'message'         => $message,
            'campaign'        => $campaign,
            'createCallback'  => $createCallback ?? route('sms.callback'),
            'statusCallback'  => $statusCallback ?? route('sms.callback')
        ]);

        info('Afro Bulk Send Response', $response->json());

        return $response;
    }

}

