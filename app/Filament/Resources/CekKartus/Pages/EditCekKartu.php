<?php

namespace App\Filament\Resources\CekKartus\Pages;

use App\Filament\Resources\CekKartus\CekKartuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCekKartu extends EditRecord
{
    protected static string $resource = CekKartuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
