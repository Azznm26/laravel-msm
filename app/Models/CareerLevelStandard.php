<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerLevelStandard extends Model
{
    protected $fillable = [
        'career_path_level_id',
        'criteria_id',
        'target_value',
    ];

    protected $casts = [
        'target_value' => 'integer',
    ];

    public function careerPathLevel(): BelongsTo
    {
        return $this->belongsTo(CareerPathLevel::class, 'career_path_level_id');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(CareerGapCriteria::class, 'criteria_id');
    }
}
