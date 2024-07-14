<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Country;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Select::make('country_id')
                        ->label(__('Country'))
                        ->options(Country::pluck('name', 'id'))
                        ->default(1),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(9)
                        ->default(null),
                    // Forms\Components\DateTimePicker::make('email_verified_at'),
                    // Forms\Components\DateTimePicker::make('phone_verified_at'),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->maxLength(255),

                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime('M y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_verified_at')
                    ->dateTime('M y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('country_id')
                    ->numeric()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
