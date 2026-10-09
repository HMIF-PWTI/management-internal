<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountActivatedMail;
use Filament\Notifications\Notification;

class ActivateAccountController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Link aktivasi tidak valid atau sudah expired.');
        }

        $alreadyActive = strtolower(trim((string) $user->status_keanggotaan)) === 'aktif';

        if (! $alreadyActive) {
            $user->update([
                'status_keanggotaan' => 'aktif',
            ]);

            // Email teks: akun sudah aktif (hanya dikirim sekali)
            try {
                Mail::to($user->email)->send(new AccountActivatedMail($user));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        Notification::make()
            ->success()
            ->title($alreadyActive ? 'Akun kamu sudah aktif.' : 'Akun berhasil diaktifkan.')
            ->body('Silakan login.')
            ->send();

        return redirect('/admin/login');
    }
}