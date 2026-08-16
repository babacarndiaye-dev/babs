<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradingSystem extends Model
{
    protected $fillable = ['name', 'scale_max', 'type', 'passing_threshold', 'is_default'];

    protected function casts(): array
    {
        return [
            'scale_max' => 'decimal:2',
            'passing_threshold' => 'decimal:2',
            'is_default' => 'boolean',
        ];
    }

    public function scales(): HasMany
    {
        return $this->hasMany(GradingScale::class);
    }
}
