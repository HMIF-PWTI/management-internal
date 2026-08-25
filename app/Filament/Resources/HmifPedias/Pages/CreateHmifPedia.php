<?php

namespace App\Filament\Resources\HmifPedias\Pages;

use App\Filament\Resources\HmifPedias\HmifPediaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHmifPedia extends CreateRecord
{
    protected static string $resource = HmifPediaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
