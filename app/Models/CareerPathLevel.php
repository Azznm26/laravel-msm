<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerPathLevel extends Model
{
    use HasFactory;

    protected $table = 'career_path_levels';

    protected $fillable = [
        'career_path_id',
        'jabatan_id',     
        'level',
        'level_order',         
        'level_name',           
        'description',
        'required_score',
        'required_exp',
    ];

    // =========================================================================
    // RELATIONS
    // =========================================================================

    // Relasi ke Parent (Career Path)
    public function careerPath()
    {
        return $this->belongsTo(CareerPath::class, 'career_path_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // [OPSIONAL] Relasi ke Task
    // Jika Anda punya tabel penghubung 'career_path_level_tasks' (lihat migration no 42 di SQL Anda)
    // Atau jika Task punya kolom 'level' dan 'career_path_id'
    /*
    public function tasks()
    {
        // Sesuaikan dengan logika task Anda nanti
        return $this->hasMany(Task::class, 'jabatan_id', 'jabatan_id'); 
    }
    */
}
