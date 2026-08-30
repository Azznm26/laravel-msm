<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'nama_department',
    ];


    // KODE BARU (Perbaikan)
    public function jabatans()
    {
        // Mengurutkan berdasarkan nama jabatan atau kolom lain yang masih ada
        return $this->hasMany(Jabatan::class, 'department_id', 'id')
            ->orderBy('nama_jabatan');
        // Anda juga bisa menghapus ->orderBy('nama_jabatan') jika pengurutan tidak wajib
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }


    public function careerPaths()
    {
        return $this->belongsToMany(CareerPath::class, 'career_path_department');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'jabatan_id')
            ->whereHas('jabatan', function ($query) {
                $query->whereColumn('jabatan.department_id', 'departments.id');
            });
    }
}
