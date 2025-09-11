<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsMessageResource\Pages;
use App\Filament\Resources\SmsMessageResource\RelationManagers;
use App\Helpers\SmsSend;
use App\Jobs\SendBatchSmsJob;
use App\Models\SmsMessage;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SmsMessageResource extends Resource
{
    protected static ?string $model = SmsMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'SMS Messages';
    
    protected static ?string $modelLabel = 'SMS Message';
    
    protected static ?string $pluralModelLabel = 'SMS Messages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('smsable'))
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
                        
                        if ($users->isEmpty()) {
                            Notification::make()
                                ->title('No Users with Phone Numbers')
                                ->body('No users have phone numbers in the system.')
                                ->warning()
                                ->send();
                            return;
                        }
                        
                        $phoneNumbers = $users->pluck('phone')->toArray();
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
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('smsable_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('smsable.name')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone')
                    ->copyable()
                    ->searchable()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        SmsMessage::STATUS_PENDING => 'warning',
                        SmsMessage::STATUS_SENT => 'info',
                        SmsMessage::STATUS_DELIVERED => 'success',
                        SmsMessage::STATUS_FAILED => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->color('secondary'),
                    
                Tables\Columns\TextColumn::make('campaign')
                    ->label('Campaign')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('message_id')
                    ->label('Provider ID')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('delivered_at')
                    ->label('Delivered At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        SmsMessage::STATUS_PENDING => 'Pending',
                        SmsMessage::STATUS_SENT => 'Sent',
                        SmsMessage::STATUS_DELIVERED => 'Delivered',
                        SmsMessage::STATUS_FAILED => 'Failed',
                    ]),
                    
                Tables\Filters\SelectFilter::make('provider')
                    ->options([
                        SmsMessage::PROVIDER_AFRO => 'Afro',
                        SmsMessage::PROVIDER_FARIS => 'Faris',
                    ]),
                    
                Tables\Filters\SelectFilter::make('smsable_type')
                    ->label('Recipient Type')
                    ->options([
                        'App\\Models\\User' => 'User',
                    ]),
                    
                Tables\Filters\SelectFilter::make('campaign')
                    ->label('Campaign')
                    ->options(function () {
                        // Get all unique campaigns from SMS messages
                        $campaigns = SmsMessage::select('campaign')
                            ->distinct()
                            ->whereNotNull('campaign')
                            ->where('campaign', '!=', '')
                            ->orderBy('campaign')
                            ->pluck('campaign', 'campaign')
                            ->toArray();
                        
                        // Add "No Campaign" option for null/empty campaigns
                        $campaigns['no_campaign'] = 'No Campaign';
                        
                        return $campaigns;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['value']) {
                            return $query;
                        }
                        
                        if ($data['value'] === 'no_campaign') {
                            return $query->where(function ($q) {
                                $q->whereNull('campaign')
                                  ->orWhere('campaign', '');
                            });
                        }
                        
                        return $query->where('campaign', $data['value']);
                    }),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DateTimePicker::make('created_from')
                            ->label('Created from')->native(false),
                        Forms\Components\DateTimePicker::make('created_until')
                            ->label('Created until')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->filtersFormWidth(MaxWidth::Medium)
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\Action::make('retry')
                    ->label('Retry')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (SmsMessage $record): bool => $record->status === SmsMessage::STATUS_PENDING || $record->status === SmsMessage::STATUS_FAILED)
                    ->requiresConfirmation()
                    ->modalHeading('Retry SMS')
                    ->modalDescription('Are you sure you want to retry sending this SMS?')
                    ->action(function (SmsMessage $record): void {
                        try {
                            $response = SmsSend::sendThroughAfro($record->phone_number, $record->message);
                            
                            if ($response->successful()) {
                                $record->update([
                                    'status' => SmsMessage::STATUS_SENT,
                                    'message_id' => $response->json('message_id') ?? null,
                                    'response_data' => $response->json() ?? null,
                                    'sent_at' => now(),
                                    'error_message' => null,
                                ]);
                                
                                Notification::make()
                                    ->title('SMS Retry Successful')
                                    ->body("SMS retry sent to {$record->phone_number}")
                                    ->success()
                                    ->send();
                            } else {
                                $record->update([
                                    'status' => SmsMessage::STATUS_FAILED,
                                    'error_message' => 'Retry failed: API returned unsuccessful response',
                                    'response_data' => $response->json() ?? null,
                                ]);
                                
                                Notification::make()
                                    ->title('SMS Retry Failed')
                                    ->body('Failed to retry SMS. Please check your configuration.')
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            $record->update([
                                'status' => SmsMessage::STATUS_FAILED,
                                'error_message' => 'Retry failed: ' . $e->getMessage(),
                            ]);
                            
                            Notification::make()
                                ->title('SMS Retry Error')
                                ->body('An error occurred while retrying SMS: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                    
                Tables\Actions\ViewAction::make(),
                
            ])
            ->bulkActions([
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
                        Forms\Components\Placeholder::make('info')
                            ->content('📱 This will send SMS to users associated with the selected SMS records.')
                            ->columnSpanFull(),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        $phoneNumbers = [];
                        $usersWithPhones = [];
                        $smsMessages = [];
                        
                        // Debug: Log the selected records
                        Log::info('SMS Bulk Action - Selected records count: ' . $records->count());
                        
                        // Collect users with phone numbers from selected SMS records
                        foreach ($records as $record) {
                            if ($record->phone_number) {
                                $phoneNumbers[] = $record->phone_number;
                                $usersWithPhones[] = $record->smsable->name ?? $record->phone_number;
                            }
                        }
                        
                        Log::info('SMS Bulk Action - Phone numbers collected: ' . count($phoneNumbers));
                        
                        if (empty($phoneNumbers)) {
                            Notification::make()
                                ->title('No Phone Numbers')
                                ->body('None of the selected SMS records have phone numbers.')
                                ->warning()
                                ->send();
                            return;
                        }
                        
                        // Create new SMS records for the bulk send
                        foreach ($records as $record) {
                            if ($record->phone_number && $record->smsable_id && $record->smsable_type) {
                                $smsMessages[] = SmsMessage::create([
                                    'smsable_id' => $record->smsable_id,
                                    'smsable_type' => $record->smsable_type,
                                    'phone_number' => $record->phone_number,
                                    'message' => $data['message'],
                                    'status' => SmsMessage::STATUS_PENDING,
                                    'provider' => SmsMessage::PROVIDER_AFRO,
                                    'campaign' => $data['campaign'] ?? null,
                                ]);
                            }
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
                                        ->body("SMS submitted to provider for " . count($phoneNumbers) . " users. Delivery status will be updated via callbacks.")
                                        ->success()
                                        ->send();
                                } else {
                                    // Mark all SMS messages as failed
                                    foreach ($smsMessages as $smsMessage) {
                                        $smsMessage->markAsFailed('Bulk API returned unsuccessful response');
                                    }
                                    
                                    Notification::make()
                                        ->title('Bulk SMS Failed')
                                        ->body('Failed to send bulk SMS. Please check your configuration.')
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
                                ->body("SMS sending jobs queued for " . count($phoneNumbers) . " users. Processing in batches of 500.")
                                ->success()
                                ->send();
                        }
                    })
                    ->deselectRecordsAfterCompletion(),
                
                Tables\Actions\BulkAction::make('retry_selected')
                        ->label('Retry Selected')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Retry Selected SMS')
                        ->modalDescription('Are you sure you want to retry sending the selected SMS messages?')
                        ->action(function (Collection $records): void {
                            $retried = 0;
                            $failed = 0;
                            $skipped = 0;
                            
                            // Debug: Log the selected records
                            Log::info('SMS Retry Action - Selected records count: ' . $records->count());
                            
                            foreach ($records as $record) {
                                Log::info('SMS Retry Action - Processing record ID: ' . $record->id . ', Status: ' . $record->status);
                                
                                if ($record->status === SmsMessage::STATUS_PENDING || $record->status === SmsMessage::STATUS_FAILED) {
                                    try {
                                        $response = SmsSend::sendThroughAfro($record->phone_number, $record->message);
                                        
                                        if ($response->successful()) {
                                            $record->update([
                                                'status' => SmsMessage::STATUS_SENT,
                                                'message_id' => $response->json('message_id') ?? null,
                                                'response_data' => $response->json() ?? null,
                                                'sent_at' => now(),
                                                'error_message' => null,
                                            ]);
                                            $retried++;
                                        } else {
                                            $record->update([
                                                'status' => SmsMessage::STATUS_FAILED,
                                                'error_message' => 'Bulk retry failed: API returned unsuccessful response',
                                                'response_data' => $response->json() ?? null,
                                            ]);
                                            $failed++;
                                        }
                                    } catch (\Exception $e) {
                                        $record->update([
                                            'status' => SmsMessage::STATUS_FAILED,
                                            'error_message' => 'Bulk retry failed: ' . $e->getMessage(),
                                        ]);
                                        $failed++;
                                    }
                                } else {
                                    $skipped++;
                                }
                            }
                            
                            $message = "Retried: {$retried}, Failed: {$failed}";
                            if ($skipped > 0) {
                                $message .= ", Skipped: {$skipped}";
                            }
                            
                            Notification::make()
                                ->title('Bulk Retry Completed')
                                ->body($message)
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListSmsMessages::route('/'),
            'view' => Pages\ViewSmsMessage::route('/{record}'),
        ];
    }
}
