<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LkmmPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LkmmPendaftaranController extends Controller
{
    /**
     * Submit Formulir dan Pengumpulan Berkas LKMM
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap'    => 'required|string|max:255',
            'nim'             => 'required|string|max:20',
            'angkatan'        => 'required|in:2024,2025',
            'kelas'           => 'required|string|max:10',
            'ipk'             => 'required|numeric|min:2.75|max:4.00',
            'no_wa'           => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            // Setiap file dibatasi maksimal 10 MB (10240 KB)
            'file_formulir'   => 'required|file|mimes:pdf,doc,docx|max:10240',
            'file_pernyataan' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'file_izin_ortu'  => 'required|file|mimes:pdf,doc,docx|max:10240',
            'file_cv'         => 'required|file|mimes:pdf,doc,docx|max:10240',
            'file_surat_aktif'=> 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file_kulber'     => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file_transkrip'  => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'nama_lengkap.required'     => 'Nama lengkap wajib diisi.',
            'nim.required'              => 'NIM wajib diisi.',
            'angkatan.required'         => 'Angkatan wajib dipilih.',
            'angkatan.in'               => 'Pendaftaran LKMM dikhususkan untuk angkatan 2024 dan 2025.',
            'kelas.required'            => 'Kelas wajib diisi.',
            'ipk.required'              => 'IPK wajib diisi.',
            'ipk.min'                   => 'IPK minimal adalah 2.75.',
            'ipk.max'                   => 'Nilai IPK tidak boleh melebihi 4.00.',
            'no_wa.required'            => 'Nomor WhatsApp wajib diisi.',
            'email.required'            => 'Email aktif wajib diisi.',
            'email.email'               => 'Format email tidak valid.',
            'file_formulir.required'    => 'Formulir pendaftaran wajib diunggah.',
            'file_pernyataan.required'  => 'Surat pernyataan wajib diunggah.',
            'file_izin_ortu.required'   => 'Surat izin orang tua wajib diunggah.',
            'file_cv.required'          => 'Curriculum Vitae (CV) wajib diunggah.',
            'file_surat_aktif.required' => 'Surat keterangan aktif kuliah wajib diunggah.',
            'file_kulber.required'      => 'Sertifikat atau surat kuliah bersama (Kulber) wajib diunggah.',
            'file_transkrip.required'   => 'Transkrip nilai akademik wajib diunggah.',
            'file_formulir.max'         => 'Ukuran Formulir Pendaftaran melebihi batas maksimal 10 MB.',
            'file_pernyataan.max'       => 'Ukuran Surat Pernyataan melebihi batas maksimal 10 MB.',
            'file_izin_ortu.max'        => 'Ukuran Surat Izin Orang Tua melebihi batas maksimal 10 MB.',
            'file_cv.max'               => 'Ukuran Dokumen CV melebihi batas maksimal 10 MB.',
            'file_surat_aktif.max'      => 'Ukuran Surat Keterangan Aktif melebihi batas maksimal 10 MB.',
            'file_kulber.max'           => 'Ukuran Dokumen Kulber melebihi batas maksimal 10 MB.',
            'file_transkrip.max'        => 'Ukuran Transkrip Nilai melebihi batas maksimal 10 MB.',
            '*.mimes'                   => 'Format berkas tidak sesuai. Harap unggah dokumen berformat PDF, DOC, atau DOCX (serta gambar JPG/PNG untuk surat & transkrip).',
            '*.max'                     => 'Setiap berkas dokumen berukuran maksimal 10 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data pendaftaran gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $nim = trim($request->input('nim'));

        // Cek pendaftaran dengan NIM yang sama
        $existing = LkmmPendaftaran::where('nim', $nim)->latest()->first();
        if ($existing && $existing->status !== 'ditolak') {
            return response()->json([
                'success' => false,
                'message' => "NIM {$nim} sudah terdaftar sebelumnya dengan Nomor Registrasi: {$existing->nomor_registrasi} (Status: {$existing->status}). Jika membutuhkan perbaikan berkas, silakan hubungi narahubung panitia.",
                'nomor_registrasi' => $existing->nomor_registrasi,
            ], 409);
        }

        // Generate Nomor Registrasi Unik
        $nomorRegistrasi = 'LKMM-2026-' . $nim . '-' . rand(1000, 9999);
        while (LkmmPendaftaran::where('nomor_registrasi', $nomorRegistrasi)->exists()) {
            $nomorRegistrasi = 'LKMM-2026-' . $nim . '-' . rand(1000, 9999);
        }

        // Simpan File ke Storage Public
        $storagePath = "lkmm-berkas/{$nim}";
        $storedFiles = [];

        foreach (LkmmPendaftaran::fileFields() as $field) {
            if ($request->hasFile($field)) {
                $storedFiles[$field] = $request->file($field)->store($storagePath, 'public');
            }
        }

        // Buat data pendaftaran baru
        $pendaftaran = LkmmPendaftaran::create([
            'nomor_registrasi' => $nomorRegistrasi,
            'nama_lengkap'     => $request->input('nama_lengkap'),
            'nim'              => $nim,
            'angkatan'         => $request->input('angkatan'),
            'kelas'            => $request->input('kelas'),
            'ipk'              => $request->input('ipk'),
            'no_wa'            => $request->input('no_wa'),
            'email'            => $request->input('email'),
            'status'           => 'pending',
            ...$storedFiles,
        ]);

        return response()->json([
            'success'          => true,
            'message'          => 'Pendaftaran LKMM berhasil! Harap simpan Nomor Registrasi Anda.',
            'nomor_registrasi' => $nomorRegistrasi,
            'data'             => [
                'nomor_registrasi' => $pendaftaran->nomor_registrasi,
                'nama_lengkap'     => $pendaftaran->nama_lengkap,
                'nim'              => $pendaftaran->nim,
                'angkatan'         => $pendaftaran->angkatan,
                'kelas'            => $pendaftaran->kelas,
                'email'            => $pendaftaran->email,
                'status'           => $pendaftaran->status,
            ],
        ], 201);
    }

    /**
     * Cek Status Pendaftaran Berdasarkan Nomor Registrasi atau NIM
     */
    public function checkStatus(Request $request)
    {
        $keyword = trim($request->input('nomor_registrasi') ?? $request->input('nim') ?? $request->input('keyword') ?? '');

        if (empty($keyword)) {
            return response()->json([
                'success' => false,
                'message' => 'Harap masukkan Nomor Registrasi atau NIM Anda.',
            ], 422);
        }

        $pendaftaran = LkmmPendaftaran::where('nomor_registrasi', $keyword)
            ->orWhere('nim', $keyword)
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false,
                'message' => "Data pendaftaran dengan kata kunci '{$keyword}' tidak ditemukan. Pastikan Nomor Registrasi atau NIM sudah benar.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'nomor_registrasi' => $pendaftaran->nomor_registrasi,
                'nama_lengkap'     => $pendaftaran->nama_lengkap,
                'nim'              => $pendaftaran->nim,
                'angkatan'         => $pendaftaran->angkatan,
                'kelas'            => $pendaftaran->kelas,
                'status'           => $pendaftaran->status,
                'catatan'          => $pendaftaran->catatan,
                'created_at'       => $pendaftaran->created_at ? $pendaftaran->created_at->format('d M Y, H:i') : null,
            ],
        ]);
    }
}
