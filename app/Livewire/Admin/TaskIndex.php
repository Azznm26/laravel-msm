<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Task;
use App\Models\Jabatan;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class TaskIndex extends Component
{
    // Mode Navigasi SPA: 'departments', 'jabatans', 'tasks', 'form', 'questions'
    public $viewMode = 'departments';

    // State Navigasi
    public $selectedDepartmentId = null;
    public $selectedDepartmentName = '';
    public $selectedJabatanId = null;
    public $selectedJabatanName = '';

    // ✅ BARU: State untuk melihat daftar pertanyaan
    public $viewingTaskId = null;

    // state pencarian per tahap
    public $searchDepartment = '';
    public $searchJabatan = '';
    public $searchTask = '';

    // State Form Task
    public $taskId = null;
    public $judul, $deskripsi, $jenis_task = 'pilihan_ganda', $status = 'draft', $deadline, $exp_reward;
    public $jabatan_id;

    // Data Dropdown
    public $jabatansForForm = [];

    protected function rules()
    {
        $deadlineRule = $this->taskId ? 'nullable|date' : 'nullable|date|after_or_equal:now';

        return [
            'judul'      => 'required|string|max:255',
            'deskripsi'  => 'required|string',
            'status'     => 'required|in:draft,published',
            'deadline'   => $deadlineRule,
            'jenis_task' => 'required|in:pilihan_ganda,upload_file,survey',
            'jabatan_id' => 'required|exists:jabatans,id',
            'exp_reward' => 'required|integer|min:1|max:10000',
        ];
    }

    protected $messages = [
        'deadline.after_or_equal' => 'Deadline tidak boleh waktu yang sudah lewat.',
        'exp_reward.min' => 'EXP Reward minimal 1.',
    ];

    public function updated($propertyName)
    {
        // Jangan validasi field pencarian
        if (in_array($propertyName, ['searchDepartment', 'searchJabatan', 'searchTask'])) {
            return;
        }
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        $data = [];

        if ($this->viewMode === 'departments') {
            $data['departments'] = Department::select([
                'id',
                'nama_department',
                DB::raw('(SELECT COUNT(*) FROM tasks 
                          WHERE tasks.jabatan_id IN (
                              SELECT id FROM jabatans 
                              WHERE jabatans.department_id = departments.id
                          )) as tasks_count')
            ])
                ->when($this->searchDepartment, function ($q) {
                    $q->where('nama_department', 'like', '%' . $this->searchDepartment . '%');
                })
                ->orderBy('nama_department')
                ->get();
        } elseif ($this->viewMode === 'jabatans') {
            $data['jabatans'] = Jabatan::where('department_id', $this->selectedDepartmentId)
                ->when($this->searchJabatan, function ($q) {
                    $q->where('nama_jabatan', 'like', '%' . $this->searchJabatan . '%');
                })
                ->withCount('tasks')
                ->orderBy('nama_jabatan')
                ->get();
        } elseif ($this->viewMode === 'tasks') {
            $data['tasks'] = Task::where('jabatan_id', $this->selectedJabatanId)
                ->when($this->searchTask, function ($q) {
                    $q->where('judul', 'like', '%' . $this->searchTask . '%');
                })
                ->withCount('questions')
                ->latest()
                ->get();
        }
        // ✅ BARU: Mode untuk melihat daftar pertanyaan
        elseif ($this->viewMode === 'questions') {
            // Eager load relasi questions dan surveyQuestions sekaligus
            $data['viewedTask'] = Task::with(['questions', 'surveyQuestions'])->findOrFail($this->viewingTaskId);
        }

        return view('livewire.admin.task-index', $data)->layout('layouts.admin');
    }

    // --- LOGIKA NAVIGASI ---

    public function selectDepartment($id, $name)
    {
        $this->selectedDepartmentId = $id;
        $this->selectedDepartmentName = $name;
        $this->searchJabatan = '';
        $this->viewMode = 'jabatans';
    }

    public function selectJabatan($id, $name)
    {
        $this->selectedJabatanId = $id;
        $this->selectedJabatanName = $name;
        $this->searchTask = '';
        $this->viewMode = 'tasks';
    }

    public function backToDepartments()
    {
        $this->viewMode = 'departments';
        $this->selectedDepartmentId = null;
        $this->selectedDepartmentName = '';
        $this->selectedJabatanId = null;
        $this->selectedJabatanName = '';
        $this->searchJabatan = '';
        $this->searchTask = '';
    }

    public function backToJabatans()
    {
        $this->viewMode = 'jabatans';
        $this->selectedJabatanId = null;
        $this->selectedJabatanName = '';
        $this->searchTask = '';
    }

    public function backToTasks()
    {
        $this->viewMode = 'tasks';
        $this->viewingTaskId = null; // ✅ Bersihkan state saat kembali ke daftar task
    }

    // ✅ BARU: Method untuk beralih ke mode lihat pertanyaan
    public function viewQuestions($id)
    {
        $this->viewingTaskId = $id;
        $this->viewMode = 'questions';
    }

    // --- LOGIKA CRUD TASK ---

    public function create()
    {
        $this->resetInputFields();
        $this->jabatan_id = $this->selectedJabatanId;
        $this->jabatansForForm = Jabatan::where('department_id', $this->selectedDepartmentId)->get();
        $this->viewMode = 'form';
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);

        $this->taskId = $task->id;
        $this->judul = $task->judul;
        $this->deskripsi = $task->deskripsi;
        $this->jenis_task = $task->jenis_task;
        $this->status = $task->status;
        $this->deadline = $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : null;
        $this->exp_reward = $task->exp_reward;
        $this->jabatan_id = $task->jabatan_id;

        $this->jabatansForForm = Jabatan::where('department_id', $this->selectedDepartmentId)->get();
        $this->viewMode = 'form';
    }

    public function store()
    {
        $this->validate();

        $task = Task::updateOrCreate(
            ['id' => $this->taskId],
            [
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'status' => $this->status,
                'deadline' => $this->deadline ?: null,
                'jenis_task' => $this->jenis_task,
                'jabatan_id' => $this->jabatan_id,
                'exp_reward' => $this->exp_reward,
            ]
        );

        session()->flash('success', $this->taskId ? 'Task berhasil diperbarui.' : 'Task berhasil dibuat.');

        if ($this->jenis_task === 'pilihan_ganda') {
            return redirect()->route('admin.question.create', $task->id);
        } elseif ($this->jenis_task === 'survey') {
            return redirect()->route('admin.survey.create', $task->id);
        }

        $this->backToTasks();
        $this->resetInputFields();
    }

    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function delete($id = null)
    {
        $targetId = $id ?? $this->deleteId;

        if ($targetId) {
            Task::findOrFail($targetId)->delete();
            $this->deleteId = null;
            session()->flash('success', 'Task berhasil dihapus.');
        }
    }

    private function resetInputFields()
    {
        $this->taskId = null;
        $this->judul = '';
        $this->deskripsi = '';
        $this->jenis_task = 'pilihan_ganda';
        $this->status = 'draft';
        $this->deadline = null;
        $this->exp_reward = '';
    }
}
