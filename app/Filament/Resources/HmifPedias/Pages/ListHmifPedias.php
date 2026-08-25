<?php

namespace App\Filament\Resources\HmifPedias\Pages;

use App\Filament\Resources\HmifPedias\HmifPediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHmifPedias extends ListRecords
{
    protected static string $resource = HmifPediaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
