<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\CareerPathLevel;
use App\Models\UserUploadedFile;
use App\Models\TaskResult;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // -----------------------------------------------------------
        // 1. LOGIC MULTI-PATH: Kumpulkan Semua Jabatan ID User
        // -----------------------------------------------------------
        $relevantJabatanIds = [];

        // A. Masukkan Jabatan Utama (Primary)
        if ($user->jabatan_id) {
            $relevantJabatanIds[] = $user->jabatan_id;
        }

        // B. Masukkan Jabatan dari Career Path Lain (Secondary)
        // Loop semua path yang ada di tabel pivot
        foreach ($user->careerPaths as $path) {
            // Ambil level saat ini dari pivot
            $currentLevel = $path->pivot->current_level;

            // Cari Jabatan yang sesuai dengan Level & Path tersebut
            $levelModel = CareerPathLevel::where('career_path_id', $path->id)
                ->where('level', $currentLevel)
                ->first();

            if ($levelModel && $levelModel->jabatan_id) {
                $relevantJabatanIds[] = $levelModel->jabatan_id;
            }
        }

        // Hapus duplikasi
        $relevantJabatanIds = array_unique($relevantJabatanIds);

        // Jika tidak ada jabatan sama sekali
        if (empty($relevantJabatanIds)) {
            return view('user.dashboard.index', [
                'taskCount' => 0,
                'recentTasks' => collect(),
                'primaryPath' => null,
                'totalXp' => 0,
                'error' => 'Jabatan atau Career Path belum ditetapkan.'
            ]);
        }

        // -----------------------------------------------------------
        // 2. QUERY TASK (Gunakan WHERE IN)
        // -----------------------------------------------------------
        $tasksQuery = Task::whereIn('jabatan_id', $relevantJabatanIds)
            ->where('status', 'published')
            ->latest();

        $allTasks = $tasksQuery->get(); // Ambil semua untuk hitung progress

        // -----------------------------------------------------------
        // 3. HITUNG STATUS TASK (Untuk Widget Dashboard)
        // -----------------------------------------------------------
        $completedCount = 0;
        $pendingCount = 0;

        foreach ($allTasks as $task) {
            // Cek status penyelesaian (Logic sederhana)
            $isCompleted = false;

            if ($task->jenis_task == 'upload_file') {
                $isCompleted = UserUploadedFile::where('user_id', $user->id)
                    ->where('task_id', $task->id)
                    ->where('is_approved', true)
                    ->exists();
            } else {
                $result = TaskResult::where('user_id', $user->id)
                    ->where('task_id', $task->id)
                    ->first();
                // Anggap selesai jika passed atau nilai >= 80
                if ($result && ($result->status == 'passed' || $result->persentase_benar >= 80)) {
                    $isCompleted = true;
                }
            }

            if ($isCompleted) {
                $completedCount++;
            } else {
                $pendingCount++;
            }
        }

        // -----------------------------------------------------------
        // 4. DATA TAMBAHAN (XP & Primary Path)
        // -----------------------------------------------------------

        // Total XP dari semua jalur
        $totalXp = $user->careerPaths()->sum('career_path_user.total_exp');

        // Ambil Primary Path Object (untuk ditampilkan nama Level Utamanya)
        $primaryPath = $user->careerPaths()
            ->where('career_path_id', $user->career_path_id)
            ->first();

        // Fallback: Jika primary path di user table null, ambil path pertama di pivot
        if (!$primaryPath && $user->careerPaths->isNotEmpty()) {
            $primaryPath = $user->careerPaths->first();
        }

        // Primary Level Name (Ambil nama level saat ini)
        $currentLevelName = 'N/A';
        if ($primaryPath) {
            $lvl = CareerPathLevel::where('career_path_id', $primaryPath->id)
                ->where('level', $primaryPath->pivot->current_level)
                ->first();
            if ($lvl) $currentLevelName = $lvl->name;
        }

        return view('user.dashboard.index', [
            'taskCount' => $allTasks->count(),
            'recentTasks' => $allTasks->take(5), // Tampilkan 5 task terbaru
            'completedCount' => $completedCount,
            'pendingCount' => $pendingCount,
            'totalXp' => $totalXp,
            'primaryPath' => $primaryPath,
            'currentLevelName' => $currentLevelName
        ]);
    }
}
