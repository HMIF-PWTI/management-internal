<x-mail::message>
# Aktivasi Akun Internal HMIF

Halo, {{ $user->nama }}

Akun internal HMIF kamu sudah dibuat, tetapi masih belum aktif.

Silakan klik tombol di bawah ini untuk mengaktifkan akun kamu.

<x-mail::button :url="$activationUrl">
Aktifkan Akun
</x-mail::button>

Link aktivasi ini berlaku selama 24 jam.

Terima kasih,<br>
HMIF UNIKOM
</x-mail::message>