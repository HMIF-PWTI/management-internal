<?php

namespace App\Filament\Resources\InfoKps\Pages;

use App\Filament\Resources\InfoKps\InfoKpResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInfoKp extends EditRecord
{
    protected static string $resource = InfoKpResource::class;

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