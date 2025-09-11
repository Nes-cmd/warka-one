<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Helpers\SmsSend;
use App\Jobs\SendBatchSmsJob;
use App\Models\Country;
use App\Models\SmsMessage;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form 
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->requiredWithout('phone')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Select::make('country_id')
                        ->label(__('Country'))
                        ->requiredWith('phone')
                        ->options(Country::pluck('name', 'id'))
                        ->default(1),
                    Forms\Components\TextInput::make('phone')
                        ->requiredWithout('email')
                        ->tel()
                        ->maxLength(9),
                    Forms\Components\DateTimePicker::make('email_verified_at')->native(false),
                    Forms\Components\DateTimePicker::make('phone_verified_at')->native(false),
                    
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->required($form->getOperation() === "create")
                        ->maxLength(255),

                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\Action::make('send_sms_all')
                    ->label('Send SMS to All Users')
                    ->icon('heroicon-o-megaphone')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('message')
                            ->label('SMS Message')
                            ->required()
                            ->rows(3)
                            ->placeholder('Enter your SMS message here...')
                            ->maxLength(160),
                        Forms\Components\TextInput::make('campaign')
                            ->label('Campaign Name (Optional)')
                            ->placeholder('Enter campaign name...'),
                        Forms\Components\Radio::make('send_method')
                            ->label('Send Method')
                            ->options([
                                'bulk_api' => 'Bulk API (Fast, but may have limitations)',
                                'single_api' => 'Single API with Jobs (Reliable, processed in batches of 500)'
                            ])
                            ->default('single_api')
                            ->required(),
                        Forms\Components\Placeholder::make('warning')
                            ->content('⚠️ This will send SMS to ALL users with phone numbers. Use with caution!')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data): void {
                        $users = User::whereNotNull('phone')->get();
                        
                        // dd($users);
                        if ($users->isEmpty()) {
                            Notification::make()
                                ->title('No Users with Phone Numbers')
                                ->body('No users have phone numbers in the system.')
                                ->warning()
                                ->send();
                            return;
                        }
                        
                        $phoneNumbers = $users->pluck('phone')->toArray();
                        $userNames = $users->pluck('name')->toArray();
                        $smsMessages = [];
                        
                        // Create SMS records for all users with phone numbers
                        foreach ($users as $user) {
                            $smsMessages[] = SmsMessage::create([
                                'smsable_id' => $user->id,
                                'smsable_type' => User::class,
                                'phone_number' => $user->phone,
                                'message' => $data['message'],
                                'status' => SmsMessage::STATUS_PENDING,
                                'provider' => SmsMessage::PROVIDER_AFRO,
                                'campaign' => $data['campaign'] ?? null,
                            ]);
                        }
                        
                        if ($data['send_method'] === 'bulk_api') {
                            // Use bulk API
                            try {
                                $response = SmsSend::sendBulkAfro(
                                    $phoneNumbers, 
                                    $data['message'], 
                                    $data['campaign'] ?? null
                                );
                                
                                if ($response->successful()) {
                                    // Update SMS messages with provider response but keep as PENDING
                                    // Let callback system handle actual delivery status
                                    foreach ($smsMessages as $smsMessage) {
                                        $smsMessage->update([
                                            'message_id' => $response->json('message_id') ?? null,
                                            'response_data' => $response->json() ?? null,
                                            'sent_at' => now(),
                                            // Keep status as PENDING - callback will update to SENT/DELIVERED/FAILED
                                        ]);
                                    }
                                    
                                    Notification::make()
                                        ->title('Bulk SMS Submitted Successfully')
                                        ->body("SMS submitted to provider for ALL " . count($phoneNumbers) . " users. Delivery status will be updated via callbacks.")
                                        ->success()
                                        ->send();
                                } else {
                                    // Mark all SMS messages as failed
                                    foreach ($smsMessages as $smsMessage) {
                                        $smsMessage->markAsFailed('Bulk API returned unsuccessful response');
                                    }
                                    
                                    Notification::make()
                                        ->title('Bulk SMS Failed')
                                        ->body('Failed to send bulk SMS to all users. Please check your configuration.')
                                        ->danger()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                // Mark all SMS messages as failed
                                foreach ($smsMessages as $smsMessage) {
                                    $smsMessage->markAsFailed($e->getMessage());
                                }
                                
                                Notification::make()
                                    ->title('Bulk SMS Error')
                                    ->body('An error occurred while sending bulk SMS: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            // Use single API with jobs (batch processing)
                            $smsMessageIds = collect($smsMessages)->pluck('id')->toArray();
                            
                            // Split into batches of 500
                            $batches = array_chunk($smsMessageIds, 500);
                            
                            foreach ($batches as $batch) {
                                SendBatchSmsJob::dispatch($batch, 'afro');
                            }
                            
                            Notification::make()
                                ->title('SMS Jobs Queued Successfully')
                                ->body("SMS sending jobs queued for ALL " . count($phoneNumbers) . " users. Processing in batches of 500.")
                                ->success()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Send SMS to All Users')
                    ->modalDescription('Are you sure you want to send SMS to ALL users with phone numbers? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, Send SMS to All'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->searchable()->copyable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')->copyable()->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime('M y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_verified_at')
                    ->dateTime('M y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime('M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('send_sms')
                    ->label('Send SMS')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->form([
                        Forms\Components\Textarea::make('message')
                            ->label('SMS Message')
                            ->required()
                            ->rows(3)
                            ->placeholder('Enter your SMS message here...')
                            ->maxLength(160),
                        Forms\Components\TextInput::make('campaign')
                            ->label('Campaign Name (Optional)')
                            ->placeholder('Enter campaign name...'),
                    ])
                    ->action(function (User $record, array $data): void {
                        if (!$record->phone) {
                            Notification::make()
                                ->title('No Phone Number')
                                ->body('This user does not have a phone number.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            $response = SmsSend::sendThroughAfro($record->phone, $data['message']);
                            
                            if ($response->successful()) {
                                // Create SMS record AFTER successful API call
                                // Keep status as PENDING - callback will update to SENT/DELIVERED/FAILED
                                $smsMessage = SmsMessage::create([
                                    'smsable_id' => $record->id,
                                    'smsable_type' => User::class,
                                    'phone_number' => $record->phone,
                                    'message' => $data['message'],
                                    'status' => SmsMessage::STATUS_PENDING,
                                    'provider' => SmsMessage::PROVIDER_AFRO,
                                    'campaign' => $data['campaign'] ?? null,
                                    'message_id' => $response->json('message_id') ?? null, // Store provider's message ID
                                    'response_data' => $response->json() ?? null,
                                    'sent_at' => now(),
                                ]);
                                
                                Notification::make()
                                    ->title('SMS Submitted Successfully')
                                    ->body("SMS submitted to provider for {$record->name} ({$record->phone}). Delivery status will be updated via callbacks.")
                                    ->success()
                                    ->send();
                            } else {
                                // Create failed SMS record
                                SmsMessage::create([
                                    'smsable_id' => $record->id,
                                    'smsable_type' => User::class,
                                    'phone_number' => $record->phone,
                                    'message' => $data['message'],
                                    'status' => SmsMessage::STATUS_FAILED,
                                    'provider' => SmsMessage::PROVIDER_AFRO,
                                    'campaign' => $data['campaign'] ?? null,
                                    'error_message' => 'API returned unsuccessful response',
                                    'response_data' => $response->json() ?? null,
                                ]);
                                
                                Notification::make()
                                    ->title('SMS Failed')
                                    ->body('Failed to send SMS. Please check your configuration.')
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            // Create failed SMS record
                            SmsMessage::create([
                                'smsable_id' => $record->id,
                                'smsable_type' => User::class,
                                'phone_number' => $record->phone,
                                'message' => $data['message'],
                                'status' => SmsMessage::STATUS_FAILED,
                                'provider' => SmsMessage::PROVIDER_AFRO,
                                'campaign' => $data['campaign'] ?? null,
                                'error_message' => $e->getMessage(),
                            ]);
                            
                            Notification::make()
                                ->title('SMS Error')
                                ->body('An error occurred while sending SMS: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (User $record): bool => !is_null($record->phone)),
            ])
            ->bulkActions([
               
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('send_bulk_sms')
                        ->label('Send Bulk SMS')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('info')
                        ->form([
                            Forms\Components\Textarea::make('message')
                                ->label('SMS Message')
                                ->required()
                                ->rows(3)
                                ->placeholder('Enter your SMS message here...')
                                ->maxLength(160),
                            Forms\Components\TextInput::make('campaign')
                                ->label('Campaign Name (Optional)')
                                ->placeholder('Enter campaign name...'),
                            Forms\Components\Radio::make('send_method')
                                ->label('Send Method')
                                ->options([
                                    'bulk_api' => 'Bulk API (Fast, but may have limitations)',
                                    'single_api' => 'Single API with Jobs (Reliable, processed in batches of 500)'
                                ])
                                ->default('single_api')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $phoneNumbers = [];
                            $usersWithPhones = [];
                            $smsMessages = [];
                            
                            // Collect users with phone numbers
                            foreach ($records as $record) {
                                if ($record->phone) {
                                    $phoneNumbers[] = $record->phone;
                                    $usersWithPhones[] = $record->name;
                                }
                            }
                            
                            if (empty($phoneNumbers)) {
                                Notification::make()
                                    ->title('No Phone Numbers')
                                    ->body('None of the selected users have phone numbers.')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            if ($data['send_method'] === 'bulk_api') {
                                // Use bulk API
                                try {
                                    $response = SmsSend::sendBulkAfro(
                                        $phoneNumbers, 
                                        $data['message'], 
                                        $data['campaign'] ?? null
                                    );
                                    
                                if ($response->successful()) {
                                    // Create SMS records AFTER successful bulk API call
                                    // Keep status as PENDING - callback will update to SENT/DELIVERED/FAILED
                                    foreach ($records as $record) {
                                        if ($record->phone) {
                                            SmsMessage::create([
                                                'smsable_id' => $record->id,
                                                'smsable_type' => User::class,
                                                'phone_number' => $record->phone,
                                                'message' => $data['message'],
                                                'status' => SmsMessage::STATUS_PENDING,
                                                'provider' => SmsMessage::PROVIDER_AFRO,
                                                'campaign' => $data['campaign'] ?? null,
                                                'message_id' => $response->json('message_id') ?? null,
                                                'response_data' => $response->json() ?? null,
                                                'sent_at' => now(),
                                            ]);
                                        }
                                    }
                                    
                                    Notification::make()
                                        ->title('Bulk SMS Submitted Successfully')
                                        ->body("SMS submitted to provider for " . count($phoneNumbers) . " users. Delivery status will be updated via callbacks.")
                                        ->success()
                                        ->send();
                                } else {
                                    // Create failed SMS records
                                    foreach ($records as $record) {
                                        if ($record->phone) {
                                            SmsMessage::create([
                                                'smsable_id' => $record->id,
                                                'smsable_type' => User::class,
                                                'phone_number' => $record->phone,
                                                'message' => $data['message'],
                                                'status' => SmsMessage::STATUS_FAILED,
                                                'provider' => SmsMessage::PROVIDER_AFRO,
                                                'campaign' => $data['campaign'] ?? null,
                                                'error_message' => 'Bulk API returned unsuccessful response',
                                                'response_data' => $response->json() ?? null,
                                            ]);
                                        }
                                    }
                                    
                                    Notification::make()
                                        ->title('Bulk SMS Failed')
                                        ->body('Failed to send bulk SMS. Please check your configuration.')
                                        ->danger()
                                        ->send();
                                }
                                } catch (\Exception $e) {
                                    // Create failed SMS records
                                    foreach ($records as $record) {
                                        if ($record->phone) {
                                            SmsMessage::create([
                                                'smsable_id' => $record->id,
                                                'smsable_type' => User::class,
                                                'phone_number' => $record->phone,
                                                'message' => $data['message'],
                                                'status' => SmsMessage::STATUS_FAILED,
                                                'provider' => SmsMessage::PROVIDER_AFRO,
                                                'campaign' => $data['campaign'] ?? null,
                                                'error_message' => $e->getMessage(),
                                            ]);
                                        }
                                    }
                                    
                                    Notification::make()
                                        ->title('Bulk SMS Error')
                                        ->body('An error occurred while sending bulk SMS: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            } else {
                                // Use single API with jobs (batch processing)
                                // Create SMS records first for jobs to process
                                $smsMessages = [];
                                foreach ($records as $record) {
                                    if ($record->phone) {
                                        $smsMessages[] = SmsMessage::create([
                                            'smsable_id' => $record->id,
                                            'smsable_type' => User::class,
                                            'phone_number' => $record->phone,
                                            'message' => $data['message'],
                                            'status' => SmsMessage::STATUS_PENDING,
                                            'provider' => SmsMessage::PROVIDER_AFRO,
                                            'campaign' => $data['campaign'] ?? null,
                                        ]);
                                    }
                                }
                                
                                $smsMessageIds = collect($smsMessages)->pluck('id')->toArray();
                                
                                // Split into batches of 500
                                $batches = array_chunk($smsMessageIds, 500);
                                
                                foreach ($batches as $batch) {
                                    SendBatchSmsJob::dispatch($batch, 'afro');
                                }
                                
                                Notification::make()
                                    ->title('SMS Jobs Queued Successfully')
                                    ->body("SMS sending jobs queued for " . count($phoneNumbers) . " users. Processing in batches of 500.")
                                    ->success()
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
            
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
