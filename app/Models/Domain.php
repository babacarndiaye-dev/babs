<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }
}
