<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationType extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }
}
