<?php
 
namespace App\Models\Passport;
 
use Laravel\Passport\Client as BaseClient;
 
class Client extends BaseClient
{
    protected $casts = [
        'use_auth_types' => 'array',
    ];
    /**
     * Determine if the client should skip the authorization prompt.
     */
    public function skipsAuthorization(): bool
    {
        return true;
    }
}