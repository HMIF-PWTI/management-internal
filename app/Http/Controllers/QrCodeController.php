<?php

namespace App\Http\Controllers;

use App\Filament\Resources\QrCodes\QrCodeResource;
use App\Models\QrCode;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class QrCodeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->canAccessFitur('QR Code'), 403);

        $existing = $request->filled('qr_code_id')
            ? QrCode::where('user_id', $request->user()->id)->findOrFail($request->integer('qr_code_id'))
            : null;

        $validated = $request->validate([
            'link' => ['required', 'url:http,https', 'max:2048'],
            'tracking_token' => [
                'required',
                'ulid',
                Rule::unique('qr_codes', 'tracking_token')->ignore($existing?->id),
            ],
            // The browser converts every logo to a small PNG, so nothing here can exhaust memory or carry SVG scripts.
            'logo' => ['nullable', 'file', 'mimes:png', 'max:2048', 'dimensions:max_width=1024,max_height=1024'],
            'qr_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'frame' => ['required', Rule::in(['none', 'simple', 'scan'])],
            'shape' => ['required', Rule::in([
                'square',
                'dots',
                'rounded',
                'extra-rounded',
                'classy',
                'classy-rounded',
            ])],
            'scan_tracking' => ['required', 'boolean'],
            'qr_image_data' => ['required', 'string', 'max:6000000', function (string $attribute, string $value, \Closure $fail) {
                if ($this->decodePng($value) === null) {
                    $fail('Gambar QR Code tidak valid. Muat ulang halaman lalu coba lagi.');
                }
            }],
        ], [
            'tracking_token.unique' => 'QR Code ini sudah tersimpan. Muat ulang halaman untuk membuat yang baru.',
            'logo.*' => 'Logo tidak valid. Pilih ulang gambar logo.',
        ]);

        $logoPath = $existing?->logo_path;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('qr-logos', 'public');
        } elseif ($request->boolean('remove_logo')) {
            $logoPath = null;
        }

        $filename = 'qr-codes/' . Str::random(40) . '.png';
        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk('public');
        $storage->put($filename, $this->decodePng($validated['qr_image_data']));

        $attributes = [
            'link' => $validated['link'],
            'logo_path' => $logoPath,
            'qr_color' => strtolower($validated['qr_color']),
            'background_color' => strtolower($validated['background_color']),
            'frame' => $validated['frame'],
            'shape' => $validated['shape'],
            'scan_tracking' => $request->boolean('scan_tracking'),
            'qr_image_url' => $storage->url($filename),
        ];

        if ($existing) {
            $existing->update($attributes);
        } else {
            QrCode::create([
                ...$attributes,
                'user_id' => $request->user()->id,
                'tracking_token' => $validated['tracking_token'],
            ]);
        }

        Notification::make()->title('QR Code berhasil disimpan.')->success()->send();

        return redirect()->to(QrCodeResource::getUrl('index'));
    }

    public function track(Request $request, string $code): RedirectResponse
    {
        // Numeric ids keep QR codes printed before tracking tokens existed working.
        $qrCode = QrCode::where(ctype_digit($code) ? 'id' : 'tracking_token', $code)->firstOrFail();

        if ($qrCode->scan_tracking) {
            $qrCode->increment('total_scans');

            $ip = $request->ip() ?: '127.0.0.1';
            $cacheKey = "qr_unique_{$qrCode->id}_{$ip}";

            if (!cache()->has($cacheKey)) {
                cache()->put($cacheKey, true, now()->addDays(30));
                $qrCode->increment('unique_scans');
            }

            if (empty($qrCode->top_location) || $qrCode->top_location === '-') {
                $qrCode->update(['top_location' => 'Indonesia']);
            }
        }

        return redirect()->away($qrCode->link);
    }

    private function decodePng(string $dataUrl): ?string
    {
        if (! str_starts_with($dataUrl, 'data:image/png;base64,')) {
            return null;
        }

        $binary = base64_decode(Str::after($dataUrl, 'data:image/png;base64,'), true);

        return $binary !== false && str_starts_with($binary, "\x89PNG\r\n\x1a\n") ? $binary : null;
    }
}
