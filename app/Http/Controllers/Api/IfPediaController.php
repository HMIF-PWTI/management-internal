<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IfPedia;
use Illuminate\Support\Facades\Storage;
class IfPediaController extends Controller
{
    public function index()
    {
        $ifPedia = IfPedia::query()
            ->where('is_active', 1)
            ->latest()
            ->first();

        if (!$ifPedia) {
            return response()->json([
                'success' => false,
                'message' => 'IF-Pedia belum tersedia',
                'payload' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data IF-Pedia berhasil diambil',
            'payload' => [
                'id' => $ifPedia->id,
                'judul' => $ifPedia->judul,
                'deskripsi' => $ifPedia->deskripsi,

                // FE nanti pakai endpoint ini, bukan /storage langsung
                'file_pdf' => url('/api/v1/if-pedias/pdf'),

                'is_active' => (bool) $ifPedia->is_active,
                'created_at' => $ifPedia->created_at,
                'updated_at' => $ifPedia->updated_at,
            ],
        ]);
    }

  
public function pdf()
{
    $ifPedia = IfPedia::where('is_active', 1)->latest()->first();

    if (!$ifPedia || !$ifPedia->file_pdf) {
        return response()->json(['message' => 'PDF tidak ditemukan'], 404);
    }

  $path = storage_path('app/public/' . $ifPedia->file_pdf);

   if (!file_exists($path)) {
    return response()->json([
        'message' => 'File PDF tidak ditemukan',
        'path' => $path,
        'file_pdf' => $ifPedia->file_pdf,
    ], 404);
}

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $ifPedia->judul . '.pdf"',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
    ]);
}

}