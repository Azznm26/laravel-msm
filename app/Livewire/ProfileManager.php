<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileManager extends Component
{
    use WithFileUploads;

    public $user;

    // Form Data Dasar
    public $name;
    public $email;
    public $new_photo;

    // ✅ Foto ID Badge (terpisah dari foto profil, wajib diisi)
    public $new_id_badge_photo;

    // Form Password
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'new_photo' => 'nullable|image|max:2048',
            'new_id_badge_photo' => 'nullable|image|max:2048',
        ];

        // Validasi Password Dinamis Berdasarkan Role
        if (!empty($this->new_password)) {
            $rules['new_password'] = 'required|string|min:8|same:new_password_confirmation';

            // Hanya user biasa yang WAJIB memasukkan password lama
            if ($this->user->role === 'user') {
                $rules['current_password'] = ['required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user->password)) {
                        $fail('Password saat ini salah.');
                    }
                }];
            }
        }

        // ✅ Wajibkan foto ID Badge jika user belum punya dan tidak mengupload yang baru
        if (empty($this->user->id_badge_photo) && empty($this->new_id_badge_photo)) {
            $rules['new_id_badge_photo'] = 'required|image|max:2048';
        }

        $this->validate($rules, [
            'new_id_badge_photo.required' => 'Foto ID Badge wajib diupload untuk verifikasi internal perusahaan.',
        ]);

        // 1. Eksekusi Upload Foto Profil
        if ($this->new_photo) {
            if ($this->user->photo) {
                Storage::disk('public')->delete($this->user->photo);
            }
            $this->user->photo = $this->new_photo->store('profile-photos', 'public');
        }

        // 2. Eksekusi Upload Foto ID Badge
        if ($this->new_id_badge_photo) {
            if ($this->user->id_badge_photo) {
                Storage::disk('public')->delete($this->user->id_badge_photo);
            }
            $this->user->id_badge_photo = $this->new_id_badge_photo->store('id-badge-photos', 'public');
        }

        // 3. Eksekusi Update Data
        $this->user->name = $this->name;
        $this->user->email = $this->email;

        // 4. Eksekusi Ganti Password
        if (!empty($this->new_password)) {
            $this->user->password = Hash::make($this->new_password);
        }

        $this->user->save();

        // Bersihkan form password & input file
        $this->reset(['current_password', 'new_password', 'new_password_confirmation', 'new_photo', 'new_id_badge_photo']);

        session()->flash('success', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        // 🌟 KEAJAIBAN TALL STACK: Render Layout Berbeda Berdasarkan Role!
        $layoutName = in_array($this->user->role, ['admin', 'super_admin'])
            ? 'layouts.admin'
            : 'layouts.app';

        return view('livewire.profile-manager')->layout($layoutName);
    }
}
