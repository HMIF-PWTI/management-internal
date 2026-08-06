<?php

namespace App\Filament\Resources\Fiturs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FiturForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),

                TextInput::make('resource')
                    ->required(),

                Select::make('divisis')
                    ->relationship('divisis', 'nama')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }
}