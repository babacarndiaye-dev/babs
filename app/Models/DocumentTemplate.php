<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = ['code', 'name', 'description', 'blade_view', 'numbering_format', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
