<?php

namespace App\Filament\Resources\HmifPedias;

use App\Filament\Resources\HmifPedias\Pages\CreateHmifPedia;
use App\Filament\Resources\HmifPedias\Pages\EditHmifPedia;
use App\Filament\Resources\HmifPedias\Pages\ListHmifPedias;
use App\Filament\Resources\HmifPedias\Schemas\HmifPediaForm;
use App\Filament\Resources\HmifPedias\Tables\HmifPediasTable;
use App\Models\HmifPedia;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HmifPediaResource extends Resource
{
    protected static ?string $model = HmifPedia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'HMIF-PEDIA';

    protected static ?string $pluralModelLabel = 'HMIF-PEDIA';

    protected static ?string $modelLabel = 'HMIF-PEDIA';

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return HmifPediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HmifPediasTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return static::canAccessHmifPedia();
    }

    public static function canView(Model $record): bool
    {
        return static::canAccessHmifPedia();
    }

    public static function canCreate(): bool
    {
        return static::canAccessHmifPedia();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canAccessHmifPedia();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canAccessHmifPedia();
    }

    public static function canDeleteAny(): bool
    {
        return static::canAccessHmifPedia();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHmifPedias::route('/'),
            'create' => CreateHmifPedia::route('/create'),
            'edit' => EditHmifPedia::route('/{record}/edit'),
        ];
    }

    private static function canAccessHmifPedia(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $user->loadMissing('divisi');
        $singkatan = strtolower(trim((string) $user->divisi?->singkatan));

        return in_array($singkatan, ['pao', 'superadmin'], true);
    }
}
