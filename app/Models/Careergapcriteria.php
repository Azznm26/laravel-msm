<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerGapCriteria extends Model
{
    protected $table = 'career_gap_criteria'; // ← eksplisit, karena Eloquent salah tebak jadi "career_gap_criterias"
    protected $fillable = [
        'aspect_id',
        'name',
        'factor_type', // 'core' | 'secondary'
        'order',
    ];

    public function aspect(): BelongsTo
    {
        return $this->belongsTo(CareerGapAspect::class, 'aspect_id');
    }

    public function levelStandards(): HasMany
    {
        return $this->hasMany(CareerLevelStandard::class, 'criteria_id');
    }

    public function competencyScores(): HasMany
    {
        return $this->hasMany(UserCompetencyScore::class, 'criteria_id');
    }

    public function isCore(): bool
    {
        return $this->factor_type === 'core';
    }
}
