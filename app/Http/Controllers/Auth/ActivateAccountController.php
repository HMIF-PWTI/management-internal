<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountActivatedMail;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActivateAccountController extends Controller
{
    public function __invoke(Request $request, string $user): RedirectResponse
    {
        if (! $request->hasValidRelativeSignature() && ! $request->hasValidSignature()) {
            abort(403, 'Link aktivasi tidak valid atau sudah expired.');
        }

        $account = User::query()->find($user);

        if (! $account) {
            Log::warning('Link aktivasi merujuk ke akun yang tidak ditemukan.', [
                'user_id' => $user,
            ]);

            Notification::make()
                ->danger()
                ->title('Akun tidak ditemukan.')
                ->body('Silakan daftar kembali untuk mendapatkan link aktivasi baru.')
                ->send();

            return redirect('/admin/register');
        }

        $alreadyActive = strtolower(trim((string) $account->status_keanggotaan)) === 'aktif';

        if (! $alreadyActive) {
            $account->update([
                'status_keanggotaan' => 'aktif',
            ]);

            // Email teks: akun sudah aktif (hanya dikirim sekali)
            try {
                Mail::to($account->email)->send(new AccountActivatedMail($account));
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
