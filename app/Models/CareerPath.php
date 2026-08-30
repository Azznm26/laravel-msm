<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareerPath extends Model
{
    use HasFactory;

    protected $table = 'career_paths'; // Opsional, tapi baik untuk kepastian

    protected $fillable = [
        'name',
        'description',
        'order'
    ];

    // =========================================================================
    // RELATIONS: USERS (MANY-TO-MANY)
    // =========================================================================

    /**
     * Relasi ke User (Karyawan).
     * Menggunakan tabel pivot 'career_path_user'.
     */
    public function users()
    {
        // PERBAIKAN:
        // 1. Nama tabel pivot disesuaikan dengan SQL: 'career_path_user'
        // 2. Kolom pivot disesuaikan dengan SQL: current_level, total_exp, dll.
        return $this->belongsToMany(User::class, 'career_path_user', 'career_path_id', 'user_id')
            ->withPivot(['current_level', 'total_exp', 'is_active', 'start_date', 'end_date'])
            ->withTimestamps();
    }

    /**
     * Helper: Ambil user yang aktif saja di jalur karir ini
     */
    public function activeUsers()
    {
        return $this->users()->wherePivot('is_active', true);
    }

    // =========================================================================
    // RELATIONS: STRUCTURE (JABATAN & DEPARTMENT)
    // =========================================================================

    public function jabatans()
    {
        return $this->belongsToMany(Jabatan::class, 'career_path_jabatan', 'career_path_id', 'jabatan_id')
            ->withPivot('level_order', 'is_highest_level')
            ->orderBy('career_path_jabatan.level_order', 'asc');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'career_path_department', 'career_path_id', 'department_id');
    }

    // =========================================================================
    // RELATIONS: LEVELS configuration
    // =========================================================================

    public function levels()
    {
        return $this->hasMany(CareerPathLevel::class, 'career_path_id')->orderBy('level', 'asc');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
