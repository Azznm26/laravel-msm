<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'pertanyaan', 'wajib', 'urutan'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
