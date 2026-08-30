<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubordinateController extends Controller
{
    /**
     * Menampilkan daftar bawahan (Hanya untuk Pimpinan Departemen).
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Super Admin: Bisa lihat semua user (kecuali diri sendiri)
        if ($user->isAdmin()) {
            $subordinates = User::with(['jabatan', 'department'])
                ->where('id', '!=', $user->id)
                ->where('role', '!=', 'super_admin') // Opsional: sembunyikan sesama super admin
                ->latest()
                ->paginate(15);

            return view('subordinates.index', compact('subordinates'));
        }

        // 2. Cek apakah User punya Departemen & Jabatan?
        if (!$user->department_id || !$user->jabatan_id) {
            abort(403, 'Anda tidak terdaftar dalam departemen atau jabatan apapun.');
        }

        // 3. LOGIKA HIERARKI TERTINGGI
        // Cari level_order paling tinggi (angka terbesar) di departemen ini
        // Asumsi: Level 1 = Staff, Level 5 = Manager. Jadi kita cari MAX().
        $maxLevelOrder = Jabatan::where('department_id', $user->department_id)
            ->max('level_order');

        // Cek apakah jabatan user saat ini adalah yang tertinggi?
        $isHeadOfDept = $user->jabatan->level_order >= $maxLevelOrder;

        if ($isHeadOfDept) {
            // Tampilkan semua user di departemen yang sama, KECUALI diri sendiri
            // Dan KECUALI user lain yang levelnya setara (sesama Manager) jika diinginkan
            $subordinates = User::with(['jabatan', 'department'])
                ->where('department_id', $user->department_id)
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->paginate(15);

            return view('subordinates.index', compact('subordinates'));
        }

        // 4. Jika bukan pimpinan
        abort(403, 'Akses Ditolak. Hanya jabatan tertinggi di departemen yang dapat melihat daftar bawahan.');
    }

    /**
     * Menampilkan detail progress bawahan.
     */
    public function show(User $subordinate)
    {
        $currentUser = Auth::user();

        // 1. Admin Boleh Akses
        if ($currentUser->isAdmin()) {
            return $this->showSubordinateDetail($subordinate);
        }

        // 2. Validasi Departemen (Harus Sama)
        if ($currentUser->department_id !== $subordinate->department_id) {
            abort(403, 'Anda tidak memiliki akses ke karyawan beda departemen.');
        }

        // 3. Validasi Hierarki (Harus Pimpinan)
        $maxLevelOrder = Jabatan::where('department_id', $currentUser->department_id)
            ->max('level_order');

        if ($currentUser->jabatan->level_order < $maxLevelOrder) {
            abort(403, 'Hanya pimpinan yang boleh melihat detail bawahan.');
        }

        return $this->showSubordinateDetail($subordinate);
    }

    /**
     * Helper private untuk load data detail (karena dipakai admin & manager)
     */
    private function showSubordinateDetail($user)
    {
        // Load data lengkap termasuk Multi Career Path
        $user->load(['careerPaths.levels', 'department', 'jabatan']);

        // Kita hitung ringkasan progress untuk ditampilkan ke atasan
        $careerPaths = $user->careerPaths->map(function ($path) {
            $currentLvl = $path->pivot->current_level;
            $totalExp = $path->pivot->total_exp;

            // Cari level selanjutnya untuk hitung %
            $nextLevel = $path->levels->firstWhere('level', $currentLvl + 1);
            $expRequired = $nextLevel ? $nextLevel->exp_required : 0;

            $percentage = ($nextLevel && $expRequired > 0)
                ? min(100, round(($totalExp / $expRequired) * 100))
                : 100;

            $path->progress_percentage = $percentage;
            $path->current_level_name = $path->levels->firstWhere('level', $currentLvl)->name ?? 'Level ' . $currentLvl;

            return $path;
        });

        return view('subordinates.show', compact('user', 'careerPaths'));
    }
}
