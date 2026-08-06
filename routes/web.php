<?php

use App\Http\Controllers\Auth\ActivateAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/aktivasi-akun/{user}', ActivateAccountController::class)
    ->name('akun.aktivasi');