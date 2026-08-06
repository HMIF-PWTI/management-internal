<?php

namespace App\Filament\Resources\RfidCards\Pages;

use App\Filament\Resources\RfidCards\RfidCardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRfidCard extends EditRecord
{
    protected static string $resource = RfidCardResource::class;

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
