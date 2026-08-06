<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    protected $table = 'divisis';

    protected $fillable = [
        'nama',
        'singkatan',
        'deskripsi',
    ];

    /**
     * Relasi divisi dengan fitur melalui tabel pivot divisi_fitur.
     */
public function fiturs(): BelongsToMany
    {
        return $this->belongsToMany(
            Fitur::class,
            'divisi_fitur',
            'id_divisi',
            'fitur_id'
        )->withTimestamps();
    }

    /**
     * Relasi divisi dengan user.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_divisi');
    }
}