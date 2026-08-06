<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapakKwu extends Model
{
    protected $fillable = [
    'gambar',
    'nama_produk',
    'harga',
    'deskripsi',
    'link',
    'is_active',
    ];
}
