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
        public array $smsMessageIds,
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
            'sms_message_ids' => $this->smsMessageIds,
            'provider' => $this->provider,
            'count' => count($this->smsMessageIds)
        ]);

        $smsMessages = SmsMessage::whereIn('id', $this->smsMessageIds)
            ->where('status', SmsMessage::STATUS_PENDING)
            ->get();

        if ($smsMessages->isEmpty()) {
            Log::warning('SendBatchSmsJob: No pending SMS messages found', [
                'sms_message_ids' => $this->smsMessageIds
            ]);
            return;
        }

        foreach ($smsMessages as $smsMessage) {
            try {
                $this->sendSingleSms($smsMessage);
                
                // Add small delay between SMS to avoid rate limiting
                usleep(100000); // 0.1 second delay
                
            } catch (\Exception $e) {
                Log::error('SendBatchSmsJob: Failed to send SMS', [
                    'sms_message_id' => $smsMessage->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                $smsMessage->markAsFailed($e->getMessage());
            }
        }

        Log::info('SendBatchSmsJob completed', [
            'processed_count' => $smsMessages->count(),
            'sms_message_ids' => $this->smsMessageIds
        ]);
    }

    /**
     * Send individual SMS message
     */
    private function sendSingleSms(SmsMessage $smsMessage): void
    {
        $response = SmsSend::sendThroughAfro(
            $smsMessage->phone_number,
            $smsMessage->message
        );

        if ($response->successful()) {
            $smsMessage->markAsSent(
                $response->json('messageId') ?? null,
                $response->json() ?? null
            );
            
            Log::info('SMS sent successfully via job', [
                'sms_message_id' => $smsMessage->id,
                'phone_number' => $smsMessage->phone_number,
                'provider_message_id' => $response->json('messageId')
            ]);
        } else {
            $errorMessage = 'API returned unsuccessful response: ' . $response->status();
            $smsMessage->markAsFailed($errorMessage);
            
            Log::warning('SMS failed via job', [
                'sms_message_id' => $smsMessage->id,
                'phone_number' => $smsMessage->phone_number,
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
            'sms_message_ids' => $this->smsMessageIds,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Mark all SMS messages as failed
        SmsMessage::whereIn('id', $this->smsMessageIds)
            ->where('status', SmsMessage::STATUS_PENDING)
            ->update([
                'status' => SmsMessage::STATUS_FAILED,
                'error_message' => 'Job failed: ' . $exception->getMessage()
            ]);
    }
}
