<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\CareerPathLevel;
use App\Models\Question;
use App\Models\TaskResult;
use App\Models\UserAnswer;
use App\Models\UserSurveyAnswer;
use App\Models\UserUploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TaskViewer extends Component
{
    use WithFileUploads;

    public $viewMode = 'index'; // 'index', 'show', 'result'

    // State Filter & Task
    public $filter_type = '';
    public $selectedTaskId = null;
    // ✅ FIX #1: HAPUS "public $task = null;"
    // Menyimpan Eloquent model secara langsung di Livewire public property
    // menyebabkan masalah serialisasi snapshot setelah relationship di-load.
    // Kita cukup simpan $selectedTaskId dan load task fresh di setiap render/action.

    // State Form Submit
    public $answers = [];
    public $file;

    public function render()
    {
        $user = Auth::user();

        if (!$user->id_badge) {
            session()->flash('error', 'Silakan lengkapi nomor ID Badge di profil Anda terlebih dahulu.');
            return view('livewire.user.task-viewer', ['tasks' => collect()])->layout('layouts.app');
        }

        $data = [];

        // ==========================================
        // MODE 1: DAFTAR TASK (INDEX)
        // ==========================================
        if ($this->viewMode === 'index') {
            $relevantJabatanIds = $this->getRelevantJabatanIds($user);

            if (empty($relevantJabatanIds)) {
                $data['error'] = 'Anda belum memiliki jabatan atau career path yang aktif.';
                $data['tasks'] = collect();
            } else {
                $query = Task::where(function ($q) use ($relevantJabatanIds) {
                    $q->whereIn('jabatan_id', $relevantJabatanIds)
                        ->orWhereNull('jabatan_id');
                })
                    ->where('status', 'published')
                    ->with(['jabatan.department'])
                    ->withCount('questions');

                if ($this->filter_type) {
                    $query->where('jenis_task', $this->filter_type);
                }

                $data['tasks'] = $query->latest()->get()->map(function ($task) use ($user) {
                    return $this->appendTaskStatus($task, $user);
                });
            }

            $data['approvalNotification'] = null;
            $recentUploads = UserUploadedFile::where('user_id', $user->id)
                ->where('is_approved', true)
                ->orderBy('approved_at', 'desc')
                ->limit(5)->get();

            foreach ($recentUploads as $upload) {
                $key = "upload_approved_{$user->id}_{$upload->id}";
                if (Cache::has($key)) {
                    $data['approvalNotification'] = Cache::get($key);
                    $data['cacheKey'] = $key;
                    break;
                }
            }
        }

        // ==========================================
        // MODE 2 & 3: KERJAKAN TASK & LIHAT HASIL
        // ==========================================
        else {
            // ✅ FIX #2: Load task FRESH dari database setiap render,
            // bukan dari $this->task yang bisa gagal ter-hidrasikan oleh Livewire.
            if (!$this->selectedTaskId) {
                $this->viewMode = 'index';
                $data['tasks'] = collect();
                $data['approvalNotification'] = null;
                return view('livewire.user.task-viewer', $data)->layout('layouts.app');
            }

            // ✅ FIX #3: Eager-load relasi yang dibutuhkan sekaligus,
            // hindari N+1 dan pastikan data siap tanpa caching di model property.
            $task = Task::with(['questions', 'surveyQuestions'])->find($this->selectedTaskId);

            // ✅ FIX #4: Null guard — jika task tidak ditemukan, redirect ke index
            if (!$task) {
                session()->flash('error', 'Tugas tidak ditemukan atau telah dihapus.');
                $this->viewMode = 'index';
                $this->selectedTaskId = null;
                $data['tasks'] = collect();
                $data['approvalNotification'] = null;
                return view('livewire.user.task-viewer', $data)->layout('layouts.app');
            }

            $data['task'] = $task;

            if ($this->viewMode === 'show') {
                if ($task->jenis_task === 'pilihan_ganda') {
                    $data['questions'] = $task->questions;
                } elseif ($task->jenis_task === 'survey') {
                    $data['surveyQuestions'] = $task->surveyQuestions;
                }
            } elseif ($this->viewMode === 'result') {
                if ($task->jenis_task === 'upload_file') {
                    $file = UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->first();
                    $data['uploadedFile'] = $file;
                    $data['hasPassed'] = $file && $file->is_approved;
                    $data['percentage'] = 100;
                } else {
                    $res = TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->first();
                    $data['taskResult'] = $res;
                    $data['percentage'] = $res ? $res->persentase_benar : 0;
                    $data['hasPassed'] = $res && ($res->status === 'passed' || $res->persentase_benar >= 80);

                    if ($task->jenis_task === 'pilihan_ganda') {
                        $data['userAnswers'] = UserAnswer::where('user_id', $user->id)
                            ->where('task_id', $task->id)
                            ->with('question')
                            ->get();
                    }
                }
            }
        }

        return view('livewire.user.task-viewer', $data)->layout('layouts.app');
    }

    // --- LOGIKA NAVIGASI SPA ---

    public function showTask($taskId)
    {
        try {
            $user = Auth::user();
            $task = Task::findOrFail($taskId);
            $this->selectedTaskId = $taskId;
            $this->resetForm();

            if ($this->checkIfPassed($user, $task)) {
                $this->viewMode = 'result';
                return;
            }

            $hasSubmitted = $this->checkIfSubmitted($user, $task);
            if ($hasSubmitted) {
                $this->viewMode = 'result';
                return;
            }

            $allowedJabatanIds = $this->getRelevantJabatanIds($user);
            if ($task->jabatan_id !== null && !in_array($task->jabatan_id, $allowedJabatanIds)) {
                session()->flash('error', 'Akses ditolak. Jabatan Anda belum memenuhi syarat.');
                $this->viewMode = 'index';
                return;
            }

            $this->viewMode = 'show';
        } catch (\Exception $e) {
            // ✅ Tampilkan error yang sebenarnya
            \Log::error('TaskViewer::showTask ERROR', [
                'taskId'  => $taskId,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);
            session()->flash('error', '[DEBUG] ' . $e->getMessage());
            $this->viewMode = 'index';
        }
    }

    public function backToIndex()
    {
        $this->viewMode = 'index';
        $this->selectedTaskId = null;
        $this->resetForm();
    }

    // --- LOGIKA SUBMIT TASK ---

    public function submitTask()
    {
        $user = Auth::user();

        // ✅ FIX #7: Load task fresh dari DB saat submit, bukan dari property
        $task = Task::find($this->selectedTaskId);

        if (!$task) {
            session()->flash('error', 'Tugas tidak ditemukan.');
            $this->viewMode = 'index';
            return;
        }

        $allowedJabatanIds = $this->getRelevantJabatanIds($user);
        if ($task->jabatan_id !== null && !in_array($task->jabatan_id, $allowedJabatanIds)) {
            session()->flash('error', 'Akses ditolak saat mencoba mengirim jawaban.');
            return;
        }

        if ($this->checkIfPassed($user, $task)) {
            $this->viewMode = 'result';
            return;
        }

        if ($task->jenis_task === 'pilihan_ganda') {
            $this->submitPilihanGanda($user, $task);
        } elseif ($task->jenis_task === 'survey') {
            $this->submitSurvey($user, $task);
        } elseif ($task->jenis_task === 'upload_file') {
            $this->submitUploadFile($user, $task);
        }
    }

    // ✅ FIX #8: Semua private submit method menerima $task sebagai parameter
    private function submitPilihanGanda($user, Task $task)
    {
        $this->validate(['answers' => 'required|array']);

        $totalQuestions = $task->questions->count();
        $correctAnswers = 0;

        UserAnswer::where('user_id', $user->id)->where('task_id', $task->id)->delete();

        foreach ($this->answers as $questionId => $answer) {
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
            session()->flash('success', "Selamat! Anda lulus dengan nilai {$percentage}%.");
        } else {
            session()->flash('failed', "Nilai: {$percentage}%. KKM: {$requiredScore}%. Coba lagi!");
        }

        $this->viewMode = 'result';
    }

    private function submitSurvey($user, Task $task)
    {
        $this->validate(['answers' => 'required|array']);

        foreach ($this->answers as $qId => $ans) {
            UserSurveyAnswer::updateOrCreate(
                ['user_id' => $user->id, 'task_id' => $task->id, 'survey_question_id' => $qId],
                ['answer' => (bool) $ans]
            );
        }

        TaskResult::updateOrCreate(
            ['user_id' => $user->id, 'task_id' => $task->id],
            ['total_skor' => 0, 'jumlah_soal' => count($this->answers), 'persentase_benar' => 100, 'status' => 'passed']
        );

        $this->processPassingReward($user, $task);
        session()->flash('success', 'Survei berhasil diselesaikan!');
        $this->viewMode = 'result';
    }

    private function submitUploadFile($user, Task $task)
    {
        $this->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'File dokumen wajib diunggah.',
            'file.max'      => 'Ukuran file tidak boleh melebihi 10MB.',
        ]);

        $path = $this->file->store('task-uploads', 'public');

        UserUploadedFile::updateOrCreate(
            ['user_id' => $user->id, 'task_id' => $task->id],
            ['file_path' => $path, 'is_approved' => false]
        );

        session()->flash('info', 'Dokumen berhasil diunggah dan sedang menunggu validasi.');
        $this->viewMode = 'result';
    }

    // --- HELPER LOGIC ---

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

    private function getRelevantJabatanIds($user)
    {
        $ids = [];
        if ($user->jabatan_id) $ids[] = $user->jabatan_id;

        foreach ($user->careerPaths as $path) {
            $levelModel = CareerPathLevel::where('career_path_id', $path->id)
                ->where('level', $path->pivot->current_level)
                ->first();
            if ($levelModel && $levelModel->jabatan_id) {
                $ids[] = $levelModel->jabatan_id;
            }
        }

        return array_unique($ids);
    }

    private function appendTaskStatus($task, $user)
    {
        $statusColor = 'available';

        if ($task->jenis_task === 'upload_file') {
            $upload = UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->first();
            if ($upload) {
                $statusColor = $upload->is_approved ? 'completed' : 'pending_review';
            }
        } else {
            $result = TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->first();
            if ($result) {
                if ($task->jenis_task === 'survey') {
                    $statusColor = 'completed';
                } else {
                    $statusColor = ($result->status === 'passed' || $result->persentase_benar >= 80) ? 'completed' : 'failed';
                }
            }
        }

        if ($statusColor === 'available' && $task->deadline && Carbon::now()->greaterThan($task->deadline)) {
            $statusColor = 'overdue';
        }

        $task->status_color = $statusColor;
        return $task;
    }

    private function checkIfSubmitted($user, Task $task)
    {
        if ($task->jenis_task === 'upload_file') {
            return UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->exists();
        }
        return TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->exists();
    }

    private function checkIfPassed($user, Task $task)
    {
        if ($task->jenis_task === 'upload_file') {
            $file = UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->first();
            return $file && $file->is_approved;
        }
        $result = TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->first();
        return $result && ($result->status === 'passed' || $result->persentase_benar >= 80);
    }

    private function resetForm()
    {
        $this->answers = [];
        $this->file    = null;
        $this->resetErrorBag();
    }
}
