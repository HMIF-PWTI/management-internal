<?php

namespace App\Filament\Resources\LapakKwus\Pages;

use App\Filament\Resources\LapakKwus\LapakKwuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLapakKwus extends ListRecords
{
    protected static string $resource = LapakKwuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
