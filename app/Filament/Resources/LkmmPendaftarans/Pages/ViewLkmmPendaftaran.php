<?php

namespace App\Filament\Resources\LkmmPendaftarans\Pages;

use App\Filament\Resources\LkmmPendaftarans\LkmmPendaftaranResource;
use App\Models\LkmmPendaftaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ViewLkmmPendaftaran extends ViewRecord
{
    protected static string $resource = LkmmPendaftaranResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Informasi Pendaftaran')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('nomor_registrasi')
                            ->label('Nomor Registrasi')
                            ->copyable(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending'  => 'warning',
                                'diterima' => 'success',
                                'ditolak'  => 'danger',
                                default    => 'gray',
                            }),
                        TextEntry::make('created_at')
                            ->label('Tanggal Daftar')
                            ->dateTime('d M Y, H:i'),
                    ]),
                ]),

            Section::make('Data Diri Calon Peserta')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('nama_lengkap')->label('Nama Lengkap'),
                        TextEntry::make('nim')->label('NIM')->copyable(),
                        TextEntry::make('angkatan')->label('Angkatan'),
                        TextEntry::make('kelas')->label('Kelas'),
                        TextEntry::make('ipk')->label('IPK Berjalan'),
                        TextEntry::make('no_wa')->label('No. WhatsApp')->copyable(),
                        TextEntry::make('email')->label('Email')->copyable(),
                    ]),
                ]),

            Section::make('Dokumen Berkas Persyaratan')
                ->description('Klik dokumen di bawah ini untuk membuka atau mengunduh berkas pendaftar.')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('file_formulir')
                            ->label('1. Formulir Pendaftaran LKMM')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_formulir ? Storage::disk('public')->url($record->file_formulir) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),

                        TextEntry::make('file_pernyataan')
                            ->label('2. Surat Pernyataan Calon Pendaftar')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_pernyataan ? Storage::disk('public')->url($record->file_pernyataan) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),

                        TextEntry::make('file_izin_ortu')
                            ->label('3. Surat Izin Orang Tua / Wali')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_izin_ortu ? Storage::disk('public')->url($record->file_izin_ortu) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),

                        TextEntry::make('file_cv')
                            ->label('4. Curriculum Vitae (CV)')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_cv ? Storage::disk('public')->url($record->file_cv) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),

                        TextEntry::make('file_surat_aktif')
                            ->label('5. Surat Keterangan Aktif Mahasiswa')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_surat_aktif ? Storage::disk('public')->url($record->file_surat_aktif) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),

                        TextEntry::make('file_kulber')
                            ->label('6. Sertifikat Kulber / Surat Keterangan')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_kulber ? Storage::disk('public')->url($record->file_kulber) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),

                        TextEntry::make('file_transkrip')
                            ->label('7. Transkrip Nilai Akademik')
                            ->formatStateUsing(fn ($state) => $state ? '📄 Lihat / Unduh Dokumen' : 'Belum diunggah')
                            ->url(fn ($record) => $record->file_transkrip ? Storage::disk('public')->url($record->file_transkrip) : null)
                            ->openUrlInNewTab()
                            ->badge(fn ($state) => (bool) $state)
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),
                    ]),
                ]),

            Section::make('Catatan Verifikasi Panitia')
                ->schema([
                    TextEntry::make('catatan')
                        ->label('Catatan Panitia')
                        ->default('Belum ada catatan verifikasi.'),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_all')
                ->label('Download Semua Berkas (.zip)')
                ->color('primary')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $record = $this->record;
                    $cleanName = Str::slug($record->nama_lengkap, '_');
                    $zipName = "LKMM_{$record->nim}_{$cleanName}.zip";
                    $tempDir = storage_path('app/temp');

                    if (!is_dir($tempDir)) {
                        mkdir($tempDir, 0755, true);
                    }

                    $tempPath = "{$tempDir}/{$zipName}";

                    $zip = new ZipArchive();
                    if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                        $fileMap = [
                            'file_formulir'   => '01_Formulir_Pendaftaran',
                            'file_pernyataan' => '02_Surat_Pernyataan',
                            'file_izin_ortu'  => '03_Surat_Izin_Ortu',
                            'file_cv'         => '04_Curriculum_Vitae',
                            'file_surat_aktif'=> '05_Surat_Keterangan_Aktif',
                            'file_kulber'     => '06_Sertifikat_Kulber',
                            'file_transkrip'  => '07_Transkrip_Nilai',
                        ];

                        foreach ($fileMap as $field => $label) {
                            $filePath = $record->$field;
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                                $fullDiskPath = Storage::disk('public')->path($filePath);
                                $zip->addFile($fullDiskPath, "{$label}_{$record->nim}.{$ext}");
                            }
                        }
                        $zip->close();
                    }

                    return response()->download($tempPath, $zipName)->deleteFileAfterSend(true);
                }),

            Action::make('terima')
                ->label('Terima Berkas')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->form([
                    Textarea::make('catatan')
                        ->label('Catatan Panitia (Opsional)')
                        ->placeholder('Contoh: Berkas telah diverifikasi lengkap dan memenuhi syarat.'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status'  => 'diterima',
                        'catatan' => $data['catatan'] ?? $this->record->catatan ?? 'Berkas telah diverifikasi lengkap.',
                    ]);
                    Notification::make()->title('Pendaftar berhasil diterima!')->success()->send();
                    $this->refreshFormData(['status', 'catatan']);
                })
                ->visible(fn () => $this->record->status !== 'diterima'),

            Action::make('tolak')
                ->label('Tolak Berkas')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->form([
                    Textarea::make('catatan')
                        ->label('Alasan Penolakan / Catatan Perbaikan')
                        ->required()
                        ->placeholder('Contoh: Transkrip nilai belum lengkap atau IPK belum memenuhi kriteria minimal 2.75.'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status'  => 'ditolak',
                        'catatan' => $data['catatan'],
                    ]);
                    Notification::make()->title('Pendaftar ditolak dengan catatan.')->danger()->send();
                    $this->refreshFormData(['status', 'catatan']);
                })
                ->visible(fn () => $this->record->status !== 'ditolak'),

            Action::make('edit_catatan')
                ->label('Ubah Catatan')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->fillForm(fn () => ['catatan' => $this->record->catatan])
                ->form([
                    Textarea::make('catatan')
                        ->label('Catatan Panitia')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->update(['catatan' => $data['catatan']]);
                    Notification::make()->title('Catatan berhasil diperbarui!')->success()->send();
                    $this->refreshFormData(['catatan']);
                }),

            Action::make('reset_pending')
                ->label('Reset ke Pending')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'pending']);
                    Notification::make()->title('Status pendaftar direset ke pending.')->warning()->send();
                    $this->refreshFormData(['status']);
                })
                ->visible(fn () => $this->record->status !== 'pending'),
        ];
    }
}
