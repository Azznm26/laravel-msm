<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class PermissionIndex extends Component
{
    use WithPagination;

    public $name;
    public $permissionId;
    public $isModalOpen = false;

    public function render()
    {
        // Mengambil data permission dan membaginya 10 per halaman
        $permissions = Permission::paginate(10);
        return view('livewire.admin.permission-index', compact('permissions'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permissionId
        ]);

        Permission::updateOrCreate(
            ['id' => $this->permissionId],
            ['name' => $this->name, 'guard_name' => 'web']
        );

        session()->flash('success', $this->permissionId ? 'Permission berhasil diperbarui.' : 'Permission berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permissionId = $id;
        $this->name = $permission->name;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Permission::find($id)->delete();
        session()->flash('success', 'Permission berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->permissionId = null;
    }
}
