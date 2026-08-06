<?php

namespace App\Filament\Resources\InfoKps;

use App\Filament\Resources\InfoKps\Pages\CreateInfoKp;
use App\Filament\Resources\InfoKps\Pages\EditInfoKp;
use App\Filament\Resources\InfoKps\Pages\ListInfoKps;
use App\Filament\Resources\InfoKps\Schemas\InfoKpForm;
use App\Filament\Resources\InfoKps\Tables\InfoKpsTable;
use App\Models\InfoKp;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class InfoKpResource extends Resource
{
    protected static ?string $model = InfoKp::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedBuildingOffice;

    protected static ?string $navigationLabel = 'Info KP';

    protected static ?string $pluralModelLabel = 'Info KP';

    protected static ?string $modelLabel = 'Info KP';

    protected static ?string $recordTitleAttribute = 'nama_perusahaan';

    public static function form(Schema $schema): Schema
    {
        return InfoKpForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InfoKpsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->canAccessFitur('Info KP') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInfoKps::route('/'),
            'create' => CreateInfoKp::route('/create'),
            'edit' => EditInfoKp::route('/{record}/edit'),
        ];
    }

        public static function canAccess(): bool
{
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    return $user?->canAccessFitur('InfoKp') ?? false;
}
}