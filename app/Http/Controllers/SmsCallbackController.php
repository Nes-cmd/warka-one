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
                // Debug: Let's see what message_ids we have in the database
                $recentMessages = SmsMessage::where('created_at', '>=', now()->subHours(2))
                    ->select('id', 'message_id', 'phone_number', 'status', 'created_at')
                    ->get();
                
                Log::warning('SMS Callback: Message not found', [
                    'message_id' => $messageId,
                    'callback_data' => $request->all(),
                    'recent_messages' => $recentMessages->toArray(),
                    'total_recent_count' => $recentMessages->count()
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
        $status = strtoupper(trim($statusField));
        
        // Map provider-specific statuses to our standard statuses
        $statusMap = [
            // Provider-specific statuses (from your logs)
            'ESME_ROK' => SmsMessage::STATUS_SENT,      // SMS accepted by provider
            'QUEUED' => SmsMessage::STATUS_PENDING,     // SMS queued for delivery
            'DELIVRD' => SmsMessage::STATUS_DELIVERED,  // SMS delivered to recipient
            
            // Common delivery statuses
            'DELIVERED' => SmsMessage::STATUS_DELIVERED,
            'DELIVERY' => SmsMessage::STATUS_DELIVERED,
            'DELIVERED_TO_HANDSET' => SmsMessage::STATUS_DELIVERED,
            'DELIVERED_TO_NETWORK' => SmsMessage::STATUS_DELIVERED,
            'DELIVERED_TO_TERMINAL' => SmsMessage::STATUS_DELIVERED,
            
            // Success statuses
            'SUCCESS' => SmsMessage::STATUS_DELIVERED,
            'COMPLETED' => SmsMessage::STATUS_DELIVERED,
            'OK' => SmsMessage::STATUS_DELIVERED,
            
            // Failed statuses
            'FAILED' => SmsMessage::STATUS_FAILED,
            'FAILURE' => SmsMessage::STATUS_FAILED,
            'ERROR' => SmsMessage::STATUS_FAILED,
            'REJECTED' => SmsMessage::STATUS_FAILED,
            'EXPIRED' => SmsMessage::STATUS_FAILED,
            'UNDELIVERABLE' => SmsMessage::STATUS_FAILED,
            'UNKNOWN' => SmsMessage::STATUS_FAILED,
            'UNKNOWN_SUBSCRIBER' => SmsMessage::STATUS_FAILED,
            'SUBSCRIBER_UNAVAILABLE' => SmsMessage::STATUS_FAILED,
            'NETWORK_ERROR' => SmsMessage::STATUS_FAILED,
            'INVALID_NUMBER' => SmsMessage::STATUS_FAILED,
            
            // Sent statuses
            'SENT' => SmsMessage::STATUS_SENT,
            'ACCEPTED' => SmsMessage::STATUS_SENT,
            'SUBMITTED' => SmsMessage::STATUS_SENT,
            
            // Pending statuses
            'PENDING' => SmsMessage::STATUS_PENDING,
            'PROCESSING' => SmsMessage::STATUS_PENDING,
            'IN_PROGRESS' => SmsMessage::STATUS_PENDING,
        ];

        $mappedStatus = $statusMap[$status] ?? null;
        
        if ($mappedStatus) {
            return $mappedStatus;
        }
        
        // Smart fallback for unknown statuses
        $statusLower = strtolower($status);
        
        // Check for delivery indicators
        if (str_contains($statusLower, 'deliver') || str_contains($statusLower, 'success') || str_contains($statusLower, 'complete')) {
            return SmsMessage::STATUS_DELIVERED;
        }
        
        // Check for failure indicators
        if (str_contains($statusLower, 'fail') || str_contains($statusLower, 'error') || str_contains($statusLower, 'reject') || 
            str_contains($statusLower, 'expire') || str_contains($statusLower, 'invalid') || str_contains($statusLower, 'unknown')) {
            return SmsMessage::STATUS_FAILED;
        }
        
        // Check for sent indicators
        if (str_contains($statusLower, 'sent') || str_contains($statusLower, 'accept') || str_contains($statusLower, 'submit')) {
            return SmsMessage::STATUS_SENT;
        }
        
        // Check for pending indicators
        if (str_contains($statusLower, 'pending') || str_contains($statusLower, 'queue') || str_contains($statusLower, 'process')) {
            return SmsMessage::STATUS_PENDING;
        }
        
        // Default to unknown for unrecognized statuses
        return 'unknown';
    }

    /**
     * Update SMS message status based on callback data
     */
    private function updateSmsStatus(SmsMessage $smsMessage, string $status, array $callbackData): void
    {
        $providerStatus = $callbackData['status'] ?? 'unknown';
        $description = $callbackData['description'] ?? '';
        
        switch ($status) {
            case SmsMessage::STATUS_DELIVERED:
                // Only update to delivered if not already delivered
                if ($smsMessage->status !== SmsMessage::STATUS_DELIVERED) {
                    $smsMessage->markAsDelivered();
                    
                    Log::info('SMS marked as delivered', [
                        'sms_message_id' => $smsMessage->id,
                        'provider_status' => $providerStatus,
                        'description' => $description
                    ]);
                }
                break;
                
            case SmsMessage::STATUS_FAILED:
                $errorMessage = $callbackData['error'] ?? $callbackData['reason'] ?? $description ?: 'Delivery failed';
                $smsMessage->markAsFailed($errorMessage);
                
                Log::warning('SMS marked as failed', [
                    'sms_message_id' => $smsMessage->id,
                    'provider_status' => $providerStatus,
                    'error_message' => $errorMessage
                ]);
                break;
                
            case SmsMessage::STATUS_SENT:
                // Update to sent status and store provider message ID
                $providerMessageId = $callbackData['message_id'] ?? null;
                
                if ($providerMessageId && !$smsMessage->message_id) {
                    // First time we get the provider message ID
                    $smsMessage->markAsSent($providerMessageId, $callbackData);
                    
                    Log::info('SMS marked as sent with provider ID', [
                        'sms_message_id' => $smsMessage->id,
                        'provider_message_id' => $providerMessageId,
                        'provider_status' => $providerStatus,
                        'description' => $description
                    ]);
                } else {
                    // Update response data for subsequent callbacks
                    $smsMessage->update([
                        'response_data' => array_merge($smsMessage->response_data ?? [], $callbackData)
                    ]);
                    
                    Log::info('SMS callback received (already sent)', [
                        'sms_message_id' => $smsMessage->id,
                        'provider_status' => $providerStatus,
                        'description' => $description
                    ]);
                }
                break;
                
            case SmsMessage::STATUS_PENDING:
                // Update response data for pending status (like QUEUED)
                $smsMessage->update([
                    'response_data' => array_merge($smsMessage->response_data ?? [], $callbackData)
                ]);
                
                Log::info('SMS callback received (pending)', [
                    'sms_message_id' => $smsMessage->id,
                    'provider_status' => $providerStatus,
                    'description' => $description
                ]);
                break;
                
            default:
                // For unknown statuses, just log and update response data
                $smsMessage->update([
                    'response_data' => array_merge($smsMessage->response_data ?? [], $callbackData)
                ]);
                
                Log::warning('Unknown SMS callback status', [
                    'sms_message_id' => $smsMessage->id,
                    'provider_status' => $providerStatus,
                    'description' => $description,
                    'mapped_status' => $status
                ]);
                break;
        }
    }
}
