<?php

namespace App\Filament\Resources\Fiturs\Pages;

use App\Filament\Resources\Fiturs\FiturResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFitur extends EditRecord
{
    protected static string $resource = FiturResource::class;

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
