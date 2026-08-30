<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Task;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\CareerPath;
use App\Models\CareerPathLevel;
use App\Models\TaskResult;
use App\Models\UserUploadedFile;
use App\Models\UserAnswer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UserSurveyAnswer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TaskMonitoringManager extends Component
{
    use WithPagination;

    // Mode SPA
    public $viewMode = 'list';

    // Filters & Sorting
    public $search = '';
    public $departmentId = '';
    public $jabatanId = '';
    public $careerPathId = '';
    public $jenisTask = '';
    public $statusPengerjaan = '';
    public $sortBy = 'date';
    public $sortOrder = 'desc';

    // State Master Data
    public $departments = [];
    public $availableJabatans = [];
    public $careerPaths = [];

    // State Detail
    public $detailData = [];

    public function mount()
    {
        $this->departments = Department::orderBy('nama_department')->get();
        $this->careerPaths = CareerPath::orderBy('name')->get();
        $this->availableJabatans = Jabatan::orderBy('nama_jabatan')->get(); // Default load semua
    }

    // ==========================================
    // REACTIVE FILTERS
    // ==========================================
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedDepartmentId()
    {
        $this->resetPage();
        $this->jabatanId = '';
        $this->availableJabatans = $this->departmentId
            ? Jabatan::where('department_id', $this->departmentId)->orderBy('nama_jabatan')->get()
            : Jabatan::orderBy('nama_jabatan')->get();
    }
    public function updatedJabatanId()
    {
        $this->resetPage();
    }
    public function updatedCareerPathId()
    {
        $this->resetPage();
    }
    public function updatedJenisTask()
    {
        $this->resetPage();
    }
    public function updatedStatusPengerjaan()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'asc';
        }
    }

    public function render()
    {
        $monitoringData = collect($this->getMonitoringData());

        // Pagination Manual
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $monitoringData->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedData = new LengthAwarePaginator(
            $currentItems,
            $monitoringData->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        return view('livewire.admin.task-monitoring-manager', compact('paginatedData'))
            ->layout('layouts.admin');
    }

    // ==========================================
    // LOGIKA DETAIL SHOW
    // ==========================================
    public function showDetail($userId, $taskId)
    {
        $user = User::findOrFail($userId);
        $task = Task::with(['questions', 'surveyQuestions'])->findOrFail($taskId);

        $this->detailData = [
            'user' => $user,
            'task' => $task,
            'status' => 'Belum Dikerjakan',
            'score' => 0,
            'raw_score' => 0,
            'total_soal' => 0,
            'detail' => null,
            'uploaded_at' => null,
            'answers' => null,
            'surveyAnswers' => null,
        ];

        if ($task->jenis_task === 'upload_file') {
            $upload = UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->first();
            if ($upload) {
                $this->detailData['status'] = $upload->is_approved ? 'Disetujui' : 'Menunggu Approval';
                $this->detailData['detail'] = $upload;
                $this->detailData['uploaded_at'] = $upload->created_at;
            }
        } else {
            $result = TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->first();
            if ($result) {
                $totalSoal = $task->questions_count;
                $benar = $result->total_skor;
                $this->detailData['detail'] = $result;
                $this->detailData['uploaded_at'] = $result->created_at;

                if ($task->jenis_task === 'survey') {
                    $this->detailData['status'] = 'Selesai';
                    $this->detailData['score'] = 100;
                    $this->detailData['surveyAnswers'] = UserSurveyAnswer::with('surveyQuestion')
                        ->where('user_id', $user->id)->where('task_id', $task->id)->get();
                } else {
                    $persentase = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 1) : 0;
                    $this->detailData['status'] = $persentase >= 80 ? 'Lulus' : 'Gagal';
                    $this->detailData['score'] = $persentase;
                    $this->detailData['raw_score'] = $benar;
                    $this->detailData['total_soal'] = $totalSoal;
                    $this->detailData['answers'] = UserAnswer::with('question')
                        ->where('user_id', $user->id)->where('task_id', $task->id)->get();
                }
            }
        }

        $this->viewMode = 'detail';
    }

    public function backToList()
    {
        $this->viewMode = 'list';
        $this->detailData = [];
    }

    // ==========================================
    // LOGIKA APPROVE & REJECT FILE
    // ==========================================
    public function approveUpload($uploadId)
    {
        $upload = UserUploadedFile::findOrFail($uploadId);

        if ($upload->is_approved) {
            session()->flash('error', 'Task ini sudah disetujui sebelumnya.');
            return;
        }

        $upload->forceFill([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ])->save();

        $this->grantExpAndCheckLevel($upload->user, $upload->task);

        session()->flash('success', 'File berhasil disetujui dan EXP telah diberikan.');
        $this->showDetail($upload->user_id, $upload->task_id); // Refresh Detail
    }

    public function rejectUpload($uploadId)
    {
        $upload = UserUploadedFile::findOrFail($uploadId);
        $userId = $upload->user_id;
        $taskId = $upload->task_id;

        if ($upload->file_path && Storage::disk('public')->exists($upload->file_path)) {
            Storage::disk('public')->delete($upload->file_path);
        }

        $upload->delete();
        session()->flash('success', 'File ditolak dan dihapus. User harus mengunggah ulang.');
        $this->showDetail($userId, $taskId); // Refresh Detail
    }

    // PERBAIKAN LOGIKA EXP (Menggunakan Pivot Table)
    private function grantExpAndCheckLevel($user, $task)
    {
        // Ambil Primary Path user
        $primaryPathId = $user->career_path_id;
        if (!$primaryPathId) return;

        // Cari data Pivot-nya
        $pivotRecord = $user->careerPaths()->where('career_path_id', $primaryPathId)->first();
        if ($pivotRecord) {
            $newTotalExp = $pivotRecord->pivot->total_exp + $task->exp_reward;

            // Update EXP di pivot table
            $user->careerPaths()->updateExistingPivot($primaryPathId, ['total_exp' => $newTotalExp]);

            // Cek Level Tertinggi yang bisa dicapai dengan EXP baru ini
            $highestLevel = CareerPathLevel::where('career_path_id', $primaryPathId)
                ->where('exp_required', '<=', $newTotalExp)
                ->orderBy('level', 'desc')
                ->first();

            // Jika naik level
            if ($highestLevel && $highestLevel->level > $pivotRecord->pivot->current_level) {
                $user->update(['jabatan_id' => $highestLevel->jabatan_id]); // Ganti jabatan aktual
                $user->careerPaths()->updateExistingPivot($primaryPathId, ['current_level' => $highestLevel->level]); // Update level pivot
            }
        }
    }

    // ==========================================
    // DATA GENERATOR & EXPORT
    // ==========================================
    private function getMonitoringData()
    {
        $usersQuery = User::with(['department', 'jabatan', 'careerPath'])
            ->where('role', 'user')->whereNotNull('jabatan_id')
            ->when($this->departmentId, fn($q) => $q->where('department_id', $this->departmentId))
            ->when($this->careerPathId, fn($q) => $q->where('career_path_id', $this->careerPathId))
            ->when($this->jabatanId, fn($q) => $q->where('jabatan_id', $this->jabatanId))
            ->when($this->search, fn($q) => $q->where(fn($sub) => $sub->where('name', 'like', "%{$this->search}%")->orWhere('id_badge', 'like', "%{$this->search}%")));

        $users = $usersQuery->get();
        $jabatanIds = $users->pluck('jabatan_id')->unique();

        $tasks = Task::whereIn('jabatan_id', $jabatanIds)->withCount('questions')->get();
        $monitoringData = [];

        foreach ($users as $user) {
            $userTasks = $tasks->where('jabatan_id', $user->jabatan_id);
            if ($this->jenisTask) {
                $userTasks = $userTasks->where('jenis_task', $this->jenisTask);
            }

            foreach ($userTasks as $task) {
                $status = 'Belum Dikerjakan';
                $scoreOrNote = '-';
                $submittedAt = null;
                $rawStatus = 'empty';
                $badgeColor = 'bg-gray-100 text-gray-500';

                if ($task->jenis_task === 'upload_file') {
                    $upload = UserUploadedFile::where('user_id', $user->id)->where('task_id', $task->id)->first();
                    if ($upload) {
                        $submittedAt = $upload->created_at;
                        $scoreOrNote = 'File Terupload';
                        if ($upload->is_approved) {
                            $status = 'Disetujui';
                            $rawStatus = 'approved';
                            $badgeColor = 'bg-emerald-100 text-emerald-700';
                        } else {
                            $status = 'Menunggu Approval';
                            $rawStatus = 'pending';
                            $badgeColor = 'bg-amber-100 text-amber-700';
                        }
                    }
                } else {
                    $result = TaskResult::where('user_id', $user->id)->where('task_id', $task->id)->first();
                    if ($result) {
                        $submittedAt = $result->created_at;
                        $rawStatus = $result->status;
                        if ($task->jenis_task == 'survey') {
                            $status = 'Selesai';
                            $scoreOrNote = 'Survey Terisi';
                            $badgeColor = 'bg-emerald-100 text-emerald-700';
                            $rawStatus = 'passed';
                        } else {
                            $benar = $result->total_skor ?? 0;
                            $persentase = $task->questions_count > 0 ? round(($benar / $task->questions_count) * 100, 1) : 0;
                            $scoreOrNote = $persentase . '%';
                            if ($persentase >= 80) {
                                $status = 'Lulus';
                                $badgeColor = 'bg-emerald-100 text-emerald-700';
                            } else {
                                $status = 'Gagal';
                                $badgeColor = 'bg-rose-100 text-rose-700';
                            }
                        }
                    }
                }

                if ($this->statusPengerjaan) {
                    if ($this->statusPengerjaan == 'done' && $rawStatus == 'empty') continue;
                    if ($this->statusPengerjaan == 'pending' && $rawStatus != 'empty') continue;
                }

                $monitoringData[] = [
                    'user' => $user,
                    'task' => $task,
                    'status' => $status,
                    'raw_status' => $rawStatus,
                    'badge_color' => $badgeColor,
                    'score_or_note' => $scoreOrNote,
                    'submitted_at' => $submittedAt,
                    'career_path_name' => $user->careerPath->name ?? '-'
                ];
            }
        }

        $collection = collect($monitoringData);
        if ($this->sortBy == 'date') {
            return $this->sortOrder == 'asc' ? $collection->sortBy('submitted_at') : $collection->sortByDesc('submitted_at');
        } elseif ($this->sortBy == 'name') {
            return $this->sortOrder == 'asc' ? $collection->sortBy('user.name') : $collection->sortByDesc('user.name');
        }
        return $collection;
    }

    public function exportPdf()
    {
        $data = $this->getMonitoringData();

        $filename = "task-monitoring-" . date('Y-m-d') . ".pdf";

        $pdf = Pdf::loadView('pdf.task-monitoring', [
            'taskData' => $data
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function exportExcel()
    {
        $data = $this->getMonitoringData();
        $filename = "task-monitoring-" . date('Y-m-d') . ".csv";

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama User', 'Departemen', 'Jabatan', 'Task', 'Jenis', 'Status', 'Nilai (%)', 'Waktu']);

            $no = 1;
            foreach ($data as $row) {
                fputcsv($handle, [
                    $no++,
                    $row['user']->name,
                    $row['user']->department->nama_department ?? '-',
                    $row['user']->jabatan->nama_jabatan ?? '-',
                    $row['task']->judul,
                    $row['task']->jenis_task,
                    $row['status'],
                    $row['score_or_note'],
                    $row['submitted_at'] ? $row['submitted_at']->format('d M Y H:i') : '-'
                ]);
            }
            fclose($handle);
        }, $filename);
    }
}
