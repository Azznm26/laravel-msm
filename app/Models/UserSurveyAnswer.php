<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSurveyAnswer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_survey_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'survey_question_id',
        'answer', // boolean: true = Ya, false = Tidak
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'answer' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who answered the survey.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task of this answer.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the survey question being answered.
     */
    public function surveyQuestion()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }
}
