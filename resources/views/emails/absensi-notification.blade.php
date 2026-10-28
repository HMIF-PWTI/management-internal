@php
    $isPulang = ! empty($absensi->jam_keluar);

    $judul = $isPulang
        ? 'Absensi Pulang Berhasil'
        : 'Absensi Masuk Berhasil';

    $pesan = $isPulang
        ? 'Absensi pulang kamu berhasil dicatat.'
        : 'Absensi masuk kamu berhasil dicatat.';
@endphp

<h2>{{ $judul }}</h2>

<p>Halo {{ $absensi->rfidCard?->nama ?? $absensi->user?->nama ?? 'Pengguna' }},</p>

<p>{{ $pesan }}</p>

<table cellpadding="6" cellspacing="0">
    <tr>
        <td><strong>Nama</strong></td>
        <td>: {{ $absensi->rfidCard?->nama ?? $absensi->user?->nama ?? '-' }}</td>
    </tr>

    <tr>
        <td><strong>NIM</strong></td>
        <td>: {{ $absensi->rfidCard?->nim ?? $absensi->user?->nim ?? '-' }}</td>
    </tr>

    <tr>
        <td><strong>Email</strong></td>
        <td>: {{ $absensi->user?->email ?? '-' }}</td>
    </tr>

    <tr>
        <td><strong>Divisi</strong></td>
        <td>: {{ $absensi->user?->divisi?->singkatan ?? '-' }}</td>
    </tr>

    <tr>
        <td><strong>Kegiatan</strong></td>
        <td>
            : {{ $absensi->kegiatan?->nama ?? 'Absensi Umum / Tidak ada kegiatan aktif' }}
        </td>
    </tr>

    <tr>
        <td><strong>Tanggal</strong></td>
        <td>
            : {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') }}
        </td>
    </tr>

    <tr>
        <td><strong>Jam Masuk</strong></td>
        <td>: {{ $absensi->jam_masuk ?? '-' }}</td>
    </tr>

    @if ($isPulang)
        <tr>
            <td><strong>Jam Pulang</strong></td>
            <td>: {{ $absensi->jam_keluar ?? '-' }}</td>
        </tr>
    @endif

    <tr>
        <td><strong>Status</strong></td>
        <td>: {{ ucfirst($absensi->status ?? '-') }}</td>
    </tr>
</table>

<p>
    Terima kasih.<br>
    <strong>HMIF UNIKOM</strong>
</p>

<hr>

<p style="font-size: 12px; color: #777;">
    Email ini dikirim otomatis oleh Sistem Absensi Internal HMIF UNIKOM.
    Mohon tidak membalas email ini.
</p>