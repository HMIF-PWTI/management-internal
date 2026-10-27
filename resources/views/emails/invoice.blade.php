<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran - HMIF UNIKOM</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 24px; color: #1e293b;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <tr>
            <td style="background-color: #0f172a; padding: 28px 32px; text-align: left;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">HMIF UNIKOM</h1>
                            <p style="color: #94a3b8; margin: 4px 0 0 0; font-size: 13px;">Himpunan Mahasiswa Informatika</p>
                        </td>
                        <td style="text-align: right;">
                            <span style="display: inline-block; background-color: #f59e0b; color: #ffffff; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 13px; text-transform: uppercase;">
                                INVOICE
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Main Content -->
        <tr>
            <td style="padding: 32px;">
                <p style="margin: 0 0 16px 0; font-size: 15px;">Halo <strong>{{ $invoice->nama_penerima }}</strong>,</p>
                <p style="margin: 0 0 24px 0; font-size: 14px; color: #475569; line-height: 1.5;">
                    Berikut adalah rincian tagihan / invoice keuangan resmi dari Himpunan Mahasiswa Informatika (HMIF) UNIKOM:
                </p>

                <!-- Box Total Nominal -->
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px; text-align: center;">
                    <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Tagihan</span>
                    <h2 style="margin: 8px 0 0 0; font-size: 28px; color: #0f172a; font-weight: 800;">
                        Rp {{ number_format($invoice->nominal, 0, ',', '.') }}
                    </h2>
                </div>

                <!-- Info Table -->
                <table width="100%" cellpadding="8" cellspacing="0" style="font-size: 14px; border-collapse: collapse; margin-bottom: 24px;">
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td width="35%" style="color: #64748b; padding: 10px 0;"><strong>No. Invoice</strong></td>
                        <td style="color: #0f172a; font-weight: 600; padding: 10px 0;">{{ $invoice->nomor_invoice }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="color: #64748b; padding: 10px 0;"><strong>Keperluan / Uraian</strong></td>
                        <td style="color: #0f172a; padding: 10px 0;">{{ $invoice->judul }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="color: #64748b; padding: 10px 0;"><strong>Tanggal Terbit</strong></td>
                        <td style="color: #0f172a; padding: 10px 0;">{{ \Carbon\Carbon::parse($invoice->tanggal)->translatedFormat('d F Y') }}</td>
                    </tr>
                    @if ($invoice->jatuh_tempo)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="color: #64748b; padding: 10px 0;"><strong>Batas Waktu (Jatuh Tempo)</strong></td>
                        <td style="color: #ef4444; font-weight: 600; padding: 10px 0;">{{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->translatedFormat('d F Y') }}</td>
                    </tr>
                    @endif
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="color: #64748b; padding: 10px 0;"><strong>Status Pembayaran</strong></td>
                        <td style="padding: 10px 0;">
                            @if ($invoice->status === 'lunas')
                                <span style="background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 12px; text-transform: uppercase;">Lunas</span>
                            @elseif ($invoice->status === 'terkirim')
                                <span style="background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 12px; text-transform: uppercase;">Menunggu Pembayaran</span>
                            @else
                                <span style="background-color: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 12px; text-transform: uppercase;">{{ ucfirst($invoice->status) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Catatan / Instruksi Pembayaran -->
                @if ($invoice->catatan)
                <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
                    <strong style="color: #92400e; font-size: 13px; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Instruksi Pembayaran / Catatan:</strong>
                    <p style="margin: 0; font-size: 13px; color: #78350f; line-height: 1.5; white-space: pre-line;">{{ $invoice->catatan }}</p>
                </div>
                @endif

                <p style="margin: 24px 0 0 0; font-size: 14px; line-height: 1.5;">
                    Jika Anda memiliki pertanyaan terkait invoice ini, silakan hubungi pengurus divisi terkait atau bendahara HMIF UNIKOM.
                </p>

                <p style="margin: 20px 0 0 0; font-size: 14px;">
                    Salam hangat,<br>
                    <strong>Pengurus HMIF UNIKOM</strong>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; text-align: center;">
                <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                    Email ini dikirim secara resmi oleh Sistem Internal HMIF UNIKOM.<br>
                    &copy; {{ date('Y') }} HMIF UNIKOM. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
