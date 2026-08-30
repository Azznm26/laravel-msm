<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

// Pastikan model lain terimport jika namespace berbeda, 
// tapi karena sesama namespace App\Models, biasanya aman tanpa use,
// namun untuk best practice saya tuliskan referensinya di logic.

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_badge',
        'photo',
        'id_badge_photo',
        'status',
        'department_id',
        'jabatan_id',
        'career_path_id', // Ini tetap ada untuk menyimpan PRIMARY PATH
        'role',
        'is_admin',
        'last_seen',          // Tambahan dari SQL
        'requested_jabatan',   // Tambahan dari SQL
        'requested_department', // Tambahan dari SQL
        'can_review_tasks',    // Tambahan dari SQL
        'can_view_reports',    // Tambahan dari SQL
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'can_review_tasks' => 'boolean',
        'can_view_reports' => 'boolean',
        'last_seen' => 'datetime',
    ];

    // =========================================================================
    // RELATIONS: CORE & CAREER PATH
    // =========================================================================

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * PRIMARY CAREER PATH (Single Select)
     * Relasi ini menunjuk ke career path utama user (misal: jabatan struktural).
     * Digunakan untuk keperluan Payroll atau Struktur Organisasi Utama.
     */
    public function careerPath() // Bisa direname jadi 'primaryCareerPath' jika mau lebih jelas
    {
        return $this->belongsTo(CareerPath::class, 'career_path_id');
    }

    /**
     * ALL CAREER PATHS (Multi Select / Many-to-Many)
     * Relasi ini menampung SEMUA jalur karir user (Utama + Tambahan/Cross-skill).
     * Digunakan untuk Logic Task, Training, dan Kompetensi.
     */
    public function careerPaths()
    {
        // Parameter: (ModelTujuan, NamaTabelPivot, FK_ModelIni, FK_ModelTujuan)
        return $this->belongsToMany(CareerPath::class, 'career_path_user', 'user_id', 'career_path_id')
            ->withPivot(['current_level', 'total_exp', 'is_active', 'start_date', 'end_date'])
            ->withTimestamps();
    }

    /**
     * Helper: Hanya mengambil Career Path yang statusnya ACTIVE
     */
    public function activeCareerPaths()
    {
        return $this->careerPaths()->wherePivot('is_active', true);
    }

    public function careerProgress()
    {
        return $this->hasOne(UserCareerProgress::class, 'user_id');
    }

    // =========================================================================
    // RELATIONS: TASKS & ACTIVITIES
    // =========================================================================

    public function assignedTasks()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function uploadedFiles()
    {
        return $this->hasMany(UserUploadedFile::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id');
    }

    public function taskResults()
    {
        return $this->hasMany(TaskResult::class, 'user_id');
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    // =========================================================================
    // LOGIC & HELPER ACCESSORS
    // =========================================================================

    public function isAdmin()
    {
        return $this->is_admin === true;
    }

    public function isManager()
    {
        // Menggunakan null coalescing operator (??) dan str_contains php 8+
        return !$this->isAdmin() && str_contains($this->jabatan?->nama_jabatan ?? '', 'Manager');
    }

    public function isStaff()
    {
        return !$this->isAdmin() && str_contains($this->jabatan?->nama_jabatan ?? '', 'Staff');
    }

    public function subordinates()
    {
        if (!$this->department_id) {
            return self::whereRaw('1 = 0');
        }

        return self::where('department_id', $this->department_id)
            ->where('id', '!=', $this->id);
    }

    public function hasSubordinates()
    {
        return $this->subordinates()->exists();
    }

    // Mengambil Jabatan ID dari progress karir user, jika tidak ada, ambil jabatan_id default
    public function getCurrentJabatanIdAttribute()
    {
        return $this->careerProgress?->jabatan_id ?? $this->jabatan_id;
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    public function deviceTokens()
    {
        return $this->hasMany(\App\Models\DeviceToken::class);
    }
}
