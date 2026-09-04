<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HmifFormQuestionOption extends Model
{
    protected $fillable = ['question_id', 'label', 'value', 'position', 'is_other'];

    protected function casts(): array
    {
        return ['position' => 'integer', 'is_other' => 'boolean'];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(HmifFormQuestion::class, 'question_id');
    }
}
