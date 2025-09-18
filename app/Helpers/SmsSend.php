<?php 

namespace App\Helpers;

use App\Models\SmsMessage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsSend {
    public static function send(string $to, string $message, $smsable = null, string $campaign = null, string $via = 'afro'){
        // Create SMS record first
        $smsMessage = self::createSmsRecord($to, $message, $smsable, $campaign, $via);

        try {
            // Send SMS
            if($via == 'afro'){
                $response = self::sendThroughAfro($to, $message);
            } else {
                $response = self::sendThroughFaris($to, $message);
            }
            
            // Update record based on response
            self::updateSmsRecord($smsMessage, $response);
        } catch (Exception $e) {
            self::updateSmsRecordOnError($smsMessage, $e);
        }

        return $smsMessage;
    }

    /**
     * Create SMS record
     */
    private static function createSmsRecord(string $to, string $message, $smsable = null, string $campaign = null, string $via = 'afro'): SmsMessage
    {
        return SmsMessage::create([
            'smsable_id' => $smsable ? $smsable->id : null,
            'smsable_type' => $smsable ? get_class($smsable) : null,
            'phone_number' => $to,
            'message' => $message,
            'status' => SmsMessage::STATUS_PENDING,
            'provider' => $via === 'afro' ? SmsMessage::PROVIDER_AFRO : SmsMessage::PROVIDER_FARIS,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Update SMS record based on successful response
     */
    private static function updateSmsRecord(SmsMessage $smsMessage, $response): void
    {
        if ($response && (is_object($response) ? $response->successful() : true)) {
            $messageId = is_object($response) ? $response->json('response.message_id') : null;
            $smsMessage->update([
                'status' => SmsMessage::STATUS_PENDING, // Let callback handle final status
                'message_id' => $messageId,
                'response_data' => is_object($response) ? $response->json() : $response,
                'sent_at' => now(),
            ]);
        } else {
            $smsMessage->update([
                'status' => SmsMessage::STATUS_FAILED,
                'error_message' => 'API returned unsuccessful response',
                'response_data' => is_object($response) ? $response->json() : $response,
            ]);
        }
    }

    /**
     * Update SMS record on error
     */
    private static function updateSmsRecordOnError(SmsMessage $smsMessage, \Exception $e): void
    {
        $smsMessage->update([
            'status' => SmsMessage::STATUS_FAILED,
            'error_message' => $e->getMessage(),
        ]);
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

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer '. env('AFRO_KEY'),
            'Content-type'  => 'application/json',
        ])->post('https://api.afromessage.com/api/send', $data);

        // Debug: Log the response to see what we're getting
        Log::info('Afro Single Send Response', [
            'status' => $response->status(),
            'body' => $response->json(),
            'phone' => $to,
            'message_preview' => substr($message, 0, 50) . '...'
        ]);

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

        // Debug: Log the response to see what we're getting
        Log::info('Afro Bulk Send Response', [
            'status' => $response->status(),
            'body' => $response->json(),
            'phone_count' => count($to),
            'message_preview' => substr($message, 0, 50) . '...'
        ]);

        return $response;
    }


}

