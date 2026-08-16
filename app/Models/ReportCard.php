<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportCard extends Model
{
    protected $fillable = [
        'student_id', 'class_id', 'academic_year_id', 'period', 'general_average',
        'rank', 'class_size', 'decision', 'comment', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'general_average' => 'decimal:2',
            'published_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(ReportCardLine::class);
    }
}
