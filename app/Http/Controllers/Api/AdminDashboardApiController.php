<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Jabatan;
use App\Models\Department;
use App\Models\TaskResult;
use Illuminate\Support\Facades\DB;

/**
 * Versi API (mobile) dari App\Livewire\Admin\Dashboard.
 * Semua query & logika perhitungan disalin persis dari versi Livewire
 * supaya angka yang tampil di web & mobile selalu konsisten.
 */
class AdminDashboardApiController extends Controller
{
    public function __construct()
    {
        // Hanya admin & super_admin yang boleh mengakses dashboard ini.
        $this->middleware(function ($request, $next) {
            $role = optional($request->user())->role;

            if (!in_array($role, ['admin', 'super_admin'])) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return $next($request);
        });
    }

    /**
     * GET /admin/dashboard/summary
     */
    public function summary()
    {
        // 1. Statistik Cards
        $totalPengerjaan = TaskResult::count();

        $cards = [
            ['key' => 'total_task', 'title' => 'Total Task', 'count' => Task::count(), 'icon' => 'list-checks', 'color' => 'emerald'],
            ['key' => 'total_jabatan', 'title' => 'Total Jabatan', 'count' => Jabatan::count(), 'icon' => 'briefcase', 'color' => 'blue'],
            ['key' => 'total_dept', 'title' => 'Total Dept', 'count' => Department::count(), 'icon' => 'building-2', 'color' => 'indigo'],
            ['key' => 'total_user', 'title' => 'Total User', 'count' => User::count(), 'icon' => 'users', 'color' => 'amber'],
            ['key' => 'total_submit', 'title' => 'Total Submit', 'count' => $totalPengerjaan, 'icon' => 'file-text', 'color' => 'rose'],
            ['key' => 'user_online', 'title' => 'User Online', 'count' => User::where('last_seen', '>=', now()->subMinutes(5))->count(), 'icon' => 'wifi', 'color' => 'green'],
        ];

        // 2. Data User Online
        $onlineUsers = User::with('jabatan')
            ->where('last_seen', '>=', now()->subMinutes(5))
            ->orderBy('last_seen', 'desc')
            ->get();

        // 3. Rata-rata Nilai per Task
        $avgScores = DB::table('task_results')
            ->join('tasks', 'task_results.task_id', '=', 'tasks.id')
            ->select('tasks.judul', DB::raw('AVG(task_results.persentase_benar) as avg_score'))
            ->groupBy('tasks.id', 'tasks.judul')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        // 4. Tingkat Kelulusan Global
        $totalAttempts = $totalPengerjaan;
        $allResults = TaskResult::all();
        $passedAttempts = $allResults->filter(function ($result) {
            return $result->status === 'passed' || $result->persentase_benar >= 80;
        })->count();

        $accuracy = $totalAttempts ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0;
        $failedAttempts = $totalAttempts - $passedAttempts;

        // 5. Aktivitas Terbaru
        $recentActivities = TaskResult::with(['user', 'task'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'cards' => $cards,
            'online_users' => $onlineUsers,
            'avg_scores' => $avgScores,
            'total_attempts' => $totalAttempts,
            'passed_attempts' => $passedAttempts,
            'accuracy' => $accuracy,
            'failed_attempts' => $failedAttempts,
            'recent_activities' => $recentActivities,
        ]);
    }
}
