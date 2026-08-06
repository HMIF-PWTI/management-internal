<?php

namespace App\Filament\Resources\LapakKwus\Pages;

use App\Filament\Resources\LapakKwus\LapakKwuResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLapakKwu extends CreateRecord
{
    protected static string $resource = LapakKwuResource::class;

       protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
 

}
