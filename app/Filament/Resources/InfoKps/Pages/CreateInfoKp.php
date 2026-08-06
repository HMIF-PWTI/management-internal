<?php

namespace App\Filament\Resources\InfoKps\Pages;

use App\Filament\Resources\InfoKps\InfoKpResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInfoKp extends CreateRecord
{
    protected static string $resource = InfoKpResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}