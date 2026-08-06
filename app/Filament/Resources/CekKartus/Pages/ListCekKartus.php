<?php

namespace App\Filament\Resources\CekKartus\Pages;

use App\Filament\Resources\CekKartus\CekKartuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCekKartus extends ListRecords
{
    protected static string $resource = CekKartuResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
