<?php

namespace App\Filament\Resources\Fiturs\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FitursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Fitur')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('resource')
                    ->label('Resource')
                    ->badge()
                    ->searchable(),

                TextColumn::make('divisis.nama')
                    ->label('Divisi Aktif')
                    ->badge()
                    ->separator(',')
                    ->placeholder('Belum ada divisi'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}