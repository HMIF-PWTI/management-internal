<?php

namespace App\Mail;

use App\Models\Absensi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbsensiNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Absensi $absensi;
    public string $messageText;

    public function __construct(Absensi $absensi, string $messageText)
    {
        $this->absensi = $absensi;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject($this->messageText)
            ->view('emails.absensi-notification');
    }
}