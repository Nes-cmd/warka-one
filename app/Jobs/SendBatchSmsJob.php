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
                // Use SmsSend::send() method for each recipient
                SmsSend::send($recipient->phone, $this->message, $recipient, $this->campaign, $this->provider);
                
                // Add small delay between SMS to avoid rate limiting
                usleep(100000); // 0.1 second delay
                
            } catch (\Exception $e) {
                Log::error('SendBatchSmsJob: Failed to send SMS', [
                    'recipient_id' => $recipient->id ?? 'unknown',
                    'phone' => $recipient->phone ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::info('SendBatchSmsJob completed', [
            'processed_count' => count($this->recipients),
            'provider' => $this->provider
        ]);
    }

}
