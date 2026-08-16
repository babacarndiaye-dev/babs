<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialty extends Model
{
    protected $fillable = ['formation_id', 'name', 'slug'];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }
}
