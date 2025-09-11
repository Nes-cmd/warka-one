<?php

namespace App\Filament\Resources\SmsMessageResource\Pages;

use App\Filament\Resources\SmsMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmsMessage extends EditRecord
{
    protected static string $resource = SmsMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
