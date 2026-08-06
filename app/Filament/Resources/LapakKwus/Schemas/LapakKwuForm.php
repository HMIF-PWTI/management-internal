<?php

namespace App\Filament\Resources\LapakKwus\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class LapakKwuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('gambar')
                    ->label('Gambar Produk')
                    ->image()
                    ->disk('public')
                    ->directory('lapak-kwu')
                    ->visibility('public')
                    ->required(),

                TextInput::make('nama_produk')
                    ->required(),

                TextInput::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Textarea::make('deskripsi')
                    ->columnSpanFull(),

                TextInput::make('link')
    ->url()
    ->required(),

                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}