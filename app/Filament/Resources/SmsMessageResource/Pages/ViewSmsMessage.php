<?php

namespace App\Filament\Resources\SmsMessageResource\Pages;

use App\Filament\Resources\SmsMessageResource;
use App\Models\SmsMessage;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSmsMessage extends ViewRecord
{
    protected static string $resource = SmsMessageResource::class;

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
                            ->placeholder('Unknown'),
                            
                        Infolists\Components\TextEntry::make('phone_number')
                            ->label('Phone Number')
                            ->copyable()
                            ->color('info'),
                            
                        Infolists\Components\TextEntry::make('message')
                            ->label('Message Content')
                            ->columnSpanFull()
                            ->copyable()
                            ->formatStateUsing(fn (string $state): string => $state),
                            
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
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (SmsMessage $record): bool => !is_null($record->error_message)),
                    
                Infolists\Components\Section::make('Provider Response')
                    ->schema([
                        Infolists\Components\TextEntry::make('response_data')
                            ->label('Response Data')
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return json_encode($state, JSON_PRETTY_PRINT);
                                }
                                return $state ?? 'No response data';
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
                    try {
                        $response = \App\Helpers\SmsSend::sendThroughAfro($record->phone_number, $record->message);
                        
                        if ($response->successful()) {
                            $record->update([
                                'status' => SmsMessage::STATUS_PENDING, // Keep as pending, let callback handle status
                                'message_id' => $response->json('message_id') ?? null,
                                'response_data' => $response->json() ?? null,
                                'sent_at' => now(),
                                'error_message' => null,
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('SMS Retry Successful')
                                ->body("SMS retry submitted to provider for {$record->phone_number}. Status will be updated via callbacks.")
                                ->success()
                                ->send();
                        } else {
                            $record->update([
                                'status' => SmsMessage::STATUS_FAILED,
                                'error_message' => 'Retry failed: API returned unsuccessful response',
                                'response_data' => $response->json() ?? null,
                            ]);
                            
                            \Filament\Notifications\Notification::make()
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
                        
                        \Filament\Notifications\Notification::make()
                            ->title('SMS Retry Error')
                            ->body('An error occurred while retrying SMS: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
                
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
