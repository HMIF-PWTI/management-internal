<?php

namespace App\Filament\Resources\LkmmPendaftarans;

use App\Filament\Resources\LkmmPendaftarans\Pages\ListLkmmPendaftarans;
use App\Filament\Resources\LkmmPendaftarans\Pages\ViewLkmmPendaftaran;
use App\Filament\Resources\LkmmPendaftarans\Tables\LkmmPendaftaransTable;
use App\Models\LkmmPendaftaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LkmmPendaftaranResource extends Resource
{
    protected static ?string $model = LkmmPendaftaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'LKMM Pendaftaran';

    protected static ?string $pluralModelLabel = 'Pendaftar LKMM';

    protected static ?string $modelLabel = 'Pendaftar LKMM';

    protected static ?string $navigationGroup = 'LKMM';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return LkmmPendaftaransTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLkmmPendaftarans::route('/'),
            'view'  => ViewLkmmPendaftaran::route('/{record}'),
        ];
    }
}

