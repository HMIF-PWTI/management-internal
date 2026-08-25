<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HmifPedia extends Model
{
    protected $table = 'hmif_pedias';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file_pdf',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $hmifPedia): void {
            if (! $hmifPedia->is_active) {
                return;
            }

            DB::transaction(function () use ($hmifPedia): void {
                self::query()
                    ->whereKeyNot($hmifPedia->getKey())
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            });
        });
    }
}
