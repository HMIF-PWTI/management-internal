<?php

namespace App\Filament\Resources\Absensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AbsensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cek_kartu_id')
                    ->required()
                    ->numeric(),
                TextInput::make('rfid_card_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kegiatan_id')
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('jam_masuk'),
                TimePicker::make('jam_keluar'),
                Select::make('status')
                    ->options(['hadir' => 'Hadir', 'pulang' => 'Pulang'])
                    ->default('hadir')
                    ->required(),
            ]);
    }
}
