<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Formation extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'formation_type_id', 'domain_id', 'title', 'slug', 'short_description',
        'description', 'objectives', 'curriculum', 'skills', 'career_opportunities',
        'admission_requirements', 'duration_value', 'duration_unit', 'level_label',
        'tuition_fee', 'seats_available', 'start_date', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'tuition_fee' => 'decimal:2',
            'start_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function formationType(): BelongsTo
    {
        return $this->belongsTo(FormationType::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function specialties(): HasMany
    {
        return $this->hasMany(Specialty::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'formation_subjects')
            ->withPivot(['level_id', 'coefficient', 'hours_per_week'])
            ->withTimestamps();
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function durationLabel(): string
    {
        if (! $this->duration_value || ! $this->duration_unit) {
            return '';
        }

        return "{$this->duration_value} {$this->duration_unit}";
    }
}
