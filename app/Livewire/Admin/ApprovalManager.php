<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Department;
use App\Models\CareerPath;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApprovalManager extends Component
{
    use WithPagination;

    // Mode Tampilan: 'list', 'assign'
    public $viewMode = 'list';

    // Tab History: 'pending', 'approved', 'rejected', 'all'
    public $statusFilter = 'pending';

    // Mapping label <-> nilai kolom `status` di DB
    private const STATUS_MAP = [
        'pending' => 0,
        'approved' => 1,
        'rejected' => -1,
    ];

    // Searching & Sorting
    public $search = '';
    public $sortBy = 'created_at';
    public $sortOrder = 'asc';

    // State untuk Form Assign
    public $selectedUserId;
    public $activeUser;

    // Form Fields
    public $department_id = '';
    public $jabatan_id = '';
    public $career_path_id = '';
    public $role = 'user'; // Default

    // Data Master untuk Dropdown
    public $departments = [];
    public $availableJabatans = [];
    public $availableCareerPaths = [];

    // Reset pagination ketika melakukan pencarian
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
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

    public function mount()
    {
        $this->departments = Department::orderBy('nama_department')->get();
    }

    // ==========================================
    // REACTIVE DROPDOWNS (Pengganti AJAX lama)
    // ==========================================
    public function updatedDepartmentId()
    {
        $this->jabatan_id = '';
        $this->career_path_id = '';
        $this->availableCareerPaths = [];

        if ($this->department_id) {
            $this->availableJabatans = Jabatan::where('department_id', $this->department_id)
                ->orderBy('nama_jabatan')
                ->get();
        } else {
            $this->availableJabatans = [];
        }
    }

    public function updatedJabatanId()
    {
        $this->career_path_id = '';

        if ($this->jabatan_id) {
            // Cari Career Path yang memiliki jabatan ini
            $this->availableCareerPaths = CareerPath::whereHas('jabatans', function ($q) {
                $q->where('jabatans.id', $this->jabatan_id);
            })->orderBy('name')->get();
        } else {
            $this->availableCareerPaths = [];
        }
    }

    public function render()
    {
        $query = User::query()
            ->with(['department', 'jabatan'])
            ->where('role', '!=', 'super_admin');

        if ($this->statusFilter === 'all') {
            $query->whereIn('status', array_values(self::STATUS_MAP));
        } else {
            $query->where('status', self::STATUS_MAP[$this->statusFilter] ?? 0);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderBy($this->sortBy, $this->sortOrder)->paginate(10);

        // Badge jumlah tiap tab (tidak terpengaruh search/filter, biar tab selalu tunjukkan total riil)
        $baseCountQuery = fn($status) => User::where('role', '!=', 'super_admin')->where('status', $status)->count();

        $tabCounts = [
            'pending' => $baseCountQuery(self::STATUS_MAP['pending']),
            'approved' => $baseCountQuery(self::STATUS_MAP['approved']),
            'rejected' => $baseCountQuery(self::STATUS_MAP['rejected']),
        ];
        $tabCounts['all'] = $tabCounts['pending'] + $tabCounts['approved'] + $tabCounts['rejected'];

        return view('livewire.admin.approval-manager', [
            'pendingUsers' => $users,
            'tabCounts' => $tabCounts,
        ])->layout('layouts.admin');
    }

    // ==========================================
    // LOGIKA APPROVAL & REJECT
    // ==========================================
    public function openAssignMode($userId)
    {
        $this->activeUser = User::findOrFail($userId);
        $this->selectedUserId = $this->activeUser->id;

        // Reset form
        $this->department_id = '';
        $this->jabatan_id = '';
        $this->career_path_id = '';
        $this->role = 'user';
        $this->availableJabatans = [];
        $this->availableCareerPaths = [];

        $this->viewMode = 'assign';
    }

    public function approveUser()
    {
        $allowedRoles = auth()->user()->role === 'super_admin' ? ['user', 'admin', 'super_admin'] : ['user'];

        $this->validate([
            'department_id' => 'required|exists:departments,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'career_path_id' => 'required|exists:career_paths,id',
            'role' => ['required', 'string', Rule::in($allowedRoles)],
        ]);

        // Validasi ekstra: Pastikan jabatan ada di career path
        $isValidPath = CareerPath::where('id', $this->career_path_id)
            ->whereHas('jabatans', fn($q) => $q->where('jabatans.id', $this->jabatan_id))
            ->exists();

        if (!$isValidPath) {
            $this->addError('jabatan_id', 'Jabatan tidak terdaftar dalam alur Career Path ini.');
            return;
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($this->selectedUserId);

            // 1. Update Tabel User
            $user->update([
                'department_id' => $this->department_id,
                'jabatan_id' => $this->jabatan_id,
                'career_path_id' => $this->career_path_id,
                'role' => $this->role,
                'status' => 1, // Approved
                'email_verified_at' => now(),
            ]);

            // 2. Insert ke Pivot Career Path (Bila belum ada)
            if (!$user->careerPaths()->where('career_path_id', $this->career_path_id)->exists()) {
                $user->careerPaths()->attach($this->career_path_id, [
                    'current_level' => 1,
                    'total_exp' => 0,
                    'is_active' => true,
                    'start_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            session()->flash('success', "User {$user->name} berhasil di-approve.");
            $this->viewMode = 'list';
            $this->statusFilter = 'pending';
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan MySQL: ' . $e->getMessage());
        }
    }

    public function rejectUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => -1]); // Rejected

        session()->flash('success', "Pendaftaran {$user->name} telah ditolak.");
    }

    /**
     * Kembalikan user dari histori (approved/rejected) ke status pending lagi,
     * misalnya kalau admin salah klik approve/reject.
     */
    public function revertToPending($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => 0]);

        session()->flash('success', "Status {$user->name} dikembalikan ke Pending.");
    }

    public function cancelAssign()
    {
        $this->viewMode = 'list';
    }
}
