<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\UserSurveyAnswer;
use App\Models\UserUploadedFile;
use App\Models\TaskResult;
use App\Models\CareerPathLevel;

class TaskApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (empty($user->id_badge)) {
            return response()->json([
                'status' => 'success',
                'requires_badge' => true,
                'tasks' => []
            ]);
        }

        // ─── 1. Tentukan jabatan_id yang relevan untuk user ini ────────────────
        // Ambil semua career path yang SEDANG DIIKUTI (aktif) oleh user,
        // beserta current_level masing-masing dari pivot.
        $activeCareerPaths = $user->careerPaths()->wherePivot('is_active', true)->get();

        $relevantJabatanIds = collect();

        foreach ($activeCareerPaths as $path) {
            $currentLevel = $path->pivot->current_level ?? 1;

            // Ambil jabatan_id untuk level SAAT INI dan level BERIKUTNYA saja
            $levelJabatanIds = CareerPathLevel::where('career_path_id', $path->id)
                ->whereIn('level', [$currentLevel, $currentLevel + 1])
                ->pluck('jabatan_id');

            $relevantJabatanIds = $relevantJabatanIds->merge($levelJabatanIds);
        }

        $relevantJabatanIds = $relevantJabatanIds->unique()->filter()->values();

        // ─── 2. Ambil task, filter berdasarkan jabatan_id ───────────────────────
        $tasks = Task::with(['questions', 'surveyQuestions'])
            ->where(function ($query) use ($relevantJabatanIds) {
                $query->whereNull('jabatan_id') // task umum, selalu tampil
                    ->orWhereIn('jabatan_id', $relevantJabatanIds); // task sesuai jabatan level saat ini/berikutnya
            })
            ->latest()
            ->get();

        // 3. AMBIL RIWAYAT PENGERJAAN USER SAAT INI
        $taskResults = TaskResult::where('user_id', $user->id)->get()->keyBy('task_id');
        $uploadedFiles = \App\Models\UserUploadedFile::where('user_id', $user->id)->get()->keyBy('task_id');

        $formattedTasks = $tasks->map(function ($task) use ($user, $taskResults, $uploadedFiles) {

            $progressStatus = 'available';

            if ($task->jenis_task === 'upload_file') {
                if ($uploadedFiles->has($task->id)) {
                    $file = $uploadedFiles->get($task->id);
                    $progressStatus = $file->is_approved ? 'completed' : 'pending_approval';
                }
            } else {
                if ($taskResults->has($task->id)) {
                    $result = $taskResults->get($task->id);
                    $progressStatus = ($result->status === 'passed') ? 'completed' : 'failed';
                }
            }

            return [
                'id' => $task->id,
                'judul' => $task->judul,
                'deskripsi' => $task->deskripsi,
                'jenis_task' => $task->jenis_task,
                'exp_reward' => $task->exp_reward,
                'deadline' => $task->deadline,
                'status_color' => $task->status_color ?? 'available',
                'user_progress_status' => $progressStatus,
                'questions' => $task->questions,
                'survey_questions' => $task->surveyQuestions,
            ];
        });

        return response()->json([
            'status' => 'success',
            'requires_badge' => false,
            'tasks' => $formattedTasks
        ]);
    }

    public function submitQuiz(Request $request, $taskId)
    {
        $user = $request->user();

        $request->validate([
            'answers' => 'required|array',
        ]);

        $task = Task::find($taskId);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        if ($task->jenis_task !== 'pilihan_ganda') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas ini bukan jenis pilihan ganda.',
            ], 422);
        }

        // Cek apakah sudah lulus sebelumnya
        $existingResult = TaskResult::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        if ($existingResult && ($existingResult->status === 'passed' || $existingResult->persentase_benar >= 80)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Anda sudah lulus tugas ini sebelumnya.',
                'percentage' => $existingResult->persentase_benar,
            ]);
        }

        $totalQuestions = $task->questions()->count();
        $correctAnswers = 0;

        UserAnswer::where('user_id', $user->id)->where('task_id', $task->id)->delete();

        foreach ($request->answers as $questionId => $answer) {
            $question = Question::find($questionId);
            if (!$question || $question->task_id !== $task->id) continue;

            $isCorrect = strtolower($answer) === strtolower($question->jawaban_benar);
            $point = $isCorrect ? ($question->skor ?? 0) : 0;

            UserAnswer::create([
                'user_id'     => $user->id,
                'task_id'     => $task->id,
                'question_id' => $questionId,
                'answer'      => $answer,
                'is_correct'  => $isCorrect,
                'score'       => $point,
            ]);

            if ($isCorrect) $correctAnswers++;
        }

        $percentage    = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 1) : 0;
        $requiredScore = 80;
        $status        = ($percentage >= $requiredScore) ? 'passed' : 'failed';

        TaskResult::updateOrCreate(
            ['user_id' => $user->id, 'task_id' => $task->id],
            [
                'total_skor'       => $correctAnswers,
                'jumlah_soal'      => $totalQuestions,
                'persentase_benar' => $percentage,
                'status'           => $status,
            ]
        );

        if ($status === 'passed') {
            $this->processPassingReward($user, $task);
        }

        return response()->json([
            'status' => 'success',
            'percentage' => $percentage,
            'result_status' => $status,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
        ]);
    }

    // ==========================================
    // SUBMIT SURVEY
    // ==========================================
    public function submitSurvey(Request $request, $taskId)
    {
        $user = $request->user();

        $request->validate([
            'answers' => 'required|array',
        ]);

        $task = Task::find($taskId);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        if ($task->jenis_task !== 'survey') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas ini bukan jenis survey.',
            ], 422);
        }

        foreach ($request->answers as $qId => $ans) {
            UserSurveyAnswer::updateOrCreate(
                ['user_id' => $user->id, 'task_id' => $task->id, 'survey_question_id' => $qId],
                ['answer' => (bool) $ans]
            );
        }

        TaskResult::updateOrCreate(
            ['user_id' => $user->id, 'task_id' => $task->id],
            [
                'total_skor'       => 0,
                'jumlah_soal'      => count($request->answers),
                'persentase_benar' => 100,
                'status'           => 'passed',
            ]
        );

        $this->processPassingReward($user, $task);

        return response()->json([
            'status' => 'success',
            'message' => 'Survei berhasil diselesaikan!',
            'result_status' => 'passed', // 👈 Tambahkan ini agar isSuccess = true
            'percentage' => 100,         // 👈 Jaga-jaga jika frontend butuh angka
        ]);
    }

    // ==========================================
    // SUBMIT UPLOAD FILE
    // ==========================================
    public function submitUploadFile(Request $request, $taskId)
    {
        $user = $request->user();

        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'File dokumen wajib diunggah.',
            'file.max'      => 'Ukuran file tidak boleh melebihi 10MB.',
        ]);

        $task = Task::find($taskId);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        if ($task->jenis_task !== 'upload_file') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas ini bukan jenis upload file.',
            ], 422);
        }

        $path = $request->file('file')->store('task-uploads', 'public');

        UserUploadedFile::updateOrCreate(
            ['user_id' => $user->id, 'task_id' => $task->id],
            ['file_path' => $path, 'is_approved' => false]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil diunggah dan sedang menunggu validasi.',
            'result_status' => 'pending', // 👈 Tambahkan ini agar isPending = true di frontend
        ]);
    }

    // Diporting dari TaskViewer.php supaya logic EXP/level up konsisten
    private function processPassingReward($user, Task $task)
    {
        if ($task->jabatan_id === null) {
            foreach ($user->careerPaths as $userPath) {
                $newExp = $userPath->pivot->total_exp + $task->exp_reward;
                $user->careerPaths()->updateExistingPivot($userPath->id, ['total_exp' => $newExp]);
            }
            return;
        }

        $relevantPathIds = CareerPathLevel::where('jabatan_id', $task->jabatan_id)
            ->pluck('career_path_id')
            ->toArray();

        foreach ($user->careerPaths as $userPath) {
            if (in_array($userPath->id, $relevantPathIds)) {
                $newExp       = $userPath->pivot->total_exp + $task->exp_reward;
                $currentLevel = $userPath->pivot->current_level;

                $nextLevelModel = CareerPathLevel::where('career_path_id', $userPath->id)
                    ->where('level', '>', $currentLevel)
                    ->where('required_exp', '<=', $newExp)
                    ->orderBy('level', 'desc')
                    ->first();

                $updateData = ['total_exp' => $newExp];

                if ($nextLevelModel) {
                    $updateData['current_level'] = $nextLevelModel->level;
                    if ($user->career_path_id == $userPath->id) {
                        $user->update(['jabatan_id' => $nextLevelModel->jabatan_id]);
                    }
                }

                $user->careerPaths()->updateExistingPivot($userPath->id, $updateData);
            }
        }
    }
}
