<?php

namespace App\Filament\Pages;

use App\Models\QrCode;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        return Auth::user()?->canAccessFitur('QR Code') ?? false;
    }

    public function getViewData(): array
    {
        $editing = request()->filled('qr')
            ? QrCode::where('user_id', Auth::id())->findOrFail(request()->integer('qr'))
            : null;

        return [
            'editing' => $editing,
            'latestQrCode' => $editing ?? QrCode::where('user_id', Auth::id())->latest()->first(),
            // Known before saving so the preview, the download and the stored image encode the same URL.
            'trackingToken' => old('tracking_token', $editing?->tracking_token ?? (string) Str::ulid()),
        ];
    }
}
