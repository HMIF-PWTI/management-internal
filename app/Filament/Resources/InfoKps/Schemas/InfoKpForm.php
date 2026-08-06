<?php

namespace App\Filament\Resources\InfoKps\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class InfoKpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_perusahaan')
                ->label('Nama Perusahaan')
                ->required(),

            TextInput::make('kota')
                ->label('Kota')
                ->required(),

            Textarea::make('alamat')
                ->label('Alamat')
                ->required()
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Is active')
                ->default(true),
        ]);
    }
}