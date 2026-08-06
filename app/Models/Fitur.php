<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fitur extends Model
{
    protected $table = 'fiturs';

    protected $fillable = [
        'nama',
        'resource',
    ];

 public function divisis(): BelongsToMany
    {
        return $this->belongsToMany(
            Divisi::class,
            'divisi_fitur',
            'fitur_id',
            'id_divisi'
        )->withTimestamps();
    }
}