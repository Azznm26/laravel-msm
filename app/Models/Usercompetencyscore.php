<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCompetencyScore extends Model
{
    protected $fillable = [
        'user_id',
        'criteria_id',
        'actual_value',
        'assessed_at',
        'assessed_by',
        'notes',
    ];

    protected $casts = [
        'actual_value' => 'integer',
        'assessed_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(CareerGapCriteria::class, 'criteria_id');
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
