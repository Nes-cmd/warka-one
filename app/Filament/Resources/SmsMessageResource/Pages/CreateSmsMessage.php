<?php

namespace App\Filament\Resources\SmsMessageResource\Pages;

use App\Filament\Resources\SmsMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSmsMessage extends CreateRecord
{
    protected static string $resource = SmsMessageResource::class;
}
