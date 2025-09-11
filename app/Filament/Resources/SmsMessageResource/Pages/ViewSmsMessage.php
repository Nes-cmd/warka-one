<?php

namespace App\Filament\Resources\SmsMessageResource\Pages;

use App\Filament\Resources\SmsMessageResource;
use App\Models\SmsMessage;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;

class ViewSmsMessage extends ViewRecord
{
    protected static string $resource = SmsMessageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Ensure the smsable relationship is loaded
        $this->record->load('smsable');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('SMS Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('ID')
                            ->copyable(),
                            
                        Infolists\Components\TextEntry::make('smsable_type')
                            ->label('Recipient Type')
                            ->formatStateUsing(fn (string $state): string => class_basename($state))
                            ->badge()
                            ->color('info'),
                            
                        Infolists\Components\TextEntry::make('smsable.name')
                            ->label('Recipient Name')
                            ->placeholder('Unknown')
                            ->formatStateUsing(function ($state, $record) {
                                if ($record->smsable && method_exists($record->smsable, 'name')) {
                                    return $record->smsable->name ?? 'Unknown';
                                }
                                return 'Unknown';
                            }),
                            
                        Infolists\Components\TextEntry::make('phone_number')
                            ->label('Phone Number')
                            ->copyable()
                            ->color('info'),
                            
                        Infolists\Components\TextEntry::make('message')
                            ->label('Message Content')
                            ->columnSpanFull()
                            ->copyable()
                            ->formatStateUsing(function ($state) {
                                if (is_string($state)) {
                                    return $state;
                                }
                                if (is_array($state) || is_object($state)) {
                                    return json_encode($state, JSON_PRETTY_PRINT);
                                }
                                return (string) $state;
                            }),
                            
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                SmsMessage::STATUS_PENDING => 'warning',
                                SmsMessage::STATUS_SENT => 'info',
                                SmsMessage::STATUS_DELIVERED => 'success',
                                SmsMessage::STATUS_FAILED => 'danger',
                                default => 'gray',
                            }),
                            
                        Infolists\Components\TextEntry::make('provider')
                            ->label('Provider')
                            ->badge()
                            ->color('secondary'),
                            
                        Infolists\Components\TextEntry::make('campaign')
                            ->label('Campaign')
                            ->placeholder('No campaign')
                            ->badge()
                            ->color('gray'),
                            
                        Infolists\Components\TextEntry::make('message_id')
                            ->label('Provider Message ID')
                            ->copyable()
                            ->placeholder('Not available'),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('M d, Y H:i:s')
                            ->copyable(),
                            
                        Infolists\Components\TextEntry::make('sent_at')
                            ->label('Sent At')
                            ->dateTime('M d, Y H:i:s')
                            ->placeholder('Not sent yet')
                            ->copyable(),
                            
                        Infolists\Components\TextEntry::make('delivered_at')
                            ->label('Delivered At')
                            ->dateTime('M d, Y H:i:s')
                            ->placeholder('Not delivered yet')
                            ->copyable(),
                            
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('M d, Y H:i:s')
                            ->copyable(),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Error Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('error_message')
                            ->label('Error Message')
                            ->placeholder('No errors')
                            ->color('danger')
                            ->columnSpanFull()
                            ->formatStateUsing(function ($state) {
                                if (is_string($state)) {
                                    return $state;
                                }
                                if (is_array($state) || is_object($state)) {
                                    return json_encode($state, JSON_PRETTY_PRINT);
                                }
                                return (string) $state;
                            }),
                    ])
                    ->visible(fn (SmsMessage $record): bool => !is_null($record->error_message)),
                    
                Infolists\Components\Section::make('Provider Response')
                    ->schema([
                        Infolists\Components\TextEntry::make('response')
                            ->label('Response Data')
                            ->formatStateUsing(function ($record) {
                                if (is_array($record->response_data)) {
                                    return json_encode($record->response_data, JSON_PRETTY_PRINT);
                                }
                                if (is_object($record->response_data)) {
                                    return json_encode($record->response_data, JSON_PRETTY_PRINT);
                                }
                                if (is_string($record->response_data)) {
                                    return $record->response_data;
                                }
                                return 'No response data';
                            })
                            ->columnSpanFull()
                            ->copyable(),
                    ])
                    ->visible(fn (SmsMessage $record): bool => !is_null($record->response_data)),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('retry')
                ->label('Retry SMS')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (SmsMessage $record): bool => $record->status === SmsMessage::STATUS_PENDING || $record->status === SmsMessage::STATUS_FAILED)
                ->requiresConfirmation()
                ->modalHeading('Retry SMS')
                ->modalDescription('Are you sure you want to retry sending this SMS?')
                ->action(function (SmsMessage $record): void {
                    // For retry, we need to get the smsable model
                    $smsable = $record->smsable;
                    
                    if (!$smsable) {
                        \Filament\Notifications\Notification::make()
                            ->title('SMS Retry Failed')
                            ->body('Cannot retry SMS: Associated model not found.')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Use service method for retry
                    $newSmsMessage = \App\Helpers\SmsSend::sendAndCreateRecord(
                        $record->phone_number,
                        $record->message,
                        $smsable,
                        $record->campaign
                    );
                    
                    if ($newSmsMessage->status === SmsMessage::STATUS_PENDING) {
                        \Filament\Notifications\Notification::make()
                            ->title('SMS Retry Successful')
                            ->body("SMS retry submitted to provider for {$record->phone_number}. Status will be updated via callbacks.")
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('SMS Retry Failed')
                            ->body('Failed to retry SMS. Please check your configuration.')
                            ->danger()
                            ->send();
                    }
                }),
                
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
