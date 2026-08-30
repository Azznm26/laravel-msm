<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Department;
use App\Models\CareerPath;
use App\Models\CareerPathLevel;
use Illuminate\Support\Facades\DB;

class UserCareerPathManager extends Component
{
    use WithPagination;

    public $viewMode = 'index'; // 'index' atau 'form'

    // Filter
    public $filter_department_id = null;
    public $search = '';

    // Form State
    public $userId = null;
    public $userName = '';
    public $primary_career_path_id = null;
    public $secondary_career_path_ids = [];

    // State Hapus/Reset
    public $resetUserId = null;

    protected $updatesQueryString = ['filter_department_id', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterDepartmentId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = [
            'departments' => Department::orderBy('nama_department')->get(),
            'careerPaths' => CareerPath::orderBy('name')->get(),
        ];

        if ($this->viewMode === 'index') {
            $data['users'] = User::with(['department', 'jabatan', 'careerPath', 'careerPaths'])
                ->when($this->filter_department_id, function ($query) {
                    $query->where('department_id', $this->filter_department_id);
                })
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                // Opsional: Filter hanya user biasa (bukan super_admin)
                ->whereNotIn('role', ['super_admin'])
                ->latest()
                ->paginate(10);
        }

        return view('livewire.admin.user-career-path-manager', $data)->layout('layouts.admin');
    }

    // --- LOGIKA NAVIGASI ---
    public function backToIndex()
    {
        $this->viewMode = 'index';
        $this->resetForm();
    }

    // --- LOGIKA FORM & UPDATE ---
    public function edit($id)
    {
        $user = User::with('careerPaths')->findOrFail($id);

        $this->userId = $user->id;
        $this->userName = $user->name;
        $this->primary_career_path_id = $user->career_path_id;

        // Ambil array ID dari tabel pivot untuk multiselect/checkbox
        $this->secondary_career_path_ids = $user->careerPaths->pluck('id')->toArray();

        $this->viewMode = 'form';
    }

    public function store()
    {
        $this->validate([
            'primary_career_path_id' => 'required|exists:career_paths,id',
            'secondary_career_path_ids' => 'nullable|array',
            'secondary_career_path_ids.*' => 'exists:career_paths,id',
        ], [
            'primary_career_path_id.required' => 'Jalur Karir Utama wajib dipilih.',
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($this->userId);
            $primaryPathId = $this->primary_career_path_id;

            // 1. UPDATE PRIMARY PATH & JABATAN
            if ($user->career_path_id != $primaryPathId) {
                $user->career_path_id = $primaryPathId;

                // Set Jabatan ke Level 1 dari Path Baru
                $level1 = CareerPathLevel::where('career_path_id', $primaryPathId)
                    ->where('level', 1)
                    ->first();

                if ($level1) {
                    $user->jabatan_id = $level1->jabatan_id;
                    if ($level1->jabatan && $level1->jabatan->department_id) {
                        $user->department_id = $level1->jabatan->department_id;
                    }
                }
                $user->save();
            }

            // 2. SINKRONISASI PIVOT TABLE (MULTI-PATH)
            // Pastikan Primary Path masuk ke dalam array Pivot
            $allPathIds = $this->secondary_career_path_ids;
            if (!in_array($primaryPathId, $allPathIds)) {
                $allPathIds[] = $primaryPathId;
            }

            // Ambil ID yang saat ini ada di DB
            $currentIds = $user->careerPaths->pluck('id')->toArray();

            // A. Hapus path yang tidak dicentang
            $toDetach = array_diff($currentIds, $allPathIds);
            if (!empty($toDetach)) {
                $user->careerPaths()->detach($toDetach);
            }

            // B. Tambahkan path baru dengan nilai default (Exp 0, Level 1)
            $toAttach = array_diff($allPathIds, $currentIds);
            foreach ($toAttach as $id) {
                $user->careerPaths()->attach($id, [
                    'current_level' => 1,
                    'total_exp' => 0,
                    'is_active' => true,
                    'start_date' => now(),
                ]);
            }

            DB::commit();

            session()->flash('success', 'Jalur Karir untuk ' . $user->name . ' berhasil diperbarui.');
            $this->backToIndex();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // --- LOGIKA RESET (HAPUS PATH) ---
    public function confirmReset($id)
    {
        $this->resetUserId = $id;
        $this->dispatch('open-reset-modal');
    }

    public function resetCareerPath()
    {
        if ($this->resetUserId) {
            $user = User::findOrFail($this->resetUserId);

            DB::transaction(function () use ($user) {
                $user->update(['career_path_id' => null]);
                $user->careerPaths()->detach();
            });

            $this->resetUserId = null;
            session()->flash('success', 'Semua jalur karir milik ' . $user->name . ' telah di-reset.');
        }
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->userName = '';
        $this->primary_career_path_id = null;
        $this->secondary_career_path_ids = [];
        $this->resetErrorBag();
    }
}
