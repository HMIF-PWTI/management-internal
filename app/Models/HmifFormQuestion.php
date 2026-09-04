<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HmifFormQuestion extends Model
{
    protected $fillable = ['form_id', 'section_id', 'type', 'label', 'description', 'settings', 'is_required', 'sort_order'];

    protected function casts(): array
    {
        return ['is_required' => 'boolean', 'settings' => 'array'];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(HmifForm::class, 'form_id');
    }

    public function section(): BelongsTo { return $this->belongsTo(HmifFormSection::class, 'section_id'); }

    public function answers(): HasMany
    {
        return $this->hasMany(HmifFormAnswer::class, 'question_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(HmifFormQuestionOption::class, 'question_id')->orderBy('position')->orderBy('id');
    }

    public function responseFiles(): HasMany { return $this->hasMany(HmifFormResponseFile::class, 'question_id'); }
}
