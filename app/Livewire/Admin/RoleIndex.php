<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class RoleIndex extends Component
{
    use WithPagination;

    public $name;
    public $roleId;
    public $isModalOpen = false;

    public function render()
    {
        // Mengambil data role dan membaginya 10 per halaman
        $roles = Role::paginate(10);
        return view('livewire.admin.role-index', compact('roles'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId
        ]);

        Role::updateOrCreate(
            ['id' => $this->roleId],
            ['name' => $this->name, 'guard_name' => 'web']
        );

        // Memanggil global alert yang ada di admin.blade.php Anda
        session()->flash('success', $this->roleId ? 'Role berhasil diperbarui.' : 'Role berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Role::find($id)->delete();
        session()->flash('success', 'Role berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->roleId = null;
    }
}
