<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'foto',
        'nama',
        'jabatan',
        'id_divisi',
        'urutan',
        'is_inti',
        'is_active',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }
}