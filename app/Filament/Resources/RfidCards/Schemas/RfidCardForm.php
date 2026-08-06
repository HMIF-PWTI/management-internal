<?php

namespace App\Filament\Resources\RfidCards\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class RfidCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uid')
                    ->label('UID')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nim')
                    ->label('NIM')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),

                TextInput::make('jabatan')
                    ->label('Jabatan'),

                Select::make('divisi_id')
                    ->label('Divisi')
                    ->relationship('divisi', 'nama')
                    ->searchable()
                    ->preload(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}