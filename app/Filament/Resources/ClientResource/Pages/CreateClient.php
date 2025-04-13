<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

     protected function handleRecordCreation(array $data): Model {
        $data['personal_access_client'] = false;
        $data['password_client'] = false;
        $data['revoked'] = false;
        $data['secret'] = \Str::random(40);
        $record = $this->getModel()::create($data);
        return $record;
     }
}
