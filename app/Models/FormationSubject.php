<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationSubject extends Model
{
    protected $table = 'formation_subjects';

    protected $fillable = ['formation_id', 'subject_id', 'level_id', 'coefficient', 'hours_per_week'];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
