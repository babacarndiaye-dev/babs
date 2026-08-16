<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeSchedule extends Model
{
    protected $fillable = ['formation_id', 'academic_year_id', 'fee_type_id', 'frequency', 'amount', 'installments_count'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }
}
