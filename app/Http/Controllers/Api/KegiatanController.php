<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::query()
            ->where('is_web_active', 1)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($kegiatan) {
                return [
                    'id' => $kegiatan->id,
                    'gambar' => $kegiatan->gambar
                        ? asset('storage/' . $kegiatan->gambar)
                        : null,
                    'nama' => $kegiatan->nama,
                    'tanggal' => $kegiatan->tanggal,
                    'deskripsi' => $kegiatan->deskripsi,
                    'lokasi' => $kegiatan->lokasi,
                    'is_absen_active' => (bool) $kegiatan->is_absen_active,
                    'is_web_active' => (bool) $kegiatan->is_web_active,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Data kegiatan berhasil diambil',
            'payload' => $kegiatans,
        ]);
    }
}