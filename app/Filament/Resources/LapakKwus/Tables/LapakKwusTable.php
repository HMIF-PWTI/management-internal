<?php

namespace App\Filament\Resources\LapakKwus\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class LapakKwusTable
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


                TextColumn::make('nama_produk')
                    ->searchable(),

                TextColumn::make('harga')
                    ->money('IDR'),

                TextColumn::make('link')
                    ->label('link')
                    ->limit(30),

                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}