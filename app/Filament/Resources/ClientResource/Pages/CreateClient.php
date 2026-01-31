<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Remove secret from data - Passport will generate it
        $secret = $data['secret'] ?? null;
        unset($data['secret']);
        
        // Extract custom fields
        $useAuthTypes = $data['use_auth_types'] ?? [];
        $passType = $data['pass_type'] ?? 'password';
        $registrationEnabled = $data['registration_enabled'] ?? false;
        $description = $data['description'] ?? null;
        
        // Use Laravel Passport's ClientRepository to create the client properly
        // Parameters: userId, name, redirect, provider, personalAccessClient, passwordClient, confidential
        $clients = app(ClientRepository::class);
        $client = $clients->create(
            Auth::id(),
            $data['name'],
            $data['redirect'],
            null, // provider (null for default)
            false, // personal_access_client
            true,  // password_client
            true   // confidential (true for password clients - they need to securely store the secret)
        );

        // Update with custom fields
        $client->update([
            'use_auth_types' => $useAuthTypes,
            'pass_type' => $passType,
            'registration_enabled' => $registrationEnabled,
            'description' => $description,
        ]);

        return $client;
    }
}
