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
}
