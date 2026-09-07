<?php

namespace App\Http\Controllers;

use App\Filament\Resources\QrCodes\QrCodeResource;
use App\Models\QrCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class QrCodeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'link' => ['required', 'url', 'max:2048'],
            'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,gif,svg', 'max:2048'],
            'qr_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'frame' => ['required', Rule::in(['none', 'simple', 'scan'])],
            'shape' => ['required', Rule::in([
                'square',
                'dots',
                'dot',
                'rounded',
                'extra-rounded',
                'classy',
                'classy-rounded',
            ])],
            'scan_tracking' => ['nullable'],
            'qr_image_data' => ['nullable', 'string'],
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('qr-logos', 'public');
        }

        $shape = $validated['shape'] === 'dot' ? 'dots' : $validated['shape'];
        $scanTracking = $request->has('scan_tracking') ? $request->boolean('scan_tracking') : true;

        $qrImageUrl = null;
        if (!empty($validated['qr_image_data']) && str_starts_with($validated['qr_image_data'], 'data:image/')) {
            $data = $validated['qr_image_data'];
            @list($type, $data) = explode(';', $data);
            @list(, $data) = explode(',', $data);
            $decoded = base64_decode($data);
            if ($decoded !== false) {
                $filename = 'qr-codes/' . Str::random(40) . '.png';
                /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
                $storage = Storage::disk('public');
                $storage->put($filename, $decoded);
                $qrImageUrl = $storage->url($filename);
            }
        }

        $qrCode = QrCode::create([
            'user_id' => $request->user()->id,
            'link' => $validated['link'],
            'logo_path' => $logoPath,
            'qr_color' => strtolower($validated['qr_color']),
            'background_color' => strtolower($validated['background_color']),
            'frame' => $validated['frame'],
            'shape' => $shape,
            'scan_tracking' => $scanTracking,
            'qr_image_url' => $qrImageUrl,
        ]);

        if ($scanTracking) {
            $qrCode->refreshQrImage();
        }

        return redirect()
            ->to(QrCodeResource::getUrl('index'))
            ->with('status', 'QR Code berhasil disimpan ke database.');
    }

    public function track(Request $request, QrCode $qrCode): RedirectResponse
    {
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
}
