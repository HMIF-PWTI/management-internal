<?php

namespace App\Console\Commands;

use App\Services\FirebaseKegiatanService;
use Illuminate\Console\Command;

class SyncKegiatansToFirebase extends Command
{
    protected $signature = 'firebase:sync-kegiatans';

    protected $description = 'Sinkronkan seluruh kegiatan MySQL ke Firebase Realtime Database';

    public function handle(FirebaseKegiatanService $firebase): int
    {
        $result = $firebase->syncAll();

        $this->info("Data MySQL ditemukan: {$result['found']}");
        $this->info("Data aktif disinkronkan: {$result['synced']}");
        $this->info("Data nonaktif dipastikan terhapus: {$result['removed']}");
        $this->line("Gagal: {$result['failed']}");

        return $result['failed'] === 0 ? self::SUCCESS : self::FAILURE;
    }
}
