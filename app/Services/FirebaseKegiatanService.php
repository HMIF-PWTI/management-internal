<?php

namespace App\Services;

use App\Models\Kegiatan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Throwable;

class FirebaseKegiatanService
{
    public function __construct(private readonly Database $database) {}

    public function sync(Kegiatan $kegiatan): void
    {
        if (! $kegiatan->is_web_active) {
            $this->delete($kegiatan);

            return;
        }

        $this->reference($kegiatan)->set($this->payload($kegiatan));
    }

    public function delete(Kegiatan $kegiatan): void
    {
        $this->reference($kegiatan)->remove();
    }

    /** @return array{found: int, synced: int, removed: int, failed: int} */
    public function syncAll(): array
    {
        $result = ['found' => 0, 'synced' => 0, 'removed' => 0, 'failed' => 0];

        Kegiatan::query()->orderBy('id')->chunkById(100, function ($kegiatans) use (&$result): void {
            foreach ($kegiatans as $kegiatan) {
                $result['found']++;

                try {
                    $this->sync($kegiatan);
                    $result[$kegiatan->is_web_active ? 'synced' : 'removed']++;
                } catch (Throwable $exception) {
                    $result['failed']++;
                    Log::error('Gagal menyinkronkan kegiatan ke Firebase.', [
                        'kegiatan_id' => $kegiatan->getKey(),
                        'exception_class' => $exception::class,
                        'message' => $exception->getMessage(),
                    ]);
                }
            }
        });

        return $result;
    }

    /** @return array{id: int, nama: string, tanggal: string, deskripsi: string, lokasi: ?string, gambar: ?string, is_absen_active: bool, is_web_active: bool} */
    public function payload(Kegiatan $kegiatan): array
    {
        return [
            'id' => (int) $kegiatan->getKey(),
            'nama' => (string) $kegiatan->nama,
            'tanggal' => $kegiatan->tanggal->format('Y-m-d'),
            'deskripsi' => (string) $kegiatan->deskripsi,
            'lokasi' => $kegiatan->lokasi,
            'gambar' => $this->publicImageUrl($kegiatan->gambar),
            'is_absen_active' => (bool) $kegiatan->is_absen_active,
            'is_web_active' => (bool) $kegiatan->is_web_active,
        ];
    }

    private function reference(Kegiatan $kegiatan): Reference
    {
        return $this->database->getReference('kegiatans/'.(int) $kegiatan->getKey());
    }

    private function publicImageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        return filter_var($image, FILTER_VALIDATE_URL)
            ? $image
            : Storage::disk('public')->url($image);
    }
}
