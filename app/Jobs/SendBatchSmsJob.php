<?php

namespace App\Jobs;

use App\Helpers\SmsSend;
use App\Models\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBatchSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout
    public $tries = 3; // Retry 3 times

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $recipients,
        public string $message,
        public ?string $campaign = null,
        public string $provider = 'afro'
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SendBatchSmsJob started', [
            'recipients_count' => count($this->recipients),
            'provider' => $this->provider,
            'message_preview' => substr($this->message, 0, 50) . '...'
        ]);

        foreach ($this->recipients as $recipient) {
            try {
                $this->sendSingleSms($recipient);
                
                // Add small delay between SMS to avoid rate limiting
                usleep(100000); // 0.1 second delay
                
            } catch (\Exception $e) {
                Log::error('SendBatchSmsJob: Failed to send SMS', [
                    'recipient_id' => $recipient->id ?? 'unknown',
                    'phone' => $recipient->phone ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Create failed SMS record
                SmsMessage::create([
                    'smsable_id' => $recipient->id,
                    'smsable_type' => get_class($recipient),
                    'phone_number' => $recipient->phone,
                    'message' => $this->message,
                    'status' => SmsMessage::STATUS_FAILED,
                    'provider' => SmsMessage::PROVIDER_AFRO,
                    'campaign' => $this->campaign,
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        Log::info('SendBatchSmsJob completed', [
            'processed_count' => count($this->recipients),
            'provider' => $this->provider
        ]);
    }

    /**
     * Send individual SMS message
     */
    private function sendSingleSms($recipient): void
    {
        $response = SmsSend::sendThroughAfro(
            $recipient->phone,
            $this->message
        );

        if ($response->successful()) {
            $messageId = $response->json('response.message_id') ?? null;
            
            // Create SMS record AFTER successful send
            SmsMessage::create([
                'smsable_id' => $recipient->id,
                'smsable_type' => get_class($recipient),
                'phone_number' => $recipient->phone,
                'message' => $this->message,
                'status' => SmsMessage::STATUS_PENDING, // Let callback handle status updates
                'provider' => SmsMessage::PROVIDER_AFRO,
                'campaign' => $this->campaign,
                'message_id' => $messageId,
                'response_data' => $response->json(),
                'sent_at' => now(),
            ]);
            
            Log::info('SMS sent successfully via job', [
                'recipient_id' => $recipient->id,
                'phone_number' => $recipient->phone,
                'provider_message_id' => $messageId
            ]);
        } else {
            $errorMessage = 'API returned unsuccessful response: ' . $response->status();
            
            // Create failed SMS record
            SmsMessage::create([
                'smsable_id' => $recipient->id,
                'smsable_type' => get_class($recipient),
                'phone_number' => $recipient->phone,
                'message' => $this->message,
                'status' => SmsMessage::STATUS_FAILED,
                'provider' => SmsMessage::PROVIDER_AFRO,
                'campaign' => $this->campaign,
                'error_message' => $errorMessage,
                'response_data' => $response->json(),
            ]);
            
            Log::warning('SMS failed via job', [
                'recipient_id' => $recipient->id,
                'phone_number' => $recipient->phone,
                'status' => $response->status(),
                'response' => $response->json()
            ]);
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendBatchSmsJob failed', [
            'recipients_count' => count($this->recipients),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Create failed SMS records for all recipients
        foreach ($this->recipients as $recipient) {
            SmsMessage::create([
                'smsable_id' => $recipient->id,
                'smsable_type' => get_class($recipient),
                'phone_number' => $recipient->phone,
                'message' => $this->message,
                'status' => SmsMessage::STATUS_FAILED,
                'provider' => SmsMessage::PROVIDER_AFRO,
                'campaign' => $this->campaign,
                'error_message' => 'Job failed: ' . $exception->getMessage(),
            ]);
        }
    }
}
