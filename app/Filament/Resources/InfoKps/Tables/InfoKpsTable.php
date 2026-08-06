<?php

namespace App\Filament\Resources\InfoKps\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InfoKpsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->searchable(),

                TextColumn::make('kota')
                    ->label('Kota')
                    ->searchable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50),

                IconColumn::make('is_active')
                    ->label('Is active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}