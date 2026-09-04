<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HmifFormAnswer extends Model
{
    protected $fillable = ['response_id', 'question_id', 'value'];
    public function response(): BelongsTo { return $this->belongsTo(HmifFormResponse::class, 'response_id'); }
    public function question(): BelongsTo { return $this->belongsTo(HmifFormQuestion::class, 'question_id'); }
}
