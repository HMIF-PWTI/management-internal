<?php

namespace App\Filament\Resources\IfPedias\Pages;

use App\Filament\Resources\IfPedias\IfPediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIfPedias extends ListRecords
{
    protected static string $resource = IfPediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
