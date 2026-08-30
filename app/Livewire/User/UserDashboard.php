<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Task;
use App\Models\CareerPathLevel;
use App\Models\UserUploadedFile;
use App\Models\TaskResult;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $relevantJabatanIds = [];

        // 1. Ambil Jabatan Utama (Primary)
        if ($user->jabatan_id) {
            $relevantJabatanIds[] = $user->jabatan_id;
        }

        // 2. Ambil Jabatan dari Career Path Lain (Secondary)
        foreach ($user->careerPaths as $path) {
            $currentLevel = $path->pivot->current_level;
            $levelModel = CareerPathLevel::where('career_path_id', $path->id)
                ->where('level', $currentLevel)
                ->first();

            if ($levelModel && $levelModel->jabatan_id) {
                $relevantJabatanIds[] = $levelModel->jabatan_id;
            }
        }
        $relevantJabatanIds = array_unique($relevantJabatanIds);

        $data = [
            'totalXp' => 0,
            'primaryPath' => null,
            'currentLevelName' => 'N/A',
            'taskCount' => 0,
            'completedCount' => 0,
            'pendingCount' => 0,
            'recentTasks' => collect(),
            'error' => null
        ];

        // 3. Jika belum punya jabatan/path, kembalikan tampilan kosong dengan error
        if (empty($relevantJabatanIds)) {
            $data['error'] = 'Akun Anda belum dipetakan ke Jalur Karir manapun oleh Admin.';
            return view('livewire.user.user-dashboard', $data)->layout('layouts.app');
        }

        // 4. Hitung XP & Primary Path
        $data['totalXp'] = $user->careerPaths()->sum('career_path_user.total_exp');

        $primaryPath = $user->careerPaths()->where('career_path_id', $user->career_path_id)->first()
            ?? $user->careerPaths->first();

        if ($primaryPath) {
            $data['primaryPath'] = $primaryPath;
            $lvl = CareerPathLevel::where('career_path_id', $primaryPath->id)
                ->where('level', $primaryPath->pivot->current_level)
                ->first();
            if ($lvl) $data['currentLevelName'] = $lvl->name;
        }

        // 5. Hitung Task (Tugas)
        $allTasks = Task::whereIn('jabatan_id', $relevantJabatanIds)
            ->where('status', 'published')
            ->latest()
            ->get();

        $data['taskCount'] = $allTasks->count();
        $processedTasks = collect();

        foreach ($allTasks as $task) {
            $isCompleted = false;
            $statusLabel = 'Belum Dikerjakan';
            $statusColor = 'amber';

            // Cek Upload
            if ($task->jenis_task == 'upload_file') {
                $upload = UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->first();
                if ($upload) {
                    if ($upload->is_approved) {
                        $isCompleted = true;
                        $statusLabel = 'Selesai';
                        $statusColor = 'emerald';
                    } else {
                        $statusLabel = 'Menunggu Validasi';
                        $statusColor = 'blue';
                    }
                }
            } else {
                // Cek Quiz / Survey
                $result = TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->first();
                if ($result) {
                    if ($result->status == 'passed' || $result->persentase_benar >= 80 || $task->jenis_task == 'survey') {
                        $isCompleted = true;
                        $statusLabel = 'Selesai';
                        $statusColor = 'emerald';
                    } else {
                        $statusLabel = 'Gagal (Coba Lagi)';
                        $statusColor = 'rose';
                    }
                }
            }

            if ($isCompleted) {
                $data['completedCount']++;
            } else {
                $data['pendingCount']++;
            }

            // Simpan status ke object task (hanya untuk 5 task terbaru yang ditampilkan)
            if ($processedTasks->count() < 5) {
                $task->my_status = $statusLabel;
                $task->my_color = $statusColor;
                $processedTasks->push($task);
            }
        }

        $data['recentTasks'] = $processedTasks;

        return view('livewire.user.user-dashboard', $data)->layout('layouts.app');
    }
}
