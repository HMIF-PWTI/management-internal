<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LapakKwu;

class LapakKwuController extends Controller
{
    public function index()
    {
        $lapakKwu = LapakKwu::where('is_active', 1)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'gambar' => $item->gambar
                        ? asset('storage/' . $item->gambar)
                        : null,
                    'nama_produk' => $item->nama_produk,
                    'harga' => $item->harga,
                    'deskripsi' => $item->deskripsi,
                    'link' => $item->link,
                    'is_active' => $item->is_active,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Data Lapak KWU berhasil diambil',
            'data' => $lapakKwu,
        ]);
    }
}