<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Passport\Client as PassportClient;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ClientResource extends Resource
{
    protected static ?string $model = PassportClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('redirect')->required()->url(),
                    Forms\Components\Select::make('use_auth_types')->multiple()->options(['phone' => 'Phone', 'email' => 'Email'])->required(),
                    Forms\Components\TextInput::make('secret')->required()->suffixAction(
                        Action::make('generate')->icon('heroicon-o-arrow-path-rounded-square')->action(fn(callable $set) => $set('secret', Str::random(40)))
                        )->afterStateHydrated(fn(callable $set) => $set('secret', Str::random(40))),
                    Forms\Components\Toggle::make('registration_enabled'),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('use_auth_types')->badge(),
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
