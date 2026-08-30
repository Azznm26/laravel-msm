<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerGapAspect extends Model
{
    protected $fillable = [
        'name',
        'cf_weight',
        'sf_weight',
        'order',
    ];

    protected $casts = [
        'cf_weight' => 'decimal:2',
        'sf_weight' => 'decimal:2',
    ];

    public function criteria(): HasMany
    {
        return $this->hasMany(CareerGapCriteria::class, 'aspect_id')->orderBy('order');
    }
}
