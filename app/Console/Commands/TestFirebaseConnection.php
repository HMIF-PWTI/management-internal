<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use Throwable;

class TestFirebaseConnection extends Command
{
    protected $signature = 'firebase:test';

    protected $description = 'Verifikasi koneksi Firebase tanpa mengubah data';

    public function handle(Database $database): int
    {
        try {
            $database->getReference('kegiatans')->getSnapshot();
            $this->info('Firebase connection successful.');
            $this->line('Database: '.config('firebase.project_id'));

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Firebase connection failed. Periksa log aplikasi untuk detail aman.');

            return self::FAILURE;
        }
    }
}
