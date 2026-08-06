<?php

namespace App\Filament\Resources\InfoKps\Pages;

use App\Filament\Resources\InfoKps\InfoKpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfoKps extends ListRecords
{
    protected static string $resource = InfoKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}