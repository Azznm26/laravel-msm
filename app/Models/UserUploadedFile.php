<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserUploadedFile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_uploaded_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'file_path',
        'is_approved', // <--- Tambahkan ini
        'approved_at', // <--- Tambahkan ini
        'approved_by', // <--- Tambahkan ini
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_approved' => 'boolean', // <--- Tambahan opsional: biar otomatis jadi true/false
        'approved_at' => 'datetime', // <--- Tambahan opsional: biar otomatis jadi format tanggal
    ];
    /**
     * Get the user who uploaded the file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task associated with this upload.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the full URL to the uploaded file (for public access).
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the file name only.
     */
    public function getFileNameAttribute()
    {
        return basename($this->file_path);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}
