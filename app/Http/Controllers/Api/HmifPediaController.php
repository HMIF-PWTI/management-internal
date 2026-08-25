<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HmifPedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HmifPediaController extends Controller
{
    public function index(): JsonResponse
    {
        $hmifPedia = HmifPedia::query()
            ->where('is_active', true)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'data' => $hmifPedia ? [
                'id' => $hmifPedia->id,
                'judul' => $hmifPedia->judul,
                'deskripsi' => $hmifPedia->deskripsi,
                'file_pdf' => $hmifPedia->file_pdf,
                'pdf_url' => url('/api/hmif-pedia/pdf'),
                'created_at' => $hmifPedia->created_at,
                'updated_at' => $hmifPedia->updated_at,
            ] : null,
        ]);
    }

    public function pdf(): JsonResponse|StreamedResponse
    {
        $hmifPedia = HmifPedia::query()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (! $hmifPedia || ! Storage::disk('public')->exists($hmifPedia->file_pdf)) {
            return response()->json([
                'success' => false,
                'message' => 'File HMIF-PEDIA tidak ditemukan.',
            ], 404);
        }

        return Storage::disk('public')->response(
            $hmifPedia->file_pdf,
            basename($hmifPedia->file_pdf),
            ['Content-Type' => 'application/pdf'],
            'inline',
        );
    }
}
