<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfoKp;

class InfoKpController extends Controller
{
    public function index()
    {
        $data = InfoKp::query()
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Info KP berhasil diambil',
            'payload' => $data,
        ]);
    }
}