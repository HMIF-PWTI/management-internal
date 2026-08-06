<?php

namespace App\Filament\Resources\CekKartus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CekKartusTable
{
   public static function configure(Table $table): Table
{
    return $table
        ->poll('3s')
        ->columns([
            TextColumn::make('uid')
                ->searchable(),

           TextColumn::make('rfidCard.nama')
                ->label('Nama Pemilik Kartu')
                ->searchable()
                ->sortable(),

            TextColumn::make('status')
                ->badge()
                ->colors([
                    'danger' => 'unknown',
                    'warning' => 'inactive',
                    'success' => 'valid',
                ]),

            TextColumn::make('scanned_at')
                ->label('Waktu Scan')
                ->dateTime('d/m/Y H:i:s')
                ->sortable(),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->recordActions([])
        ->toolbarActions([]);
}
}
