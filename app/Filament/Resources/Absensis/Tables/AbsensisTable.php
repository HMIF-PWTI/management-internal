<?php

namespace App\Filament\Resources\Absensis\Tables;

use App\Models\Absensi;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $userLogin = auth()->user();

                $query->with([
                    'user.divisi',
                    'rfidCard.divisi',
                    'kegiatan',
                ]);

                $isSuperAdmin = strtolower(trim($userLogin?->divisi?->singkatan ?? '')) === 'superadmin';

                if (! $isSuperAdmin) {
                    if ($userLogin && ! empty($userLogin->nim)) {
                        Absensi::whereNull('user_id')
                            ->whereHas('rfidCard', fn ($rfidQuery) => $rfidQuery->where('nim', $userLogin->nim))
                            ->update(['user_id' => $userLogin->id]);
                    }

                    $query->where('absensis.user_id', $userLogin?->id ?? 0);
                }
            })
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->state(fn ($record) => $record->user?->nama ?? $record->rfidCard?->nama ?? '-')
                    ->searchable(),

                TextColumn::make('nim')
                    ->label('NIM')
                    ->state(fn ($record) => $record->user?->nim ?? $record->rfidCard?->nim ?? '-')
                    ->searchable(),

                TextColumn::make('divisi_jabatan')
                    ->label('Divisi / Jabatan')
                    ->state(function ($record) {
                        $divisi = $record->user?->divisi?->singkatan
                            ?? $record->rfidCard?->divisi?->singkatan
                            ?? $record->rfidCard?->divisi?->nama
                            ?? '-';

                        $jabatan = $record->user?->jabatan
                            ?? $record->rfidCard?->jabatan
                            ?? '-';

                        return "{$divisi} / {$jabatan}";
                    }),

                TextColumn::make('kegiatan.nama')
                    ->label('Kegiatan')
                    ->placeholder('-'),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date(),

                TextColumn::make('jam_masuk')
                    ->label('Jam Masuk'),

                TextColumn::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
           ->headerActions([
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        $userLogin = auth()->user();

                        $query = Absensi::query()
                            ->with([
                                'user.divisi',
                                'rfidCard.divisi',
                                'kegiatan',
                            ]);

                        $isSuperAdmin = strtolower(trim($userLogin?->divisi?->singkatan ?? '')) === 'superadmin';

                        if (! $isSuperAdmin) {
                            $query->where('absensis.user_id', $userLogin?->id ?? 0);
                        }

                        $absensis = $query->get();

                        $pdf = Pdf::loadView('pdf.absensi', [
                            'absensis' => $absensis,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'data-absensi-' . now()->format('Y-m-d-H-i-s') . '.pdf');
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(fn () => strtolower(trim(auth()->user()?->divisi?->singkatan ?? '')) === 'superadmin'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn () => strtolower(trim(auth()->user()?->divisi?->singkatan ?? '')) === 'superadmin'),
            ]);
    }
}