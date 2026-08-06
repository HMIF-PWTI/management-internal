<?php

namespace App\Filament\Resources\Abouts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class AboutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                ImageColumn::make('foto'),

                TextColumn::make('nama')
                    ->searchable(),

                TextColumn::make('jabatan'),

                TextColumn::make('divisi.nama')
                    ->label('Divisi'),

                IconColumn::make('is_inti')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}