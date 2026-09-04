<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HmifFormSection extends Model
{
    protected $fillable = ['form_id', 'title', 'description', 'position', 'after_section_action', 'after_section_target_id'];

    protected function casts(): array { return ['position' => 'integer']; }
    public function form(): BelongsTo { return $this->belongsTo(HmifForm::class, 'form_id'); }
    public function questions(): HasMany { return $this->hasMany(HmifFormQuestion::class, 'section_id')->orderBy('sort_order'); }

    public function navigationTarget(): BelongsTo { return $this->belongsTo(self::class, 'after_section_target_id'); }
}
