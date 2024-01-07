<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SmsSend {
    public static function send($to, $message){
        // if()
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJpZGVudGlmaWVyIjoidkV0MjVTVWg2OG9MNWNBdFE1dWxHNlpicHJ3RzMxd1QiLCJleHAiOjE4NjEyMDA0OTEsImlhdCI6MTcwMzM0NzY5MSwianRpIjoiNzI1OWQzNzgtZGYwZS00NzIwLWJiOTctY2YxMzM4Njc3YTAwIn0.w58houRyXLAt8xaO6QbgUwnG3nHdVMTiKUHJQcH9AAk',
            'Content-type'  => 'application/json',
        ])->post('https://api.afromessage.com/api/send', [
            'from'     => 'e80ad9d8-adf3-463f-80f4-7c4b39f7f164', //
            'sender'   => '9786', // sender short code 
            'to'       => $to, 
            'message'  => $message,
            'callback' => 'http://example.com'
        ]);

        return $response->json();
    }
}