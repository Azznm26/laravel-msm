<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class Edit extends Component
{
    use WithFileUploads;

    public $user;

    // Form Fields
    public $name;
    public $email;
    public $new_photo;

    // ✅ Foto ID Badge (terpisah dari foto profil, wajib diisi)
    public $new_id_badge_photo;

    // Password Fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->user = Auth::user()->load(['department', 'jabatan', 'careerPaths']);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'new_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'new_id_badge_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ];

        // Validasi Password jika diisi
        if (!empty($this->new_password)) {
            $rules['current_password'] = ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, $this->user->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }];
            $rules['new_password'] = ['required', Password::defaults()];
            $rules['new_password_confirmation'] = 'required|same:new_password';
        }

        // Wajibkan foto ID Badge jika user belum punya dan tidak mengupload yang baru
        if (empty($this->user->id_badge_photo) && empty($this->new_id_badge_photo)) {
            $rules['new_id_badge_photo'] = 'required|image|mimes:jpeg,jpg,png,gif|max:2048';
        }

        $this->validate($rules, [
            'new_id_badge_photo.required' => 'Foto ID Badge wajib diupload untuk verifikasi internal perusahaan.',
            'new_photo.max' => 'Ukuran foto maksimal 2MB.',
            'new_id_badge_photo.max' => 'Ukuran foto ID Badge maksimal 2MB.',
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

        // 3. Update Text Data
        $this->user->name = $this->name;
        $this->user->email = $this->email;

        if (!empty($this->new_password)) {
            $this->user->password = Hash::make($this->new_password);
        }

        $this->user->save();

        // Bersihkan form password & file agar tidak nyangkut
        $this->reset(['current_password', 'new_password', 'new_password_confirmation', 'new_photo', 'new_id_badge_photo']);

        session()->flash('success', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        $layoutName = in_array(auth()->user()->role, ['admin', 'super_admin']) ? 'layouts.admin' : 'layouts.app';

        return view('livewire.profile.edit')->layout($layoutName);
    }
}
