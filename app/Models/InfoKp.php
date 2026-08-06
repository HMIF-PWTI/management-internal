<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoKp extends Model
{
    protected $fillable = [
        'nama_perusahaan',
        'kota',
        'alamat',
        'is_active',
    ];
}