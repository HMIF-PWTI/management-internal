<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKegiatan extends EditRecord
{
    protected static string $resource = KegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
