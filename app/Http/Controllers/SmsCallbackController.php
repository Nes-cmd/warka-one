<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsCallbackController extends Controller
{
    /**
     * Handle SMS delivery status callback from provider
     */
    public function handleCallback(Request $request)
    {
        // Log all incoming data for debugging
        Log::info('SMS Callback Received', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'query_params' => $request->query(),
            'body' => $request->all(),
            'raw_content' => $request->getContent(),
        ]);

        try {
            // Extract message ID from the callback (this might vary by provider)
            $messageId = $this->extractMessageId($request);
            $status = $this->extractStatus($request);
            
            if (!$messageId) {
                Log::warning('SMS Callback: No message ID found', $request->all());
                return response()->json(['error' => 'Message ID not found'], 400);
            }

            // Find the SMS message by provider's message ID
            $smsMessage = SmsMessage::where('message_id', $messageId)->first();
            
            if (!$smsMessage) {
                Log::warning('SMS Callback: Message not found', [
                    'message_id' => $messageId,
                    'callback_data' => $request->all()
                ]);
                return response()->json(['error' => 'Message not found'], 404);
            }

            // Update SMS message status based on callback
            $this->updateSmsStatus($smsMessage, $status, $request->all());

            Log::info('SMS Callback Processed Successfully', [
                'sms_message_id' => $smsMessage->id,
                'provider_message_id' => $messageId,
                'status' => $status,
                'smsable_type' => $smsMessage->smsable_type,
                'smsable_id' => $smsMessage->smsable_id,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('SMS Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'callback_data' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Extract message ID from callback request
     * This method should be customized based on your SMS provider's callback format
     */
    private function extractMessageId(Request $request): ?string
    {
        // Try different possible field names for message ID
        $possibleFields = ['messageId', 'message_id', 'id', 'msgId', 'msg_id', 'reference'];
        
        foreach ($possibleFields as $field) {
            if ($request->has($field)) {
                return $request->input($field);
            }
        }

        // If not found in request body, try query parameters
        foreach ($possibleFields as $field) {
            if ($request->query($field)) {
                return $request->query($field);
            }
        }

        return null;
    }

    /**
     * Extract delivery status from callback request
     */
    private function extractStatus(Request $request): string
    {
        // Try different possible field names for status
        $statusField = $request->input('status') ?? $request->query('status');
        
        if (!$statusField) {
            return 'unknown';
        }

        // Normalize status values
        $status = strtolower(trim($statusField));
        
        // Map provider-specific statuses to our standard statuses
        $statusMap = [
            'delivered' => SmsMessage::STATUS_DELIVERED,
            'delivery' => SmsMessage::STATUS_DELIVERED,
            'success' => SmsMessage::STATUS_DELIVERED,
            'completed' => SmsMessage::STATUS_DELIVERED,
            'failed' => SmsMessage::STATUS_FAILED,
            'failure' => SmsMessage::STATUS_FAILED,
            'error' => SmsMessage::STATUS_FAILED,
            'sent' => SmsMessage::STATUS_SENT,
            'pending' => SmsMessage::STATUS_PENDING,
        ];

        return $statusMap[$status] ?? 'unknown';
    }

    /**
     * Update SMS message status based on callback data
     */
    private function updateSmsStatus(SmsMessage $smsMessage, string $status, array $callbackData): void
    {
        switch ($status) {
            case SmsMessage::STATUS_DELIVERED:
                $smsMessage->markAsDelivered();
                break;
                
            case SmsMessage::STATUS_FAILED:
                $errorMessage = $callbackData['error'] ?? $callbackData['reason'] ?? 'Delivery failed';
                $smsMessage->markAsFailed($errorMessage);
                break;
                
            case SmsMessage::STATUS_SENT:
                $smsMessage->markAsSent(
                    $smsMessage->message_id,
                    $callbackData
                );
                break;
                
            default:
                // For unknown statuses, just log and update response data
                $smsMessage->update([
                    'response_data' => array_merge($smsMessage->response_data ?? [], $callbackData)
                ]);
                break;
        }
    }
}
