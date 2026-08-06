<?php

namespace App\Filament\Resources\Kegiatans\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class KegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                 ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(70)
                    ->width(70)
                    ->square(),

                TextColumn::make('nama')
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->date(),

                TextColumn::make('lokasi'),

                IconColumn::make('is_absen_active')
                    ->label('Absensi')
                    ->boolean(),

                IconColumn::make('is_web_active')
                    ->label('Website')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}