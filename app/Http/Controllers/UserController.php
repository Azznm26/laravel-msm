<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'super_admin'])) {
            return view('admin.profile', compact('user'));
        }

        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // ✅ Validasi berdasarkan role
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:2048',
        ];

        // Hanya user biasa yang butuh current_password untuk ganti password
        if ($user->role === 'user') {
            $rules['current_password'] = 'required_with:new_password|string|min:8';
            $rules['new_password'] = 'nullable|string|min:8|confirmed';
        } else {
            // Admin/super_admin bisa ganti password tanpa current (opsional)
            $rules['new_password'] = 'nullable|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Update data dasar
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Upload foto
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profile-photos', 'public');
        }

        // Ganti password
        if (!empty($validated['new_password'])) {
            if ($user->role === 'user') {
                if (!Hash::check($validated['current_password'], $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini salah.']);
                }
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        // ✅ Redirect sesuai role
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->route('admin.profile')->with('success', 'Profil admin berhasil diperbarui.');
        }

        return redirect()->route('user.profile')->with('success', 'Profil Anda berhasil diperbarui.');
    }
    public function apiShow(Request $request)
    {
        // Load semua relasi yang dibutuhkan
        $user = $request->user()->load(['department', 'jabatan', 'careerPath']);

        return response()->json([
            'id'                   => $user->id,
            'name'                 => $user->name,
            'email'                => $user->email,
            'role'                 => $user->role,
            'status'               => $user->status, // Ambil status aslinya (1 atau 0)

            // ✅ Kirim ID relasi (untuk jaga-jaga jika relasinya kosong)
            'department_id'        => $user->department_id,
            'jabatan_id'           => $user->jabatan_id,
            'career_path_id'       => $user->career_path_id,

            // ✅ Kirim Objek Relasinya agar terbaca oleh getRelName di React Native
            'department'           => $user->department,
            'jabatan'              => $user->jabatan,
            'career_path'          => $user->careerPath,

            // ✅ Kirim data tambahan untuk profil
            'last_seen'            => $user->last_seen,
            'requested_jabatan'    => $user->requested_jabatan,
            'requested_department' => $user->requested_department,
            'id_badge'             => $user->id_badge,

            // Format URL Foto
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,

            // ✅ Foto ID Badge terpisah dari foto profil
            'id_badge_photo' => $user->id_badge_photo
                ? asset('storage/' . $user->id_badge_photo)
                : null,
        ]);
    }

    // ─── PUT /api/user/profile ────────────────────────────────
    public function apiUpdateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
                'photo' => $user->photo
                    ? asset('storage/' . $user->photo)
                    : null,
            ],
        ]);
    }

    public function apiUpdatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Kata sandi saat ini tidak sesuai.',
                'errors'  => ['current_password' => ['Kata sandi saat ini tidak sesuai.']],
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Kata sandi berhasil diperbarui.',
        ]);
    }

    // ─── POST /api/user/photo ─────────────────────────────────
    // Foto PROFIL (foto wajah umum, ditampilkan di avatar & sidebar)
    public function apiUpdatePhoto(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hapus foto lama jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Simpan foto baru — pakai folder yang sama dengan web ('profile-photos')
        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->update(['photo' => $path]);

        return response()->json([
            'message' => 'Foto berhasil diperbarui.',
            'photo'   => asset('storage/' . $path),
        ]);
    }

    // ─── POST /api/user/badge-photo ───────────────────────────
    // ✅ BARU: Foto khusus untuk ID BADGE (dipakai di kartu identitas karyawan)
    // Sengaja dipisah dari apiUpdatePhoto() di atas: kolom, folder storage,
    // dan validasi berbeda dari foto profil biasa.
    public function apiUpdateBadgePhoto(Request $request)
    {
        $user = $request->user();

        $request->validate([
            // Untuk foto ID Badge biasanya ingin kualitas lebih ketat
            // (format resmi, ukuran wajar untuk dicetak di kartu)
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        // Hapus foto badge lama jika ada, supaya storage tidak menumpuk
        if ($user->id_badge_photo) {
            Storage::disk('public')->delete($user->id_badge_photo);
        }

        // Simpan di folder terpisah: 'badge-photos', bukan 'profile-photos'
        $path = $request->file('photo')->store('badge-photos', 'public');

        $user->update(['id_badge_photo' => $path]);

        return response()->json([
            'message'        => 'Foto ID Badge berhasil diperbarui.',
            'id_badge_photo' => asset('storage/' . $path),
        ]);
    }

    public function apiCareerPaths(Request $request)
    {
        $user = $request->user();

        $paths = $user->careerPaths()->with(['levels.jabatan'])->get()->map(function ($path) use ($user) {
            $currentLevel = $path->pivot->current_level ?? 1;
            $totalExp = $path->pivot->total_exp ?? 0;

            $sortedLevels = $path->levels->sortBy('level')->values();

            $currentLevelData = $sortedLevels->firstWhere('level', $currentLevel);
            $nextLevelData = $sortedLevels->firstWhere('level', $currentLevel + 1);

            // ─── Hitung progress_percentage secara manual ───────────────────
            if ($nextLevelData) {
                $requiredForCurrent = $currentLevelData->required_exp ?? 0;
                $requiredForNext = $nextLevelData->required_exp;
                $range = $requiredForNext - $requiredForCurrent;

                $progressPercentage = $range > 0
                    ? (($totalExp - $requiredForCurrent) / $range) * 100
                    : 100;

                $progressPercentage = max(0, min(100, round($progressPercentage)));
            } else {
                // Sudah di level maksimal, tidak ada level berikutnya
                $progressPercentage = 100;
            }

            return [
                'id'                     => $path->id,
                'name'                   => $path->name,
                // is_primary belum ada kolomnya di career_paths, jadi dibandingkan manual
                'is_primary'             => $user->career_path_id === $path->id,
                'total_exp'              => $totalExp,
                'current_level'          => $currentLevel,
                'progress_percentage'    => $progressPercentage,
                'start_date'             => $path->pivot->start_date,
                'calculated_next_level'  => $nextLevelData,
                'levels'                 => $sortedLevels,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $paths
        ]);
    }
}
