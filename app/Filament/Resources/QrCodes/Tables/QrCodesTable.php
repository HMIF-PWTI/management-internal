<?php

namespace App\Filament\Resources\QrCodes\Tables;

use App\Filament\Pages\QrCodeGenerator;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QrCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('qr_image_url')
                    ->label('QR Code')
                    ->square()
                    ->size(72)
                    ->url(fn ($record): string => $record->qr_image_url)
                    ->openUrlInNewTab(),

                TextColumn::make('link')
                    ->label('Link')
                    ->searchable()
                    ->limit(48),

                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->square()
                    ->size(48),

                TextColumn::make('qr_color')
                    ->label('QR Color')
                    ->badge(),

                TextColumn::make('background_color')
                    ->label('Background')
                    ->badge(),

                \Filament\Tables\Columns\IconColumn::make('scan_tracking')
                    ->label('Tracking')
                    ->boolean(),

                TextColumn::make('total_scans')
                    ->label('Total Scans')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('unique_scans')
                    ->label('Unique Scans')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                TextColumn::make('top_location')
                    ->label('Top Location')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn ($record): string => QrCodeGenerator::getUrl(['qr' => $record->id])),
                DeleteAction::make(),
            ]);
    }
}
