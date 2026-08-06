<?php

namespace App\Filament\Resources\LapakKwus;

use App\Filament\Resources\LapakKwus\Pages\CreateLapakKwu;
use App\Filament\Resources\LapakKwus\Pages\EditLapakKwu;
use App\Filament\Resources\LapakKwus\Pages\ListLapakKwus;
use App\Filament\Resources\LapakKwus\Schemas\LapakKwuForm;
use App\Filament\Resources\LapakKwus\Tables\LapakKwusTable;
use App\Models\LapakKwu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LapakKwuResource extends Resource
{
    protected static ?string $model = LapakKwu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static ?string $recordTitleAttribute = 'nama_produk';
    protected static ?string $navigationLabel = 'lapak kwu';
    protected static ?string $pluralModelLabel = 'lapak kwu';
    public static function form(Schema $schema): Schema
    {
        return LapakKwuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LapakKwusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLapakKwus::route('/'),
            'create' => CreateLapakKwu::route('/create'),
            'edit' => EditLapakKwu::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->canAccessFitur('lapak kwu') ?? false;
    }
}
