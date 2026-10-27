<?php

namespace App\Filament\Pages;

use App\Models\QrCode;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class QrCodeGenerator extends Page
{
    protected static ?string $navigationLabel = 'QR Code Generator';

    protected static bool $shouldRegisterNavigation = false;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static string|\UnitEnum|null $navigationGroup = 'Tools';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'qr-code-generator';

    protected static ?string $title = 'QR Code Generator';

    protected string $view = 'filament.pages.qr-code-generator';

    public static function canAccess(): bool
    {
        // Disembunyikan sementara sampai ditentukan divisi mana yang boleh mengakses
        return false;
    }

    public function getViewData(): array
    {
        return [
            'latestQrCode' => QrCode::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->first(),
        ];
    }
}
