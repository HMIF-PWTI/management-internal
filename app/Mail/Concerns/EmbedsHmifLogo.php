<?php

namespace App\Mail\Concerns;

use Symfony\Component\Mime\Email;

trait EmbedsHmifLogo
{
    /**
     * Tempel logo HMIF langsung ke email (inline CID) agar tampil
     * walau APP_URL tidak bisa diakses publik.
     */
    protected function embedHmifLogo(): static
    {
        return $this->withSymfonyMessage(function (Email $message) {
            $message->embedFromPath(
                public_path('images/logo-hmif.png'),
                'logo-hmif',
                'image/png'
            );
        });
    }
}
