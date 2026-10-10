<?php

namespace App\Mail\Concerns;

use RuntimeException;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

trait EmbedsHmifLogo
{
    /**
     * Tempel logo HMIF langsung ke email (inline CID) agar tampil
     * walau APP_URL tidak bisa diakses publik.
     */
    protected function embedHmifLogo(): static
    {
        return $this->withSymfonyMessage(function (Email $message) {
            $logoContents = file_get_contents(public_path('images/logo-hmif.png'));

            if ($logoContents === false) {
                throw new RuntimeException('Logo HMIF tidak dapat dibaca.');
            }

            $logo = (new DataPart(
                $logoContents,
                'logo-hmif.png',
                'image/png',
            ))
                ->asInline()
                ->setContentId('logo-hmif@hmifunikom.my.id');

            $message->addPart($logo);
        });
    }
}
