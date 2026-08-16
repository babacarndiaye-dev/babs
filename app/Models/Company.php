<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name', 'sector', 'address', 'phone', 'email'];

    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class);
    }

    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }
}
