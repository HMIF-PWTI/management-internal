<?php

namespace App\Filament\Pages\Concerns;

use Illuminate\Support\Facades\Auth;

trait HasHmifFormsNavigation
{
    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'HMIF Forms';
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->canAccessFitur('HMIF Forms') ?? false;
    }
}
