<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'assigned_at',
        'deadline',
        'status',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', 'assigned')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<', now());
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
