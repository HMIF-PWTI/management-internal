<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrCode extends Model
{
    protected $fillable = [
        'user_id',
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

    public function getTrackingUrl(): string
    {
        if (!$this->scan_tracking) {
            return $this->link;
        }

        $appUrl = config('app.url', 'http://127.0.0.1:8000');
        $parsed = parse_url($appUrl);
        $host = $parsed['host'] ?? '127.0.0.1';

        // When in local development, use LAN IP 192.168.1.20 so mobile phones on Wi-Fi can scan & track seamlessly
        if (in_array($host, ['localhost', '127.0.0.1', '::1', '0.0.0.0'])) {
            $scheme = $parsed['scheme'] ?? 'http';
            $port = isset($parsed['port']) ? ':' . $parsed['port'] : ':8000';
            return "{$scheme}://192.168.1.20{$port}/qr/{$this->id}";
        }

        return url('/qr/' . $this->id);
    }

    public function refreshQrImage(): string
    {
        $targetData = $this->scan_tracking ? $this->getTrackingUrl() : $this->link;
        $this->qr_image_url = static::generateQrWithLogo(
            $targetData,
            $this->qr_color,
            $this->background_color,
            $this->logo_path,
        );
        $this->saveQuietly();
        return $this->qr_image_url;
    }

    protected static function booted(): void
    {
        static::saving(function (QrCode $qrCode) {
            $qrCode->qr_color = strtolower($qrCode->qr_color ?: '#0f0606');
            $qrCode->background_color = strtolower($qrCode->background_color ?: '#ffffff');

            $targetData = ($qrCode->scan_tracking && $qrCode->id)
                ? $qrCode->getTrackingUrl()
                : $qrCode->link;

            if (empty($qrCode->qr_image_url) || ($qrCode->logo_path && str_contains($qrCode->qr_image_url, 'api.qrserver.com'))) {
                $qrCode->qr_image_url = static::generateQrWithLogo(
                    $targetData,
                    $qrCode->qr_color,
                    $qrCode->background_color,
                    $qrCode->logo_path,
                );
            }
        });

        static::saved(function (QrCode $qrCode) {
            if ($qrCode->scan_tracking && !cache()->has("qr_tracking_synced_{$qrCode->id}")) {
                cache()->put("qr_tracking_synced_{$qrCode->id}", true, now()->addDays(30));
                $qrCode->refreshQrImage();
            }
        });

        static::updated(function (QrCode $qrCode) {
            $oldLogoPath = $qrCode->getOriginal('logo_path');

            if ($oldLogoPath && $oldLogoPath !== $qrCode->logo_path) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        });

        static::deleted(function (QrCode $qrCode) {
            if ($qrCode->logo_path) {
                Storage::disk('public')->delete($qrCode->logo_path);
            }

            if ($qrCode->qr_image_url && str_contains($qrCode->qr_image_url, '/storage/qr-codes/')) {
                $storedPath = 'qr-codes/' . basename($qrCode->qr_image_url);
                Storage::disk('public')->delete($storedPath);
            }
        });
    }

    public static function buildImageUrl(string $link, string $qrColor, string $backgroundColor, string $format = 'jpg', string $ecc = 'M'): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?'.http_build_query([
            'size' => '500x500',
            'format' => $format,
            'color' => strtoupper(ltrim($qrColor, '#')),
            'bgcolor' => strtoupper(ltrim($backgroundColor, '#')),
            'ecc' => $ecc,
            'data' => $link,
        ]);
    }

    public static function generateQrWithLogo(
        string $link,
        string $qrColor,
        string $backgroundColor,
        ?string $logoPath = null
    ): string {
        $qrUrl = static::buildImageUrl($link, $qrColor, $backgroundColor, 'png', 'H');

        if (!$logoPath || !Storage::disk('public')->exists($logoPath)) {
            return $qrUrl;
        }

        try {
            $context = stream_context_create([
                'http' => ['timeout' => 8],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);
            $qrContent = @file_get_contents($qrUrl, false, $context);
            if (!$qrContent) {
                return $qrUrl;
            }

            $logoFullPath = Storage::disk('public')->path($logoPath);
            $logoContent = @file_get_contents($logoFullPath);
            if (!$logoContent) {
                return $qrUrl;
            }

            $qrImg = @imagecreatefromstring($qrContent);
            $logoImg = @imagecreatefromstring($logoContent);

            if (!$qrImg || !$logoImg) {
                if ($qrImg) imagedestroy($qrImg);
                if ($logoImg) imagedestroy($logoImg);
                return $qrUrl;
            }

            $qrW = imagesx($qrImg);
            $qrH = imagesy($qrImg);
            $logoW = imagesx($logoImg);
            $logoH = imagesy($logoImg);

            // Size of logo container: 24% of QR code width
            $boxSize = (int)($qrW * 0.24);
            $padding = 6;
            $innerSize = max(10, $boxSize - ($padding * 2));

            $scale = min($innerSize / max(1, $logoW), $innerSize / max(1, $logoH));
            $finalLogoW = (int)($logoW * $scale);
            $finalLogoH = (int)($logoH * $scale);

            $boxX = (int)(($qrW - $boxSize) / 2);
            $boxY = (int)(($qrH - $boxSize) / 2);

            // Draw white background card for the logo
            $white = imagecolorallocate($qrImg, 255, 255, 255);
            $borderColor = imagecolorallocate($qrImg, 226, 232, 240);

            imagefilledrectangle($qrImg, $boxX, $boxY, $boxX + $boxSize, $boxY + $boxSize, $white);
            imagerectangle($qrImg, $boxX, $boxY, $boxX + $boxSize, $boxY + $boxSize, $borderColor);

            $logoDestX = (int)($boxX + ($boxSize - $finalLogoW) / 2);
            $logoDestY = (int)($boxY + ($boxSize - $finalLogoH) / 2);

            imagealphablending($qrImg, true);
            imagesavealpha($qrImg, true);

            imagecopyresampled(
                $qrImg,
                $logoImg,
                $logoDestX,
                $logoDestY,
                0,
                0,
                $finalLogoW,
                $finalLogoH,
                $logoW,
                $logoH
            );

            ob_start();
            imagepng($qrImg);
            $pngData = ob_get_clean();

            imagedestroy($qrImg);
            imagedestroy($logoImg);

            $filename = 'qr-codes/' . Str::random(40) . '.png';

            /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
            $storage = Storage::disk('public');
            $storage->put($filename, $pngData);

            return $storage->url($filename);
        } catch (\Throwable $e) {
            report($e);
            return $qrUrl;
        }
    }
}
