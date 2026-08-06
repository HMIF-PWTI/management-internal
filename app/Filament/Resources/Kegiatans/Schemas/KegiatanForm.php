<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class KegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               FileUpload::make('gambar')
                ->label('Gambar Kegiatan')
                ->image()
                ->disk('public')
                ->directory('kegiatan')
                ->visibility('public')
                ->nullable(),

                TextInput::make('nama')
                    ->required(),

                DatePicker::make('tanggal')
                    ->required(),

                Textarea::make('deskripsi')
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('lokasi'),

               Toggle::make('is_absen_active')
                    ->label('Aktif untuk Absensi')
                    ->default(false),

                Toggle::make('is_web_active')
                    ->label('Tampilkan di Website')
                    ->default(true),
            ]);
    }
}