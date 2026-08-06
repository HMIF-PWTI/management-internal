<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IfPedia extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'file_pdf',
        'is_active',
    ];
}