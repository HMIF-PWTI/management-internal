<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HmifFormResponseFile extends Model
{
    protected $fillable = ['response_id', 'question_id', 'disk', 'path', 'original_name', 'mime_type', 'size'];

    protected static function booted(): void
    {
        static::deleting(fn (self $file) => Storage::disk($file->disk)->delete($file->path));
    }

    public function response(): BelongsTo { return $this->belongsTo(HmifFormResponse::class, 'response_id'); }
    public function question(): BelongsTo { return $this->belongsTo(HmifFormQuestion::class, 'question_id'); }
}
