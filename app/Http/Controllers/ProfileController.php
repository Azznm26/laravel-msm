<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil.
     */
    public function edit()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Eager load relasi yang dibutuhkan di view (department, jabatan, careerPaths)
        // Ini penting agar $user->careerPaths di view tidak kosong/error
        $user->load(['department', 'jabatan', 'careerPaths']);

        // Arahkan ke view 'profile' (sesuai path resources/views/profile/edit.blade.php)
        return view('profile', [
            'user' => $user
        ]);
    }

    /**
     * Memproses update data profil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Log untuk debugging
        Log::info('Profile update started', [
            'user_id' => $user->id,
            'has_id_badge' => $request->hasFile('id_badge'),
            'has_photo' => $request->hasFile('photo')
        ]);

        // 1. Definisikan Rule Dasar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],

            // Validasi Password: Cek password lama jika ingin ganti password baru
            'current_password' => ['nullable', 'required_with:new_password', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'new_password' => ['nullable', 'required_with:current_password', Password::defaults()],
            'new_password_confirmation' => ['nullable', 'required_with:new_password', 'same:new_password'],
        ];

        // 2. Validasi Khusus ID Badge (Wajib jika belum punya)
        if (empty($user->id_badge)) {
            $rules['id_badge'] = ['required', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'];
        } else {
            $rules['id_badge'] = ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'];
        }

        $messages = [
            'id_badge.required' => 'Foto ID Badge wajib diupload untuk verifikasi.',
            'id_badge.image' => 'File harus berupa gambar.',
            'id_badge.max' => 'Ukuran foto maksimal 2MB.',
            'photo.max' => 'Ukuran foto profil maksimal 2MB.',
        ];

        $request->validate($rules, $messages);

        try {
            // === Upload ID Badge ===
            if ($request->hasFile('id_badge')) {
                // Hapus file lama jika ada
                if ($user->id_badge) {
                    Storage::disk('public')->delete($user->id_badge);
                }

                // Simpan file baru
                $path = $request->file('id_badge')->store('id_badge-photos', 'public');
                $user->id_badge = $path;

                Log::info('ID Badge updated: ' . $path);
            }

            // === Upload Foto Profil ===
            if ($request->hasFile('photo')) {
                // Hapus file lama jika ada
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }

                // Simpan file baru
                $path = $request->file('photo')->store('profile-photos', 'public');
                $user->photo = $path;

                Log::info('Profile photo updated: ' . $path);
            }

            // === Update Data Diri ===
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            // Gunakan back() agar tetap di halaman edit setelah update
            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('ProfileController update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        }
    }

    public function uploadBadgePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // max 2MB
        ]);

        $user = $request->user();

        // Hapus foto badge lama kalau ada, biar storage tidak menumpuk
        if ($user->id_badge_photo) {
            Storage::disk('public')->delete($user->id_badge_photo);
        }

        $path = $request->file('photo')->store('badges', 'public');

        $user->update(['id_badge_photo' => $path]);

        return response()->json([
            'message' => 'Foto ID Badge berhasil diperbarui',
            'id_badge_photo_url' => asset('storage/' . $path),
        ]);
    }
}
