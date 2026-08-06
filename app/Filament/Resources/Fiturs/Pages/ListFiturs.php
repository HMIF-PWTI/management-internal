<?php

namespace App\Filament\Resources\Fiturs\Pages;

use App\Filament\Resources\Fiturs\FiturResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFiturs extends ListRecords
{
    protected static string $resource = FiturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
