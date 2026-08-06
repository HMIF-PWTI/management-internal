<?php

namespace App\Filament\Resources\IfPedias\Pages;

use App\Filament\Resources\IfPedias\IfPediaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIfPedia extends CreateRecord
{
    protected static string $resource = IfPediaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
