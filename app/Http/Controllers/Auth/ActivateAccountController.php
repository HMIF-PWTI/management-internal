<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ActivateAccountController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Link aktivasi tidak valid atau sudah expired.');
        }

        $user->update([
            'status_keanggotaan' => 'aktif',
        ]);

        return redirect('/admin/login')->with('success', 'Akun berhasil diaktifkan. Silakan login.');
    }
}