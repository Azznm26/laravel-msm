<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;

class TaskAssign extends Component
{
    public Task $task;

    // State Form
    public $user_ids = [];
    public $deadline;

    public function mount(Task $task)
    {
        $this->task = $task;
    }

    public function assign()
    {
        $this->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'deadline' => 'nullable|date|after:now',
        ], [
            'user_ids.required' => 'Pilih minimal satu karyawan untuk ditugaskan.',
            'deadline.after' => 'Deadline harus merupakan tanggal atau waktu di masa depan.'
        ]);

        // Mencegah Duplikasi Penugasan
        $existing = TaskAssignment::whereIn('user_id', $this->user_ids)
            ->where('task_id', $this->task->id)
            ->pluck('user_id')
            ->toArray();

        if (!empty($existing)) {
            // Ambil nama-nama user yang sudah ditugaskan untuk ditampilkan di error
            $existingNames = User::whereIn('id', $existing)->pluck('name')->implode(', ');
            $this->addError('user_ids', 'Beberapa karyawan sudah memiliki task ini: ' . $existingNames);
            return;
        }

        // Siapkan Array untuk Insert Massal
        $assignments = collect($this->user_ids)->map(function ($userId) {
            return [
                'user_id' => $userId,
                'task_id' => $this->task->id,
                'deadline' => $this->deadline,
                'status' => 'assigned',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        TaskAssignment::insert($assignments->toArray());

        session()->flash('success', 'Task berhasil ditugaskan ke ' . $assignments->count() . ' karyawan.');

        // Redirect kembali ke halaman utama Task
        return redirect()->route('task.index');
    }

    public function render()
    {
        // Ambil daftar karyawan yang sesuai dengan kriteria
        $users = User::where('status', 1)
            ->where('role', 'user')
            ->when($this->task->jabatan_id, function ($query, $jabatanId) {
                return $query->where('jabatan_id', $jabatanId);
            })
            ->select('id', 'name', 'email')
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.admin.task-assign', compact('users'))->layout('layouts.admin');
    }
}
