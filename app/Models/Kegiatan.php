<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'gambar',
        'nama',
        'tanggal',
        'deskripsi',
        'lokasi',
        'is_absen_active',
        'is_web_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date:Y-m-d',
            'is_absen_active' => 'boolean',
            'is_web_active' => 'boolean',
        ];
    }
}
