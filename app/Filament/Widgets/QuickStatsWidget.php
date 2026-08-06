<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class QuickStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        return [
            Stat::make('NIM', $user->nim ?? '-'),

            Stat::make('Divisi', $user->divisi?->nama ?? '-'),

            Stat::make('Status Keanggotaan', $user->status_keanggotaan ?? '-'),

            Stat::make('Status Absensi Hari Ini', $absensiHariIni?->status ?? 'Belum Absen'),
        ];
    }
}