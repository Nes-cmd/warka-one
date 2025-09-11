<?php 

namespace App\Helpers;

use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    /**
     * Send SMS and create record (for immediate execution)
     */
    public static function sendAndCreateRecord(string $to, string $message, $smsable, string $campaign = null): ?SmsMessage
    {
        try {
            $response = self::sendThroughAfro($to, $message);
            
            if ($response->successful()) {
                $messageId = $response->json('response.message_id') ?? null;
                
                // Create SMS record AFTER successful send
                $smsMessage = SmsMessage::create([
                    'smsable_id' => $smsable->id,
                    'smsable_type' => get_class($smsable),
                    'phone_number' => $to,
                    'message' => $message,
                    'status' => SmsMessage::STATUS_PENDING, // Let callback handle status updates
                    'provider' => SmsMessage::PROVIDER_AFRO,
                    'campaign' => $campaign,
                    'message_id' => $messageId,
                    'response_data' => $response->json(),
                    'sent_at' => now(),
                ]);
                
                Log::info('SMS sent and record created', [
                    'sms_message_id' => $smsMessage->id,
                    'phone_number' => $to,
                    'provider_message_id' => $messageId,
                    'smsable_type' => get_class($smsable),
                    'smsable_id' => $smsable->id
                ]);
                
                return $smsMessage;
            } else {
                // Create failed SMS record
                $smsMessage = SmsMessage::create([
                    'smsable_id' => $smsable->id,
                    'smsable_type' => get_class($smsable),
                    'phone_number' => $to,
                    'message' => $message,
                    'status' => SmsMessage::STATUS_FAILED,
                    'provider' => SmsMessage::PROVIDER_AFRO,
                    'campaign' => $campaign,
                    'error_message' => 'API returned unsuccessful response',
                    'response_data' => $response->json(),
                ]);
                
                Log::warning('SMS failed and record created', [
                    'sms_message_id' => $smsMessage->id,
                    'phone_number' => $to,
                    'status' => $response->status(),
                    'smsable_type' => get_class($smsable),
                    'smsable_id' => $smsable->id
                ]);
                
                return $smsMessage;
            }
        } catch (\Exception $e) {
            // Create failed SMS record
            $smsMessage = SmsMessage::create([
                'smsable_id' => $smsable->id,
                'smsable_type' => get_class($smsable),
                'phone_number' => $to,
                'message' => $message,
                'status' => SmsMessage::STATUS_FAILED,
                'provider' => SmsMessage::PROVIDER_AFRO,
                'campaign' => $campaign,
                'error_message' => $e->getMessage(),
            ]);
            
            Log::error('SMS exception and record created', [
                'sms_message_id' => $smsMessage->id,
                'phone_number' => $to,
                'error' => $e->getMessage(),
                'smsable_type' => get_class($smsable),
                'smsable_id' => $smsable->id
            ]);
            
            return $smsMessage;
        }
    }

    /**
     * Send bulk SMS and create records (for immediate execution)
     */
    public static function sendBulkAndCreateRecords(array $recipients, string $message, string $campaign = null): array
    {
        $phoneNumbers = [];
        $smsMessages = [];
        
        // Extract phone numbers
        foreach ($recipients as $recipient) {
            if ($recipient->phone) {
                $phoneNumbers[] = $recipient->phone;
            }
        }
        
        if (empty($phoneNumbers)) {
            return ['success' => false, 'message' => 'No phone numbers found', 'sms_messages' => []];
        }
        
        try {
            $response = self::sendBulkAfro($phoneNumbers, $message, $campaign);
            
            if ($response->successful()) {
                $messageId = $response->json('response.message_id') ?? null;
                
                // Create SMS records AFTER successful bulk send
                foreach ($recipients as $recipient) {
                    if ($recipient->phone) {
                        $smsMessages[] = SmsMessage::create([
                            'smsable_id' => $recipient->id,
                            'smsable_type' => get_class($recipient),
                            'phone_number' => $recipient->phone,
                            'message' => $message,
                            'status' => SmsMessage::STATUS_PENDING, // Let callback handle status updates
                            'provider' => SmsMessage::PROVIDER_AFRO,
                            'campaign' => $campaign,
                            'message_id' => $messageId,
                            'response_data' => $response->json(),
                            'sent_at' => now(),
                        ]);
                    }
                }
                
                Log::info('Bulk SMS sent and records created', [
                    'phone_count' => count($phoneNumbers),
                    'sms_records_created' => count($smsMessages),
                    'provider_message_id' => $messageId,
                    'campaign' => $campaign
                ]);
                
                return ['success' => true, 'message' => 'Bulk SMS sent successfully', 'sms_messages' => $smsMessages];
            } else {
                // Create failed SMS records
                foreach ($recipients as $recipient) {
                    if ($recipient->phone) {
                        $smsMessages[] = SmsMessage::create([
                            'smsable_id' => $recipient->id,
                            'smsable_type' => get_class($recipient),
                            'phone_number' => $recipient->phone,
                            'message' => $message,
                            'status' => SmsMessage::STATUS_FAILED,
                            'provider' => SmsMessage::PROVIDER_AFRO,
                            'campaign' => $campaign,
                            'error_message' => 'Bulk API returned unsuccessful response',
                            'response_data' => $response->json(),
                        ]);
                    }
                }
                
                Log::warning('Bulk SMS failed and records created', [
                    'phone_count' => count($phoneNumbers),
                    'sms_records_created' => count($smsMessages),
                    'status' => $response->status()
                ]);
                
                return ['success' => false, 'message' => 'Bulk SMS failed', 'sms_messages' => $smsMessages];
            }
        } catch (\Exception $e) {
            // Create failed SMS records
            foreach ($recipients as $recipient) {
                if ($recipient->phone) {
                    $smsMessages[] = SmsMessage::create([
                        'smsable_id' => $recipient->id,
                        'smsable_type' => get_class($recipient),
                        'phone_number' => $recipient->phone,
                        'message' => $message,
                        'status' => SmsMessage::STATUS_FAILED,
                        'provider' => SmsMessage::PROVIDER_AFRO,
                        'campaign' => $campaign,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
            
            Log::error('Bulk SMS exception and records created', [
                'phone_count' => count($phoneNumbers),
                'sms_records_created' => count($smsMessages),
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => 'Bulk SMS error: ' . $e->getMessage(), 'sms_messages' => $smsMessages];
        }
    }

}

