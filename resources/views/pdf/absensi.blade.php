<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Absensi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eeeeee;
        }
    </style>
</head>
<body>
    <h2>
    Data Absensi
    @if (auth()->user()->divisi?->singkatan !== 'superadmin')
        {{ auth()->user()->nama }}
    @endif
</h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIM</th>
                <th>Divisi / Jabatan</th>
                <th>Kegiatan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensis as $absensi)
                @php
                    $nama = $absensi->user?->nama ?? $absensi->rfidCard?->nama ?? '-';
                    $nim = $absensi->user?->nim ?? $absensi->rfidCard?->nim ?? '-';
                    $divisi = $absensi->user?->divisi?->singkatan ?? $absensi->rfidCard?->divisi?->singkatan ?? $absensi->rfidCard?->divisi?->nama ?? '-';
                    $jabatan = $absensi->user?->jabatan ?? $absensi->rfidCard?->jabatan ?? '-';
                @endphp

                <tr>
                    <td>{{ $nama }}</td>
                    <td>{{ $nim }}</td>
                    <td>{{ $divisi }} / {{ $jabatan }}</td>
                    <td>{{ $absensi->kegiatan?->nama ?? '-' }}</td>
                    <td>{{ $absensi->tanggal }}</td>
                    <td>{{ $absensi->jam_masuk ?? '-' }}</td>
                    <td>{{ $absensi->jam_keluar ?? '-' }}</td>
                    <td>{{ $absensi->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>