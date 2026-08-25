<?php

namespace App\Filament\Resources\HmifPedias\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class HmifPediasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->searchable()->sortable(),
                TextColumn::make('file_pdf')->label('File PDF'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
                TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('viewPdf')
                    ->label('Lihat PDF')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => Storage::disk('public')->url($record->file_pdf))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
