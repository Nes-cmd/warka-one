<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($data['password']){
            $record->password = Hash::make($data['password']);
        }
        if($record->phone != $data['phone']){
            $record->phone = $data['phone'];
            $record->phone_verified_at = null;
        }
        if($record->email != $data['email']){
            $record->email = $data['phone'];
            $record->email_verified_at = null;
        }

        $record->name = $data['name'];
        $record->country_id = $data['country_id'];

        $record->email_verified_at = $data['email_verified_at'];
        $record->phone_verified_at = $data['phone_verified_at'];
        
        $record->save();

        return $record;
        
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
