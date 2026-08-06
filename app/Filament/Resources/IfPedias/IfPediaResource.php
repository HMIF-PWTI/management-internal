<?php

namespace App\Filament\Resources\IfPedias;

use App\Filament\Resources\IfPedias\Pages\CreateIfPedia;
use App\Filament\Resources\IfPedias\Pages\EditIfPedia;
use App\Filament\Resources\IfPedias\Pages\ListIfPedias;
use App\Filament\Resources\IfPedias\Schemas\IfPediaForm;
use App\Filament\Resources\IfPedias\Tables\IfPediasTable;
use App\Models\IfPedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class IfPediaResource extends Resource
{
    protected static ?string $model = IfPedia::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'IF Pedia';
    protected static ?string $pluralModelLabel = 'IF Pedia';
    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return IfPediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IfPediasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Menentukan apakah menu IF Pedia tampil
     * dan halaman daftar dapat dibuka.
     */
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->canAccessFitur('IF Pedia') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIfPedias::route('/'),
            'create' => CreateIfPedia::route('/create'),
            'edit' => EditIfPedia::route('/{record}/edit'),
        ];
    }
}