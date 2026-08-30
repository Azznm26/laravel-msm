<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CareerPath;
use App\Models\CareerPathLevel;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CareerPathManager extends Component
{
    // Mode SPA: 'list', 'form', 'detail'
    public $viewMode = 'list';

    // Data Master
    public $allDepartments = [];
    public $availableJabatans = [];
    public $allJabatans = []; // Untuk referensi umum (tidak lagi dipakai di form level)

    // State Form Career Path
    public $careerPathId = null;
    public $name = '';
    public $description = '';
    public $selectedDepartments = [];
    public $selectedJabatans = [];

    // State Detail Career Path
    public CareerPath $activeCareerPath;

    // Daftar jabatan YANG SUDAH DIPILIH untuk career path ini (dipakai di form level, biar user tidak bingung)
    public $levelJabatanOptions = [];

    // State Form Level
    public $levelId = null;
    public $levelOrder = '';
    public $levelName = '';
    public $expRequired = 0;
    public $jabatanIdLevel = '';

    // State Modal Konfirmasi Hapus
    public $showDeleteModal = false;
    public $deleteType = null; // 'path' atau 'level'
    public $deleteTargetId = null;
    public $deleteTargetName = '';
    public $deleteTargetUserCount = 0;

    public function mount()
    {
        $this->allDepartments = Department::all();
        $this->allJabatans = Jabatan::with('department')->get();
    }

    // ==========================================
    // REACTIVE HOOKS
    // ==========================================
    // Otomatis memfilter Jabatan saat Department dicentang (Pengganti AJAX)
    public function updatedSelectedDepartments()
    {
        if (empty($this->selectedDepartments)) {
            $this->availableJabatans = [];
            $this->selectedJabatans = [];
        } else {
            $this->availableJabatans = Jabatan::whereIn('department_id', $this->selectedDepartments)->get();
            // Hapus jabatan yang sudah dicentang jika departemennya di-uncheck
            $validJabatanIds = $this->availableJabatans->pluck('id')->toArray();
            $this->selectedJabatans = array_intersect($this->selectedJabatans, $validJabatanIds);
        }
    }

    public function render()
    {
        $careerPaths = CareerPath::with(['departments', 'jabatans'])
            ->withCount(['levels', 'users'])
            ->latest()
            ->get();

        return view('livewire.admin.career-path-manager', compact('careerPaths'))->layout('layouts.admin');
    }

    // ==========================================
    // MANAJEMEN CAREER PATH
    // ==========================================
    public function create()
    {
        $this->resetPathForm();
        $this->viewMode = 'form';
    }

    public function edit($id)
    {
        $this->resetPathForm();
        $cp = CareerPath::findOrFail($id);

        $this->careerPathId = $cp->id;
        $this->name = $cp->name;
        $this->description = $cp->description;
        $this->selectedDepartments = $cp->departments->pluck('id')->toArray();

        // Pancing hook untuk mengisi availableJabatans
        $this->updatedSelectedDepartments();

        $this->selectedJabatans = $cp->jabatans->pluck('id')->toArray();
        $this->viewMode = 'form';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selectedDepartments' => 'required|array|min:1',
            'selectedJabatans' => 'required|array|min:1',
        ], [
            'name.required' => 'Nama jalur karir wajib diisi.',
            'selectedDepartments.required' => 'Pilih minimal satu departemen.',
            'selectedJabatans.required' => 'Pilih minimal satu jabatan target.',
        ]);

        DB::beginTransaction();

        try {
            if ($this->careerPathId) {
                // UPDATE
                $cp = CareerPath::findOrFail($this->careerPathId);
                $cp->update(['name' => $this->name, 'description' => $this->description]);
                $cp->departments()->sync($this->selectedDepartments);
                $cp->jabatans()->sync($this->selectedJabatans);

                // Auto-assign update (syncWithoutDetaching)
                $users = User::whereIn('jabatan_id', $this->selectedJabatans)->get();
                foreach ($users as $user) {
                    if (!$cp->users()->where('user_id', $user->id)->exists()) {
                        $cp->users()->attach($user->id, [
                            'current_level' => 1,
                            'total_exp' => 0,
                            'is_active' => true,
                            'start_date' => now()
                        ]);
                    }
                }

                DB::commit();

                session()->flash('success', 'Jalur karir berhasil diperbarui.');

                // Setelah update, langsung tampilkan halaman detail agar user bisa lanjut atur level
                $this->show($cp->id);
                return;
            } else {
                // CREATE
                $cp = CareerPath::create(['name' => $this->name, 'description' => $this->description, 'order' => 0]);
                $cp->departments()->attach($this->selectedDepartments);
                $cp->jabatans()->attach($this->selectedJabatans);

                // Auto-assign create
                $users = User::whereIn('jabatan_id', $this->selectedJabatans)->get();
                foreach ($users as $user) {
                    $cp->users()->attach($user->id, [
                        'current_level' => 1,
                        'total_exp' => 0,
                        'is_active' => true,
                        'start_date' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                DB::commit();

                session()->flash('success', 'Jalur karir berhasil dibuat dan ' . $users->count() . ' karyawan otomatis ditambahkan. Sekarang atur tingkatan/level di bawah ini.');

                // Langsung arahkan ke halaman detail supaya user langsung lanjut menambah level
                $this->show($cp->id);
                return;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $this->activeCareerPath = CareerPath::with([
            'departments',
            'jabatans',
            'levels' => function ($q) {
                $q->orderBy('level');
            },
            'levels.jabatan.department',
            'users.jabatan',
            'users.department'
        ])->findOrFail($id);

        // Batasi pilihan jabatan di form level HANYA ke jabatan yang sudah dipilih untuk path ini
        $this->levelJabatanOptions = $this->activeCareerPath->jabatans;

        $this->resetLevelForm();
        $this->viewMode = 'detail';
    }

    public function backToList()
    {
        $this->viewMode = 'list';
    }

    // ==========================================
    // MANAJEMEN LEVEL (DI DALAM DETAIL VIEW)
    // ==========================================
    public function editLevel($id)
    {
        $level = CareerPathLevel::findOrFail($id);
        $this->levelId = $level->id;
        $this->levelOrder = $level->level;
        $this->levelName = $level->level_name;
        $this->expRequired = $level->required_exp;
        $this->jabatanIdLevel = $level->jabatan_id;
    }

    public function storeLevel()
    {
        $this->validate([
            'levelOrder' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('career_path_levels', 'level')->ignore($this->levelId)
                    ->where(fn($q) => $q->where('career_path_id', $this->activeCareerPath->id)),
            ],
            'levelName' => 'required|string|max:255',
            'expRequired' => 'required|integer|min:0',
            'jabatanIdLevel' => 'required|exists:jabatans,id',
        ], [
            'levelOrder.unique' => 'Nomor level ini sudah dipakai. Silakan gunakan nomor lain.',
            'levelName.required' => 'Nama level/pangkat wajib diisi.',
            'jabatanIdLevel.required' => 'Pilih jabatan untuk level ini.',
        ]);

        CareerPathLevel::updateOrCreate(
            ['id' => $this->levelId],
            [
                'career_path_id' => $this->activeCareerPath->id,
                'level'          => $this->levelOrder,
                'level_order'    => $this->levelOrder,
                'level_name'     => $this->levelName,
                'required_exp'   => $this->expRequired,
                'jabatan_id'     => $this->jabatanIdLevel,
            ]
        );

        session()->flash('success', $this->levelId ? 'Level berhasil diperbarui.' : 'Level baru berhasil ditambahkan.');
        $this->show($this->activeCareerPath->id); // Refresh data
    }

    public function cancelLevelEdit()
    {
        $this->resetLevelForm();
    }

    // ==========================================
    // MODAL KONFIRMASI HAPUS
    // ==========================================
    public function confirmDeletePath($id)
    {
        $cp = CareerPath::withCount('users')->findOrFail($id);

        $this->deleteType = 'path';
        $this->deleteTargetId = $cp->id;
        $this->deleteTargetName = $cp->name;
        $this->deleteTargetUserCount = $cp->users_count;
        $this->showDeleteModal = true;
    }

    public function confirmDeleteLevel($id)
    {
        $level = CareerPathLevel::findOrFail($id);

        $this->deleteType = 'level';
        $this->deleteTargetId = $level->id;
        $this->deleteTargetName = $level->level_name;
        $this->deleteTargetUserCount = 0;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->reset(['showDeleteModal', 'deleteType', 'deleteTargetId', 'deleteTargetName', 'deleteTargetUserCount']);
    }

    public function executeDelete()
    {
        if (!$this->deleteTargetId || !$this->deleteType) {
            return;
        }

        try {
            if ($this->deleteType === 'path') {
                CareerPath::findOrFail($this->deleteTargetId)->delete();
                session()->flash('success', 'Jalur karir berhasil dihapus.');

                $this->cancelDelete();
                $this->viewMode = 'list';
            } elseif ($this->deleteType === 'level') {
                CareerPathLevel::findOrFail($this->deleteTargetId)->delete();
                session()->flash('success', 'Level berhasil dihapus.');

                $activeId = $this->activeCareerPath->id;
                $this->cancelDelete();
                $this->show($activeId); // refresh detail
            }
        } catch (\Exception $e) {
            $this->cancelDelete();
            session()->flash('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }

    // ==========================================
    // HELPERS
    // ==========================================
    private function resetPathForm()
    {
        $this->careerPathId = null;
        $this->name = '';
        $this->description = '';
        $this->selectedDepartments = [];
        $this->selectedJabatans = [];
        $this->availableJabatans = [];
    }

    private function resetLevelForm()
    {
        $this->levelId = null;

        // Sarankan nomor level berikutnya secara otomatis, biar user tidak perlu menghitung sendiri
        $this->levelOrder = $this->activeCareerPath && $this->activeCareerPath->levels->count() > 0
            ? ($this->activeCareerPath->levels->max('level') + 1)
            : 1;

        $this->levelName = '';
        $this->expRequired = 0;
        $this->jabatanIdLevel = '';
    }
}