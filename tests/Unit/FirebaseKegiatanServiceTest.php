<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use App\Services\FirebaseKegiatanService;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Database;
use Tests\TestCase;

class FirebaseKegiatanServiceTest extends TestCase
{
    public function test_it_builds_a_public_firebase_payload_with_consistent_types(): void
    {
        config()->set('filesystems.disks.public.url', 'https://example.test/storage');
        Storage::forgetDisk('public');

        $kegiatan = new Kegiatan([
            'nama' => 'Kegiatan HMIF',
            'tanggal' => '2026-06-08',
            'deskripsi' => 'Deskripsi kegiatan',
            'lokasi' => 'Kampus',
            'gambar' => 'kegiatans/poster.jpg',
            'is_absen_active' => 1,
            'is_web_active' => 1,
        ]);
        $kegiatan->id = 3;

        $service = new FirebaseKegiatanService($this->createMock(Database::class));

        $this->assertSame([
            'id' => 3,
            'nama' => 'Kegiatan HMIF',
            'tanggal' => '2026-06-08',
            'deskripsi' => 'Deskripsi kegiatan',
            'lokasi' => 'Kampus',
            'gambar' => 'https://example.test/storage/kegiatans/poster.jpg',
            'is_absen_active' => true,
            'is_web_active' => true,
        ], $service->payload($kegiatan));
    }

    public function test_it_preserves_an_existing_public_image_url(): void
    {
        $kegiatan = new Kegiatan([
            'nama' => 'Kegiatan HMIF',
            'tanggal' => '2026-06-08',
            'deskripsi' => 'Deskripsi kegiatan',
            'gambar' => 'https://cdn.example.test/poster.jpg',
            'is_absen_active' => false,
            'is_web_active' => true,
        ]);
        $kegiatan->id = 4;

        $service = new FirebaseKegiatanService($this->createMock(Database::class));

        $this->assertSame('https://cdn.example.test/poster.jpg', $service->payload($kegiatan)['gambar']);
    }

    public function test_sync_removes_an_inactive_kegiatan_instead_of_writing_it(): void
    {
        $kegiatan = new Kegiatan(['is_web_active' => false]);
        $kegiatan->id = 5;

        $service = $this->getMockBuilder(FirebaseKegiatanService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        $service->expects($this->once())->method('delete')->with($kegiatan);

        $service->sync($kegiatan);
    }
}
