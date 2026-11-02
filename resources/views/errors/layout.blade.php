<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('code') - @yield('title') | HMIF</title>
    <link rel="icon" href="{{ asset('images/logo-hmif.png') }}">
    {{-- CSS inline dan tanpa auth/DB/Vite: halaman ini harus tetap tampil saat aplikasi sedang rusak. --}}
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            color: #0f172a;
            background: #0b1220 radial-gradient(circle at 75% 15%, #1e3a8a 0, transparent 60%) no-repeat fixed;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        main {
            width: 100%;
            max-width: 420px;
            padding: 32px 28px 24px;
            text-align: center;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, .4);
        }
        img { display: block; width: 72px; height: auto; margin: 0 auto 20px; }
        .code { margin: 0; color: #c8102e; font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        h1 { margin: 8px 0 12px; font-size: 22px; line-height: 1.3; }
        .message { margin: 0 0 24px; color: #475569; font-size: 14px; line-height: 1.6; }
        .actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; }
        .btn {
            padding: 10px 16px;
            color: #334155;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover { background: #f1f5f9; }
        .btn:focus-visible { outline: 2px solid #1e3a8a; outline-offset: 2px; }
        .btn-primary { color: #fff; background: #1e3a8a; border-color: #1e3a8a; }
        .btn-primary:hover { background: #1e40af; }
        footer { margin-top: 24px; padding-top: 16px; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <main>
        <img src="{{ asset('images/logo-hmif.png') }}" alt="Logo HMIF" width="72" height="72">
        <p class="code">Error @yield('code')</p>
        <h1>@yield('title')</h1>
        <p class="message">@yield('message')</p>
        <div class="actions">
            <a class="btn btn-primary" href="{{ url('/') }}">Ke Beranda</a>
            <button class="btn" type="button" onclick="history.back()">Kembali</button>
        </div>
        <footer>Himpunan Mahasiswa Teknik Informatika UNIKOM</footer>
    </main>
</body>
</html>
