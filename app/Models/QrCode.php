<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class QrCode extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_token',
        'link',
        'logo_path',
        'qr_color',
        'background_color',
        'frame',
        'shape',
        'scan_tracking',
        'total_scans',
        'unique_scans',
        'top_location',
        'qr_image_url',
    ];

    protected $casts = [
        'scan_tracking' => 'boolean',
        'total_scans' => 'integer',
        'unique_scans' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function trackingBaseUrl(): string
    {
        return rtrim(config('services.qr.public_url'), '/');
    }

    protected static function booted(): void
    {
        static::updated(function (QrCode $qrCode) {
            $oldLogoPath = $qrCode->getOriginal('logo_path');

            if ($oldLogoPath && $oldLogoPath !== $qrCode->logo_path) {
                Storage::disk('public')->delete($oldLogoPath);
            }

            if ($qrCode->getOriginal('qr_image_url') !== $qrCode->qr_image_url) {
                static::deleteStoredImage($qrCode->getOriginal('qr_image_url'));
            }
        });

        static::deleted(function (QrCode $qrCode) {
            if ($qrCode->logo_path) {
                Storage::disk('public')->delete($qrCode->logo_path);
            }

            static::deleteStoredImage($qrCode->qr_image_url);
        });
    }

    private static function deleteStoredImage(?string $url): void
    {
        if ($url && str_contains($url, '/storage/qr-codes/')) {
            Storage::disk('public')->delete('qr-codes/' . basename($url));
        }
    }
}
