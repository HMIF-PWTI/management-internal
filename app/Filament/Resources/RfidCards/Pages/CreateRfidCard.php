<?php

namespace App\Filament\Resources\RfidCards\Pages;

use App\Filament\Resources\RfidCards\RfidCardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRfidCard extends CreateRecord
{
    protected static string $resource = RfidCardResource::class;
        protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }
}
