<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HmifFormResponse extends Model
{
    protected $fillable = ['form_id', 'respondent_id', 'respondent_email', 'submitted_at'];
    protected function casts(): array { return ['submitted_at' => 'datetime']; }
    public function form(): BelongsTo { return $this->belongsTo(HmifForm::class, 'form_id'); }
    public function answers(): HasMany { return $this->hasMany(HmifFormAnswer::class, 'response_id'); }
    public function files(): HasMany { return $this->hasMany(HmifFormResponseFile::class, 'response_id'); }

    protected static function booted(): void
    {
        static::deleting(fn (self $response) => $response->files()->get()->each->delete());
    }
}
