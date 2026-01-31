<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('regenerate_secret')
                ->label('Regenerate Secret')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Regenerate Client Secret')
                ->modalDescription('Are you sure you want to revoke and regenerate this client secret? This action cannot be undone and any applications using this secret will need to be updated.')
                ->modalSubmitActionLabel('Regenerate')
                ->action(function () {
                    // Get all access token IDs for this client before revoking
                    $accessTokenIds = DB::table('oauth_access_tokens')
                        ->where('client_id', $this->record->id)
                        ->where('revoked', false)
                        ->pluck('id');
                    
                    // Revoke all refresh tokens associated with these access tokens
                    if ($accessTokenIds->isNotEmpty()) {
                        DB::table('oauth_refresh_tokens')
                            ->whereIn('access_token_id', $accessTokenIds)
                            ->where('revoked', false)
                            ->update(['revoked' => true]);
                    }
                    
                    // Revoke all access tokens for this client
                    DB::table('oauth_access_tokens')
                        ->where('client_id', $this->record->id)
                        ->where('revoked', false)
                        ->update(['revoked' => true]);
                    
                    // Regenerate the secret
                    $clients = app(ClientRepository::class);
                    $clients->regenerateSecret($this->record);
                    
                    // Refresh the form to show the new secret
                    $this->fillForm();

                    Notification::make()->success()->title('Client secret has been regenerated and all tokens have been revoked. Clients will need to re-authenticate.')->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
