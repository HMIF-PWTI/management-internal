<?php

namespace App\Filament\Resources\RfidCards\Pages;

use App\Filament\Resources\RfidCards\RfidCardResource;
use App\Models\Divisi;
use App\Models\RfidCard;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListRfidCards extends ListRecords
{
    protected static string $resource = RfidCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('importCsv')
                ->label('Import CSV')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File CSV')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $path = Storage::disk('local')->path($data['file']);

                        if (! file_exists($path)) {
                            Notification::make()
                                ->title('Import gagal')
                                ->body('File CSV tidak ditemukan.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $file = fopen($path, 'r');

                        if (! $file) {
                            Notification::make()
                                ->title('Import gagal')
                                ->body('File CSV gagal dibuka.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $divisis = Divisi::query()
                            ->pluck('id', 'singkatan')
                            ->mapWithKeys(fn ($id, $singkatan) => [
                                strtolower(trim($singkatan)) => $id,
                            ])
                            ->toArray();

                        $created = 0;
                        $updated = 0;
                        $skipped = 0;
                        $errors = [];

                        while (($row = fgetcsv($file, 2000, ';')) !== false) {
                            $uid = strtoupper(trim($row[0] ?? ''));
                            $nama = trim($row[2] ?? '');
                            $jabatan = trim($row[3] ?? '');
                            $nim = trim($row[4] ?? '');

                            if (
                                $uid === '' ||
                                strtolower($uid) === 'uid' ||
                                strtolower($nama) === 'nama' ||
                                strtolower($nim) === 'nim'
                            ) {
                                $skipped++;
                                continue;
                            }

                            if ($nama === '' || $nim === '') {
                                $skipped++;
                                $errors[] = "UID {$uid} dilewati karena nama/NIM kosong.";
                                continue;
                            }

                            $divisiId = $this->detectDivisiId($jabatan, $divisis);

                            if ($divisiId === null) {
                                $errors[] = "Divisi tidak ketemu: {$jabatan}";
                            }

                            $rfidCard = RfidCard::updateOrCreate(
                                ['uid' => $uid],
                                [
                                    'nim' => $nim,
                                    'nama' => $nama,
                                    'jabatan' => $jabatan,
                                    'divisi_id' => $divisiId,
                                    'is_active' => true,
                                ]
                            );

                            if ($rfidCard->wasRecentlyCreated) {
                                $created++;
                            } else {
                                $updated++;
                            }
                        }

                        fclose($file);

                        $errorMessage = count($errors) > 0
                            ? ' Error: ' . implode(' | ', array_slice($errors, 0, 5))
                            : '';

                        Notification::make()
                            ->title('Import CSV selesai')
                            ->body("Data baru: {$created}, update: {$updated}, skip: {$skipped}.{$errorMessage}")
                            ->success()
                            ->send();

                    } catch (\Throwable $e) {
                        Log::error('Import RFID CSV gagal', [
                            'message' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                        ]);

                        Notification::make()
                            ->title('Import CSV gagal')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    private function detectDivisiId(string $jabatan, array $divisis): ?int
    {
        $text = strtolower($jabatan);

        if (
            str_contains($text, 'ketua umum') ||
            str_contains($text, 'wakil ketua') ||
            str_contains($text, 'sekretaris') ||
            str_contains($text, 'bendahara')
        ) {
            return $divisis['superadmin'] ?? null;
        }

        if (str_contains($text, 'pwti')) {
            return $divisis['pwti'] ?? null;
        }

        if (str_contains($text, 'kominfo')) {
            return $divisis['kominfo'] ?? null;
        }

        if (str_contains($text, 'kesma')) {
            return $divisis['kesma'] ?? null;
        }

        if (str_contains($text, 'kesra')) {
            return $divisis['kesra'] ?? null;
        }

        if (str_contains($text, 'kwu')) {
            return $divisis['kwu'] ?? null;
        }

        if (str_contains($text, 'pao')) {
            return $divisis['pao'] ?? null;
        }

        if (str_contains($text, 'sosma')) {
            return $divisis['sosma'] ?? null;
        }

        return null;
    }
}