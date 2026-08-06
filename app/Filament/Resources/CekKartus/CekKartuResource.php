<?php

namespace App\Filament\Resources\CekKartus;

use App\Filament\Resources\CekKartus\Pages\CreateCekKartu;
use App\Filament\Resources\CekKartus\Pages\EditCekKartu;
use App\Filament\Resources\CekKartus\Pages\ListCekKartus;
use App\Filament\Resources\CekKartus\Schemas\CekKartuForm;
use App\Filament\Resources\CekKartus\Tables\CekKartusTable;
use App\Models\CekKartu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CekKartuResource extends Resource
{
    protected static ?string $model = CekKartu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $recordTitleAttribute = 'uid';

    public static function form(Schema $schema): Schema
    {
        return CekKartuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CekKartusTable::configure($table);
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
            'index' => ListCekKartus::route('/'),
            // 'create' => CreateCekKartu::route('/create'),
            // 'edit' => EditCekKartu::route('/{record}/edit'),
        ];
    }
     public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->canAccessFitur('Cek_Kartus') ?? false;
    }
}
