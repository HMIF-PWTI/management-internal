<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CekKartu;
use App\Models\RfidCard;
use App\Models\User;
use App\Models\Kegiatan;
class Absensi extends Model
{
    protected $fillable = [
        'cek_kartu_id',
        'rfid_card_id',
        'user_id',
        'kegiatan_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
    ];

    public function cekKartu()
    {
        return $this->belongsTo(CekKartu::class);
    }

    public function rfidCard()
    {
        return $this->belongsTo(RfidCard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}