<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class AktivasiAkunMail extends Mailable
{
    use Queueable, SerializesModels, \App\Mail\Concerns\EmbedsHmifLogo;

    public User $user;
    public string $activationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->activationUrl = URL::temporarySignedRoute(
            'akun.aktivasi',
            now()->addHours(24),
            ['user' => $user->id]
        );
    }

    public function build()
    {
        return $this->embedHmifLogo()
            ->subject('Aktivasi Akun Internal HMIF')
            ->markdown('emails.aktivasi-akun');
    }
}