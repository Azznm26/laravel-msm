<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCareerProgress extends Model
{
    use HasFactory;

    protected $table = 'user_career_progress';

    protected $fillable = [
        'user_id',
        'jabatan_id',
        'current_level',
        'total_exp',
        'total_score',
    ];

    protected $casts = [
        'total_exp' => 'integer',
        'current_level' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function careerPath()
    {
        return $this->user->careerPath;
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function currentLevelRecord()
    {
        return $this->user->careerPath
            ? $this->user->careerPath->levels()->where('level', $this->current_level)->first()
            : null;
    }
}
