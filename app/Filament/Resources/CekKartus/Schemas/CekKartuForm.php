<?php

namespace App\Filament\Resources\CekKartus\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CekKartuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uid')
                    ->required(),
                TextInput::make('rfid_card_id')
                    ->numeric(),
                Select::make('status')
                    ->options(['unknown' => 'Unknown', 'inactive' => 'Inactive', 'valid' => 'Valid'])
                    ->default('unknown')
                    ->required(),
                DateTimePicker::make('scanned_at')
                    ->required(),
            ]);
    }
}
