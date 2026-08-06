<?php

namespace App\Filament\Resources\IfPedias\Pages;

use App\Filament\Resources\IfPedias\IfPediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIfPedia extends EditRecord
{
    protected static string $resource = IfPediaResource::class;

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
