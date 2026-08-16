<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Internship extends Model
{
    protected $fillable = [
        'student_id', 'company_id', 'supervisor_contact_id', 'formation_id',
        'start_date', 'end_date', 'agreement_path', 'report_path', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function supervisorContact(): BelongsTo
    {
        return $this->belongsTo(CompanyContact::class, 'supervisor_contact_id');
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(InternshipEvaluation::class);
    }
}
