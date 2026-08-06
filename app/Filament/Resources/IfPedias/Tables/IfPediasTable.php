<?php

namespace App\Filament\Resources\IfPedias\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class IfPediasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                TextColumn::make('judul')
                    ->searchable(),

                TextColumn::make('deskripsi')
                    ->limit(40),

                TextColumn::make('file_pdf')
                    ->label('PDF'),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}