<?php

namespace App\Filament\Resources\QrCodes;

use App\Filament\Resources\QrCodes\Pages\ListQrCodes;
use App\Filament\Resources\QrCodes\Tables\QrCodesTable;
use App\Models\QrCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QrCodeResource extends Resource
{
    protected static ?string $model = QrCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static string|\UnitEnum|null $navigationGroup = 'Tools';

    protected static ?string $navigationLabel = 'QR Codes';

    protected static ?int $navigationSort = 19;

    public static function canAccess(): bool
    {
        return Auth::user()?->canAccessFitur('QR Code') ?? false;
    }

    public static function table(Table $table): Table
    {
        return QrCodesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQrCodes::route('/'),
        ];
    }
}
