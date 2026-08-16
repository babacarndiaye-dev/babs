<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipEvaluation extends Model
{
    protected $fillable = ['internship_id', 'evaluator_name', 'score', 'comments', 'evaluated_at'];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'evaluated_at' => 'date',
        ];
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
