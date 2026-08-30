<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // Tambahkan ini jika ingin syarat password lebih kuat

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $id_badge = '';
    public $password = '';
    public $password_confirmation = '';

    // Menambahkan kustomisasi pesan error
    protected $messages = [
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal harus :min karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            // Tambahkan unique:users,id_badge di bawah ini
            'id_badge' => 'required|string|unique:users,id_badge',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    public function register()
    {
        $validatedData = $this->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            // Tambahkan unique:users,id_badge di bawah ini
            'id_badge' => 'required|string|unique:users,id_badge',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'           => $validatedData['name'],
            'email'          => $validatedData['email'],
            'id_badge'       => $validatedData['id_badge'],
            'password'       => Hash::make($validatedData['password']),
            'role'           => 'user',
            'department_id'  => null,
            'jabatan_id'     => null,
            'career_path_id' => null,
            'status'         => 0, // Pending approval
        ]);

        session()->flash('registration_success', true);

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
