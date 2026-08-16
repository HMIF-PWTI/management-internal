<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class KegiatanController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $kegiatans = Kegiatan::query()
                ->where('is_web_active', true)
                ->orderBy('tanggal', 'asc')
                ->get()
                ->map(fn (Kegiatan $kegiatan): array => [
                    'id' => $kegiatan->id,
                    'gambar' => $this->getPublicImageUrl($kegiatan->gambar),
                    'nama' => $kegiatan->nama,
                    'tanggal' => $kegiatan->tanggal->format('Y-m-d'),
                    'deskripsi' => $kegiatan->deskripsi,
                    'lokasi' => $kegiatan->lokasi,
                    'is_absen_active' => $kegiatan->is_absen_active,
                    'is_web_active' => $kegiatan->is_web_active,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kegiatan berhasil diambil',
                'payload' => $kegiatans,
            ]);
        } catch (Throwable $exception) {
            Log::error('Gagal mengambil data kegiatan publik.', [
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kegiatan',
                'payload' => [],
            ], 500);
        }
    }

    private function getPublicImageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        return Storage::disk('public')->url($image);
    }
}
