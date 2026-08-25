<?php

namespace App\Filament\Resources\HmifPedias\Pages;

use App\Filament\Resources\HmifPedias\HmifPediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHmifPedia extends EditRecord
{
    protected static string $resource = HmifPediaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
