<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\CareerPath;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageUser extends Component
{
    // Mode Tampilan SPA
    public $viewMode = 'departments'; // 'departments', 'users', 'edit'

    // State Navigasi
    public $selectedDepartmentId = null;
    public $selectedDepartmentName = '';
    public $selectedUserId = null;

    // State Form Edit
    public $name, $email, $id_badge, $department_id, $jabatan_id, $primary_career_path_id, $password;
    public $secondary_career_path_ids = [];

    // Data Dropdown
    public $jabatans = [];
    public $allCareerPaths = [];
    public $deptWithPaths = [];

    public function mount()
    {
        // Load data master yang tidak sering berubah
        $this->allCareerPaths = CareerPath::orderBy('name')->get();
        $this->deptWithPaths = Department::with(['careerPaths' => function ($q) {
            $q->orderBy('name');
        }])->whereHas('careerPaths')->orderBy('nama_department')->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->selectedUserId)],
            'id_badge' => 'nullable|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'jabatan_id'    => 'required|exists:jabatans,id',
            'primary_career_path_id' => 'required|exists:career_paths,id',
            'secondary_career_path_ids' => 'nullable|array',
            'secondary_career_path_ids.*' => 'exists:career_paths,id',
            'password' => 'nullable|string|min:8',
        ];
    }

    // AJAIBNYA LIVEWIRE: Otomatis memuat Jabatan saat dropdown Department dipilih
    public function updatedDepartmentId($value)
    {
        $this->jabatans = Jabatan::where('department_id', $value)->orderBy('nama_jabatan')->get();
        $this->jabatan_id = null; // Reset pilihan jabatan sebelumnya
    }

    public function render()
    {
        $data = [];

        if ($this->viewMode === 'departments') {
            $data['adminUsers'] = User::whereIn('role', ['admin', 'super_admin'])->get();
            $data['departments'] = Department::withCount([
                'jabatans',
                'users as users_count' => function ($query) {
                    $query->where('role', 'user')->where('status', 1);
                }
            ])->get();
        } elseif ($this->viewMode === 'users') {
            $data['users'] = User::with(['jabatan', 'careerPath'])
                ->where('department_id', $this->selectedDepartmentId)
                ->where('role', 'user')
                ->get();
        }

        return view('livewire.admin.manage-user', $data)->layout('layouts.admin');
    }

    // --- NAVIGASI SPA ---

    public function viewUsers($deptId, $deptName)
    {
        $this->selectedDepartmentId = $deptId;
        $this->selectedDepartmentName = $deptName;
        $this->viewMode = 'users';
    }

    public function backToDepartments()
    {
        $this->viewMode = 'departments';
        $this->selectedDepartmentId = null;
        $this->selectedDepartmentName = '';
    }

    public function backToUsers()
    {
        $this->viewMode = 'users';
    }

    // --- LOGIKA EDIT & UPDATE USER ---

    public function editUser($userId)
    {
        $user = User::with('careerPaths')->findOrFail($userId);

        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->id_badge = $user->id_badge;
        $this->department_id = $user->department_id;

        // Isi dropdown jabatan sesuai department user
        $this->jabatans = Jabatan::where('department_id', $user->department_id)->get();
        $this->jabatan_id = $user->jabatan_id;

        $this->primary_career_path_id = $user->career_path_id;
        $this->secondary_career_path_ids = $user->careerPaths->pluck('id')->toArray();
        $this->password = ''; // Kosongkan password

        $this->viewMode = 'edit';
    }

    public function updateUser()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $user = User::findOrFail($this->selectedUserId);

            // 1. Update Data Utama
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'id_badge' => $this->id_badge,
                'department_id' => $this->department_id,
                'jabatan_id' => $this->jabatan_id,
                'career_path_id' => $this->primary_career_path_id,
            ];

            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);

            // 2. Sinkronisasi Career Paths (Pivot) menggunakan MySQL Database
            $allPathIds = $this->secondary_career_path_ids ?? [];
            if (!in_array($this->primary_career_path_id, $allPathIds)) {
                $allPathIds[] = $this->primary_career_path_id;
            }

            $currentIds = $user->careerPaths->pluck('id')->toArray();

            // Detach yang dibuang
            $toDetach = array_diff($currentIds, $allPathIds);
            if (!empty($toDetach)) {
                $user->careerPaths()->detach($toDetach);
            }

            // Attach yang baru (tanpa mereset level lama)
            $toAttach = array_diff($allPathIds, $currentIds);
            foreach ($toAttach as $id) {
                $user->careerPaths()->attach($id, [
                    'current_level' => 1,
                    'total_exp' => 0,
                    'is_active' => true,
                    'start_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            session()->flash('success', 'Data karyawan berhasil diperbarui sepenuhnya.');
            $this->viewMode = 'users'; // Kembalikan ke daftar user

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        if ($userId === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        try {
            User::findOrFail($userId)->delete();
            session()->flash('success', 'User berhasil dihapus beserta seluruh riwayat karirnya.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function toggleAccess($userId, $accessType)
    {
        $user = User::findOrFail($userId);

        if (in_array($accessType, ['can_review_tasks', 'can_view_reports'])) {
            $user->update([$accessType => !$user->$accessType]);
            session()->flash('success', 'Hak akses berhasil diperbarui.');
        }
    }
}
