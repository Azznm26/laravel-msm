<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\UserCareerProgress;
use Illuminate\Http\Request;

class TaskSubmissionController extends Controller
{
    public function store(Request $request, Task $task)
    {
        // 1. Hitung skor dari jawaban (sesuaikan logika Anda)
        $total = $task->total_skor;
        $score = $this->calculateScoreFromRequest($request, $task); // Anda sesuaikan

        // 2. Cek kelulusan (minimal 80%)
        $isPassed = ($score >= $total * 0.8);

        // 3. Simpan submission
        $submission = TaskSubmission::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'score_obtained' => $score,
            'total_possible' => $total,
            'is_passed' => $isPassed,
            'submitted_at' => now(),
        ]);

        // 4. Beri EXP hanya jika lulus
        if ($isPassed) {
            $progress = UserCareerProgress::firstOrCreate(
                ['user_id' => auth()->id(), 'jabatan_id' => auth()->user()->jabatan_id],
                ['current_level' => 1, 'total_score' => 0]
            );
            $progress->increment('total_score', $task->exp_reward);
        }

        // 5. Redirect dengan pesan
        if ($isPassed) {
            return redirect()->route('user.tasks.index')
                ->with('success', 'Task berhasil diselesaikan! Anda mendapat ' . $task->exp_reward . ' EXP.');
        } else {
            return back()->with('error', 'Nilai Anda kurang dari 80%. Silakan ulangi task ini.');
        }
    }

    // Sesuaikan logika ini sesuai jenis task Anda
    private function calculateScoreFromRequest(Request $request, Task $task)
    {
        if ($task->jenis_task !== 'pilihan_ganda') {
            return $task->total_skor; // asumsi langsung lulus
        }

        $score = 0;
        foreach ($task->questions as $q) {
            $userAnswer = $request->input("question_{$q->id}");
            if ($userAnswer === $q->jawaban_benar) {
                $score += $q->skor ?? $q->point_value ?? 1;
            }
        }
        return $score;
    }
}
