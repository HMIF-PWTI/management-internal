<?php

namespace App\Filament\Resources\LapakKwus\Pages;

use App\Filament\Resources\LapakKwus\LapakKwuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLapakKwu extends EditRecord
{
    protected static string $resource = LapakKwuResource::class;

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
