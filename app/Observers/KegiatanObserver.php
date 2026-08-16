<?php

namespace App\Observers;

use App\Models\Kegiatan;
use App\Services\FirebaseKegiatanService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;
use Throwable;

class KegiatanObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(private readonly FirebaseKegiatanService $firebase) {}

    public function created(Kegiatan $kegiatan): void
    {
        $this->attempt('sync', $kegiatan);
    }

    public function updated(Kegiatan $kegiatan): void
    {
        $this->attempt('sync', $kegiatan);
    }

    public function deleted(Kegiatan $kegiatan): void
    {
        $this->attempt('delete', $kegiatan);
    }

    private function attempt(string $operation, Kegiatan $kegiatan): void
    {
        try {
            $this->firebase->{$operation}($kegiatan);
        } catch (Throwable $exception) {
            Log::error('Sinkronisasi lifecycle kegiatan ke Firebase gagal.', [
                'kegiatan_id' => $kegiatan->getKey(),
                'operation' => $operation,
                'exception_class' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
