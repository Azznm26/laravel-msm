<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;

class DepartmentIndex extends Component
{
    // HAPUS 'public $departments;' dari sini untuk menghindari error serialisasi Livewire 3
    public $nama_department;
    public $department_id;

    // State pencarian
    public $search = '';

    // State untuk Modal
    public $isModalOpen = false;
    public $isDeleteAllModalOpen = false;
    public $password = '';

    // Aturan validasi dinamis
    protected function rules()
    {
        return [
            'nama_department' => 'required|string|max:255|unique:departments,nama_department,' . $this->department_id,
        ];
    }

    protected $messages = [
        'nama_department.required' => 'Nama department harus diisi.',
        'nama_department.unique'   => 'Nama department sudah ada. Silakan gunakan nama lain.',
        'nama_department.max'      => 'Nama department maksimal 255 karakter.',
    ];

    public function updated($propertyName)
    {
        // Jangan jalankan validasi form saat yang berubah adalah kolom search
        if ($propertyName === 'search') {
            return;
        }

        $this->validateOnly($propertyName);
    }

    public function render()
    {
        // PERBAIKAN: Ambil data langsung di sini dan kirim (passing) ke view
        $departments = Department::query()
            ->when($this->search, function ($query) {
                $query->where('nama_department', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_department', 'asc')
            ->get();

        return view('livewire.admin.department-index', compact('departments'))->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->department_id = $id;
        $this->nama_department = $department->nama_department;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        Department::updateOrCreate(
            ['id' => $this->department_id],
            ['nama_department' => $this->nama_department]
        );

        session()->flash('success', $this->department_id ? 'Departemen berhasil diperbarui.' : 'Departemen berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        // 1. Cek apakah department masih dipakai oleh tabel User
        $isUsedByUser = User::where('department_id', $id)->exists();

        // 2. Cek apakah department masih dipakai oleh tabel Jabatan
        $isUsedByJabatan = Jabatan::where('department_id', $id)->exists();

        // 3. Jika masih dipakai salah satunya, tolak penghapusan
        if ($isUsedByUser || $isUsedByJabatan) {
            session()->flash('error', 'Gagal: Departemen tidak dapat dihapus karena masih terkait dengan Data User atau Jabatan.');

            // TAMBAHKAN BARIS INI: Kirim sinyal ke frontend untuk menutup modal/loading
            $this->dispatch('close-modal');

            return;
        }

        // 4. Jika aman, lanjutkan penghapusan
        Department::findOrFail($id)->delete();
        session()->flash('success', 'Departemen berhasil dihapus.');

        // TAMBAHKAN BARIS INI JUGA: Tutup modal/loading setelah sukses menghapus
        $this->dispatch('close-modal');
    }

    public function confirmDeleteAll()
    {
        $this->password = '';
        $this->isDeleteAllModalOpen = true;
    }

    public function executeDeleteAll()
    {
        if (!in_array(auth()->user()->role, ['super_admin', 'admin'])) {
            session()->flash('error', 'Akses ditolak.');
            $this->closeDeleteAllModal();
            return;
        }

        $this->validate([
            'password' => 'required'
        ], [
            'password.required' => 'Password wajib diisi untuk keamanan.'
        ]);

        if (!Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Password salah.');
            return;
        }

        // SOLUSI: Gunakan model delete() alih-alih truncate()
        // Ini akan memicu event model dan menghormati relasi database
        try {
            // Ambil semua ID department yang sedang dipakai oleh User dan Jabatan
            $usedDepartmentIds = array_unique(array_merge(
                User::whereNotNull('department_id')->pluck('department_id')->toArray(),
                Jabatan::whereNotNull('department_id')->pluck('department_id')->toArray()
            ));

            // Hapus department yang ID-nya TIDAK ADA dalam daftar yang terpakai
            $deletedCount = Department::whereNotIn('id', $usedDepartmentIds)->delete();
            $skippedCount = count($usedDepartmentIds);

            // Berikan pesan yang informatif
            if ($skippedCount > 0) {
                session()->flash('success', "Selesai: {$deletedCount} departemen dihapus. {$skippedCount} departemen dilewati karena masih digunakan.");
            } else {
                session()->flash('success', 'Semua departemen berhasil dihapus.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        $this->closeDeleteAllModal();
    }
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function closeDeleteAllModal()
    {
        $this->isDeleteAllModalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->nama_department = '';
        $this->department_id = null;
    }
}
