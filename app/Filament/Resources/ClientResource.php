<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Passport\Client as PassportClient;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;

class ClientResource extends Resource
{
    protected static ?string $model = PassportClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('redirect')->required()->url(),
                    Forms\Components\Select::make('use_auth_types')->multiple()->options(['phone' => 'Phone', 'email' => 'Email'])->required()->label('Auth with'),
                    Forms\Components\Select::make('pass_type')->options(['password' => 'Password', 'otp' => 'OTP'])->required()->label('Pass Type'),
                    Forms\Components\Toggle::make('registration_enabled')->hint('If enabled, for new users, they will be asked to register first before they can login'),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('use_auth_types')->badge()->label('Auth with'),
                Tables\Columns\TextColumn::make('redirect')->copyable(),
                Tables\Columns\TextColumn::make('id')->copyable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('secret')->copyable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('registration_enabled')->boolean(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('regenerate_secret')
                    ->label('Regenerate Secret')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerate Client Secret')
                    ->modalDescription('Are you sure you want to revoke and regenerate this client secret? This action cannot be undone and any applications using this secret will need to be updated.')
                    ->modalSubmitActionLabel('Regenerate')
                    ->action(function (PassportClient $record) {
                        // Get all access token IDs for this client before revoking
                        $accessTokenIds = DB::table('oauth_access_tokens')
                            ->where('client_id', $record->id)
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
                            ->where('client_id', $record->id)
                            ->where('revoked', false)
                            ->update(['revoked' => true]);
                        
                        // Regenerate the secret
                        $clients = app(ClientRepository::class);
                        $clients->regenerateSecret($record);

                        Notification::make()->success()->title('Client secret has been regenerated and all tokens have been revoked. Clients will need to re-authenticate.')->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
