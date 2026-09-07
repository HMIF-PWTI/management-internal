<?php

namespace App\Filament\Resources\QrCodes\Pages;

use App\Filament\Resources\QrCodes\QrCodeResource;
use App\Filament\Pages\QrCodeGenerator;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListQrCodes extends ListRecords
{
    protected static string $resource = QrCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate QR Code')
                ->icon('heroicon-o-plus')
                ->url(QrCodeGenerator::getUrl()),
        ];
    }
}
