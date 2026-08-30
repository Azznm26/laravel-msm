<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class UserRoleIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $userId;
    public $userName;
    public $selectedRoles = []; // Array untuk menampung role yang dipilih
    public $isModalOpen = false;

    // Reset pagination ketika melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Ambil data user beserta relasi roles-nya dari Spatie
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        $roles = Role::all();

        return view('livewire.admin.user-role-index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->userName = $user->name;

        // Ambil nama-nama role yang dimiliki user saat ini (Pluck ke dalam array)
        $this->selectedRoles = $user->roles->pluck('name')->toArray();

        $this->isModalOpen = true;
    }

    public function updateRoles()
    {
        $user = User::findOrFail($this->userId);

        // Fitur ajaib Spatie: syncRoles akan otomatis menghapus role lama 
        // dan memasukkan role baru sesuai array yang kita kirim
        $user->syncRoles($this->selectedRoles);

        session()->flash('success', 'Role untuk user ' . $this->userName . ' berhasil diperbarui.');

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->userId = null;
        $this->userName = '';
        $this->selectedRoles = [];
    }
}
