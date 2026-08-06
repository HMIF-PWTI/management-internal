<?php

namespace App\Filament\Resources\RfidCards\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RfidCardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
               TextColumn::make('uid')
                    ->label('UID')
                    ->searchable(),

                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan'),

                TextColumn::make('divisi.nama')
                    ->label('Divisi')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('divisi_id')
                    ->label('Filter Divisi')
                    ->relationship('divisi', 'nama')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Filter Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->placeholder('Semua Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
