<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;

class JabatanIndex extends Component
{
    // State Navigasi
    public $selectedDepartmentId = null;
    public $selectedDepartmentName = '';

    // State Pencarian (Sinkronisasi dengan Blade)
    public $search = '';

    // State Form CRUD
    public $jabatan_id;
    public $nama_jabatan;

    // State Modal CRUD
    public $isModalOpen = false;

    // State Modal Hapus Semua
    public $isDeleteAllModalOpen = false;
    public $password = '';

    protected function rules()
    {
        return [
            'nama_jabatan' => [
                'required',
                'max:255',
                // Validasi unique berdasarkan department yang sedang dipilih
                'unique:jabatans,nama_jabatan,' . $this->jabatan_id . ',id,department_id,' . $this->selectedDepartmentId
            ],
        ];
    }

    protected $messages = [
        'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
        'nama_jabatan.unique'   => 'Nama jabatan ini sudah ada di department ini.',
        'nama_jabatan.max'      => 'Nama jabatan maksimal 255 karakter.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        // JIKA SEDANG MEMILIH DEPARTMENT TERTENTU (Tampil Tabel Jabatan)
        if ($this->selectedDepartmentId) {
            $jabatans = Jabatan::where('department_id', $this->selectedDepartmentId)
                ->when($this->search, function ($query) {
                    $query->where('nama_jabatan', 'like', '%' . $this->search . '%');
                })
                ->orderBy('nama_jabatan', 'asc')
                ->get();

            return view('livewire.admin.jabatan-index', [
                'viewMode' => 'jabatans',
                'jabatans' => $jabatans
            ])->layout('layouts.admin');
        }

        // JIKA BELUM MEMILIH (Tampil List Department)
        $departments = Department::withCount('jabatans')
            ->when($this->search, function ($query) {
                $query->where('nama_department', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_department', 'asc')
            ->get();

        return view('livewire.admin.jabatan-index', [
            'viewMode' => 'departments',
            'departments' => $departments
        ])->layout('layouts.admin');
    }

    // --- LOGIKA NAVIGASI SPA ---

    public function selectDepartment($id)
    {
        $dept = Department::findOrFail($id);
        $this->selectedDepartmentId = $dept->id;
        $this->selectedDepartmentName = $dept->nama_department;
        $this->search = ''; // Reset pencarian saat pindah view
    }

    public function backToDepartments()
    {
        $this->selectedDepartmentId = null;
        $this->selectedDepartmentName = '';
        $this->search = ''; // Reset pencarian
        $this->resetInputFields();
    }

    // --- LOGIKA CRUD JABATAN ---

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $this->jabatan_id = $jabatan->id;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        Jabatan::updateOrCreate(
            ['id' => $this->jabatan_id],
            [
                'department_id' => $this->selectedDepartmentId,
                'nama_jabatan' => $this->nama_jabatan
            ]
        );

        session()->flash('success', $this->jabatan_id ? 'Jabatan berhasil diperbarui.' : 'Jabatan berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Jabatan::findOrFail($id)->delete();
        session()->flash('success', 'Jabatan berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    // --- LOGIKA HAPUS SEMUA ---

    public function confirmDeleteAll()
    {
        $this->resetErrorBag();
        $this->password = '';
        $this->isDeleteAllModalOpen = true;
    }

    public function closeDeleteAllModal()
    {
        $this->isDeleteAllModalOpen = false;
        $this->password = '';
        $this->resetErrorBag();
    }

    public function executeDeleteAll()
    {
        // 1. Validasi Password
        $this->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Password wajib diisi untuk konfirmasi.'
        ]);

        if (!Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Password admin tidak valid.');
            return;
        }

        // 2. Eksekusi Hapus berdasarkan konteks View
        if ($this->selectedDepartmentId) {
            // Hapus semua jabatan di department yang sedang dibuka
            Jabatan::where('department_id', $this->selectedDepartmentId)->delete();
            session()->flash('success', "Seluruh data jabatan pada {$this->selectedDepartmentName} berhasil dihapus.");
        } else {
            // Hapus seluruh jabatan di semua department (Jika tombol ditekan dari luar)
            Jabatan::query()->delete();
            session()->flash('success', 'Seluruh data jabatan berhasil dikosongkan.');
        }

        // 3. Tutup modal
        $this->closeDeleteAllModal();
    }

    private function resetInputFields()
    {
        $this->nama_jabatan = '';
        $this->jabatan_id = null;
    }
}
