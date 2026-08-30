<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';

    protected $fillable = [
        'nama_jabatan',
        'department_id',
        // 'career_track',
        // 'level_in_track',
        // // 'is_admin', // opsional, tapi sangat membantu untuk role check
    ];


    public function users()
    {
        return $this->hasMany(User::class, 'jabatan_id');
    }

    public function careerPaths()
    {
        return $this->belongsToMany(CareerPath::class, 'career_path_jabatan');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'jabatan_id');
    }

    public function careerPath()
    {
        return $this->belongsTo(CareerPath::class, 'career_path_id');
    }
}
