<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AbsensiNotificationMail;
use App\Models\Absensi;
use App\Models\CekKartu;
use App\Models\Kegiatan;
use App\Models\RfidCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class RfidScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
        ]);

        $uid = strtoupper(trim($request->uid));
        $now = Carbon::now();

        $rfidCard = RfidCard::where('uid', $uid)->first();

        if (! $rfidCard) {
            CekKartu::create([
                'uid' => $uid,
                'rfid_card_id' => null,
                'user_id' => null,
                'status' => 'unknown',
                'scanned_at' => $now,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak terdaftar',
                'status' => 'unknown',
                'uid' => $uid,
            ], 404);
        }

        // Relasi RfidCard::user() menghubungkan kartu dan user melalui NIM.
        $user = $rfidCard->user()
            ->with('divisi')
            ->first();

        $cekKartu = CekKartu::create([
            'uid' => $uid,
            'rfid_card_id' => $rfidCard->id,
            'user_id' => $user?->id,
            'status' => ! $rfidCard->is_active ? 'inactive' : 'valid',
            'scanned_at' => $now,
        ]);

        if (! $rfidCard->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak aktif',
                'status' => 'inactive',
                'uid' => $uid,
                'nim' => $rfidCard->nim,
                'nama' => $rfidCard->nama,
                'divisi' => $rfidCard->divisi?->singkatan ?? $rfidCard->divisi?->nama ?? $rfidCard->jabatan ?? '-',
            ], 403);
        }

        $kegiatan = Kegiatan::whereDate('tanggal', $now->toDateString())
            ->where('is_absen_active', true)
            ->first();

        $absensi = Absensi::where('rfid_card_id', $rfidCard->id)
            ->whereDate('tanggal', $now->toDateString())
            ->where('kegiatan_id', $kegiatan?->id)
            ->first();

        if (! $absensi) {
            $absensi = Absensi::create([
                'user_id' => $user?->id,
                'rfid_card_id' => $rfidCard->id,
                'cek_kartu_id' => $cekKartu->id,
                'kegiatan_id' => $kegiatan?->id,
                'tanggal' => $now->toDateString(),
                'jam_masuk' => $now->format('H:i:s'),
                'status' => 'hadir',
            ]);

            $message = 'Absen masuk berhasil';
            $mailSubject = 'Notifikasi Absen Masuk';
        } else {
            $absensi->update([
                'jam_keluar' => $now->format('H:i:s'),
                'status' => 'pulang',
            ]);

            $message = 'Absen pulang berhasil';
            $mailSubject = 'Notifikasi Absen Pulang';
        }

        $absensi->load(['user.divisi', 'kegiatan']);

        $emailSent = false;
        $emailStatus = 'not_sent';

        if (! $user) {
            $emailStatus = 'user_not_found';

            Log::warning('Email absensi tidak dikirim karena user RFID tidak ditemukan', [
                'absensi_id' => $absensi->id,
                'rfid_card_id' => $rfidCard->id,
                'uid' => $uid,
                'nim' => $rfidCard->nim,
            ]);
        } elseif (blank($user->email)) {
            $emailStatus = 'email_empty';

            Log::warning('Email absensi tidak dikirim karena email user kosong', [
                'absensi_id' => $absensi->id,
                'user_id' => $user->id,
            ]);
        } elseif (! filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $emailStatus = 'email_invalid';

            Log::warning('Email absensi tidak dikirim karena format email tidak valid', [
                'absensi_id' => $absensi->id,
                'user_id' => $user->id,
            ]);
        } else {
            try {
                Mail::to($user->email)->send(
                    new AbsensiNotificationMail($absensi, $mailSubject)
                );

                $emailSent = true;
                $emailStatus = 'sent';
            } catch (Throwable $exception) {
                $emailStatus = 'smtp_failed';

                Log::error('Email absensi gagal dikirim', [
                    'absensi_id' => $absensi->id,
                    'user_id' => $user->id,
                    'exception' => $exception,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $absensi->status,
            'uid' => $uid,
            'nim' => $user?->nim ?? $rfidCard->nim,
            'nama' => $user?->nama ?? $rfidCard->nama,
            'email' => $user?->email,
            'email_sent' => $emailSent,
            'email_status' => $emailStatus,
            'divisi' => $user?->divisi?->singkatan ?? $rfidCard->divisi?->singkatan ?? $rfidCard->divisi?->nama ?? $rfidCard->jabatan ?? '-',
            'kegiatan' => $kegiatan?->nama,
            'tanggal' => $absensi->tanggal,
            'jam_masuk' => $absensi->jam_masuk,
            'jam_keluar' => $absensi->jam_keluar,
            'absensi' => $absensi,
        ]);
    }
}
