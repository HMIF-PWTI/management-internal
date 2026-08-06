<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidCard extends Model
{
    protected $fillable = [
        'uid',
        'nim',
        'nama',
        'jabatan',
        'divisi_id',
        'is_active',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'nim');
    }

    public function cekKartus()
    {
        return $this->hasMany(CekKartu::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}