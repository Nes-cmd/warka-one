<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expire_at' => 'datetime',
    ];

    /**
     * Limit verification lookups to the session that requested the OTP.
     */
    public function scopeForCurrentSession(Builder $query): Builder
    {
        $sessionId = session()->getId();

        if ($sessionId === null || $sessionId === '') {
            return $query->whereRaw('0 = 1');
        }

        return $query->where('session_id', $sessionId);
    }

    public static function latestActiveForCandidate(string $candidate): ?self
    {
        return static::query()
            ->where('candidate', $candidate)
            ->forCurrentSession()
            ->where('expire_at', '>=', now())
            ->latest()
            ->first();
    }

    public static function latestVerifiedForCandidate(string $candidate): ?self
    {
        return static::query()
            ->where('candidate', $candidate)
            ->forCurrentSession()
            ->where('status', 'verified')
            ->latest()
            ->first();
    }
}
