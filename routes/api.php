<?php

use App\Http\Controllers\Api\HmifPediaController;
use App\Http\Controllers\Api\IfPediaController;
use App\Http\Controllers\Api\InfoKpController;
use App\Http\Controllers\Api\KegiatanController;
use App\Http\Controllers\Api\LapakKwuController;
use App\Http\Controllers\Api\LkmmPendaftaranController;
use App\Http\Controllers\Api\RfidScanController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json([
    'success' => true,
    'message' => 'API running',
]));

Route::post('/rfid/scan', [RfidScanController::class, 'scan'])
    ->middleware(['rfid.api.key', 'throttle:30,1']);
Route::get('/lapak-kwu', [LapakKwuController::class, 'index']);
Route::get('/lapak-kwu/{id}', [LapakKwuController::class, 'show']);
Route::get('/hmif-pedia', [HmifPediaController::class, 'index']);
Route::get('/hmif-pedia/pdf', [HmifPediaController::class, 'pdf']);

Route::prefix('v1')->group(function () {
    Route::get('/kegiatans', [KegiatanController::class, 'index']);
});

Route::prefix('v1')->group(function () {
    Route::get('/if-pedias', [IfPediaController::class, 'index']);
    Route::get('/if-pedias/pdf', [IfPediaController::class, 'pdf']);
});

Route::prefix('v1')->group(function () {
    Route::get('/info-kps', [InfoKpController::class, 'index']);
});

// LKMM Pendaftaran - Public endpoints (no auth required)
Route::prefix('lkmm')->group(function () {
    Route::post('/daftar', [LkmmPendaftaranController::class, 'store']);
    Route::get('/status', [LkmmPendaftaranController::class, 'checkStatus']);
});
