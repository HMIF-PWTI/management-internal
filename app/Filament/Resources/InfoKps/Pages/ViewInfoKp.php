<?php

namespace App\Filament\Resources\InfoKps\Pages;

use App\Filament\Resources\InfoKps\InfoKpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInfoKp extends ViewRecord
{
    protected static string $resource = InfoKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
