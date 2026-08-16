<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use App\Observers\KegiatanObserver;
use App\Services\FirebaseKegiatanService;
use PHPUnit\Framework\TestCase;

class KegiatanObserverTest extends TestCase
{
    public function test_created_event_synchronizes_the_kegiatan(): void
    {
        $kegiatan = $this->kegiatan(true);
        $firebase = $this->createMock(FirebaseKegiatanService::class);
        $firebase->expects($this->once())->method('sync')->with($kegiatan);

        (new KegiatanObserver($firebase))->created($kegiatan);
    }

    public function test_updated_event_resynchronizes_the_kegiatan(): void
    {
        $kegiatan = $this->kegiatan(false);
        $firebase = $this->createMock(FirebaseKegiatanService::class);
        $firebase->expects($this->once())->method('sync')->with($kegiatan);

        (new KegiatanObserver($firebase))->updated($kegiatan);
    }

    public function test_deleted_event_removes_the_kegiatan(): void
    {
        $kegiatan = $this->kegiatan(true);
        $firebase = $this->createMock(FirebaseKegiatanService::class);
        $firebase->expects($this->once())->method('delete')->with($kegiatan);

        (new KegiatanObserver($firebase))->deleted($kegiatan);
    }

    private function kegiatan(bool $isWebActive): Kegiatan
    {
        $kegiatan = new Kegiatan([
            'nama' => 'Lifecycle test',
            'tanggal' => '2026-08-16',
            'deskripsi' => 'Lifecycle observer test',
            'is_absen_active' => false,
            'is_web_active' => $isWebActive,
        ]);
        $kegiatan->id = 999;

        return $kegiatan;
    }
}
