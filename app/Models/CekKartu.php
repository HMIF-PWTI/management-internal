<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RfidCard;
use App\Models\Absensi;

class CekKartu extends Model
{
    protected $fillable = [
        'uid',
        'rfid_card_id',
        'status',
        'scanned_at',
    ];

    public function rfidCard()
    {
        return $this->belongsTo(RfidCard::class);
    }

    public function absensi()
    {
        return $this->hasOne(Absensi::class);
    }
}