<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LkmmPendaftaran extends Model
{
    protected $fillable = [
        'nomor_registrasi',
        'nama_lengkap',
        'nim',
        'angkatan',
        'kelas',
        'ipk',
        'no_wa',
        'email',
        'file_formulir',
        'file_pernyataan',
        'file_izin_ortu',
        'file_cv',
        'file_surat_aktif',
        'file_kulber',
        'file_transkrip',
        'status',
        'catatan',
    ];

    protected $casts = [
        'ipk' => 'decimal:2',
    ];

    /**
     * Daftar nama kolom berkas dokumen yang diunggah
     */
    public static function fileFields(): array
    {
        return [
            'file_formulir',
            'file_pernyataan',
            'file_izin_ortu',
            'file_cv',
            'file_surat_aktif',
            'file_kulber',
            'file_transkrip',
        ];
    }

    /**
     * Hapus berkas fisik di storage ketika data pendaftaran dihapus
     */
    protected static function booted(): void
    {
        static::deleting(function (LkmmPendaftaran $record) {
            foreach (static::fileFields() as $field) {
                if ($record->$field && Storage::disk('public')->exists($record->$field)) {
                    Storage::disk('public')->delete($record->$field);
                }
            }
        });
    }
}
