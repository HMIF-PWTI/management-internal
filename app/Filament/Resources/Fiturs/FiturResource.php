<?php

namespace App\Filament\Resources\Fiturs;

use App\Filament\Resources\Fiturs\Pages\CreateFitur;
use App\Filament\Resources\Fiturs\Pages\EditFitur;
use App\Filament\Resources\Fiturs\Pages\ListFiturs;
use App\Filament\Resources\Fiturs\Schemas\FiturForm;
use App\Filament\Resources\Fiturs\Tables\FitursTable;
use App\Models\Fitur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
class FiturResource extends Resource
{
    protected static ?string $model = Fitur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'Fitur';

    public static function form(Schema $schema): Schema
    {
        return FiturForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FitursTable::configure($table);
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
            'index' => ListFiturs::route('/'),
            'create' => CreateFitur::route('/create'),
            'edit' => EditFitur::route('/{record}/edit'),
        ];
    }

  public static function canAccess(): bool
{
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    return $user?->canAccessFitur('fiturs') ?? false;
}
}
