<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmsMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'smsable_id',
        'smsable_type',
        'phone_number',
        'message',
        'status',
        'provider',
        'campaign',
        'message_id',
        'response_data',
        'error_message',
        'sent_at',
        'delivered_at',
    ];

    protected $casts = [
        'response_data' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';

    // Provider constants
    const PROVIDER_AFRO = 'afro';
    const PROVIDER_FARIS = 'faris';

    public function smsable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsSent(string $messageId = null, array $responseData = null): void
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'message_id' => $messageId,
            'response_data' => $responseData,
            'sent_at' => now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage = null): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }
}
