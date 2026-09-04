<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HmifForm extends Model
{
    protected $fillable = ['uuid', 'owner_id', 'title', 'description', 'status', 'public_token', 'require_login', 'collect_email', 'limit_one_response', 'show_progress_bar', 'shuffle_questions', 'required_by_default', 'confirmation_message', 'opens_at', 'closes_at', 'theme_settings', 'published_at'];

    protected function casts(): array
    {
        return ['require_login' => 'boolean', 'collect_email' => 'boolean', 'limit_one_response' => 'boolean', 'show_progress_bar' => 'boolean', 'shuffle_questions' => 'boolean', 'required_by_default' => 'boolean', 'theme_settings' => 'array', 'opens_at' => 'datetime', 'closes_at' => 'datetime', 'published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $form): void {
            $form->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string { return 'uuid'; }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function questions(): HasMany { return $this->hasMany(HmifFormQuestion::class, 'form_id')->orderBy('sort_order'); }
    public function sections(): HasMany { return $this->hasMany(HmifFormSection::class, 'form_id')->orderBy('position'); }
    public function responses(): HasMany { return $this->hasMany(HmifFormResponse::class, 'form_id'); }
}
