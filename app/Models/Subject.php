<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'formation_subjects')
            ->withPivot(['level_id', 'coefficient', 'hours_per_week'])
            ->withTimestamps();
    }
}
