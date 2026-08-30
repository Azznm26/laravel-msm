<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\CareerPath;
use App\Models\CareerPathLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCareerPathController extends Controller
{
    /**
     * Menampilkan daftar User beserta Career Path mereka.
     */
    public function index(Request $request)
    {
        $departments = Department::all();

        // Query user yang memiliki setidaknya satu career path (primary atau secondary)
        // Atau tampilkan semua user agar admin bisa assign yang belum punya.
        $users = User::with(['department', 'jabatan', 'careerPath', 'careerPaths'])
            ->when($request->department_id, function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })
            ->latest()
            ->paginate(10);

        return view('admin.user-career-path.index', compact('departments', 'users'));
    }

    /**
     * Menampilkan form untuk mengedit/assign career path ke user tertentu.
     * Note: Kita pakai Edit karena User-nya sudah ada, kita hanya memodifikasi relasinya.
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        $careerPaths = CareerPath::orderBy('name')->get();

        // Ambil ID path yang sudah dimiliki user untuk pre-fill multiselect
        $assignedPathIds = $user->careerPaths->pluck('id')->toArray();

        return view('admin.user-career-path.edit', compact(
            'user',
            'departments',
            'careerPaths',
            'assignedPathIds'
        ));
    }

    /**
     * Update Career Path User (Primary & Secondary).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'primary_career_path_id' => 'required|exists:career_paths,id',
            'secondary_career_path_ids' => 'nullable|array',
            'secondary_career_path_ids.*' => 'exists:career_paths,id',
        ]);

        DB::beginTransaction();

        try {
            // 1. LOGIC PRIMARY PATH (Jalur Utama & Jabatan)
            $primaryPathId = $request->primary_career_path_id;

            // Cek apakah Primary Path berubah?
            if ($user->career_path_id != $primaryPathId) {
                $user->career_path_id = $primaryPathId;

                // Opsional: Update Jabatan User ke Level 1 dari Primary Path baru
                // Hanya jika jabatan user saat ini tidak sesuai dengan path barunya
                $level1 = CareerPathLevel::where('career_path_id', $primaryPathId)
                    ->where('level', 1)
                    ->first();

                if ($level1) {
                    $user->jabatan_id = $level1->jabatan_id;
                    // Note: Department biasanya ikut berubah jika jabatan berubah
                    if ($level1->jabatan && $level1->jabatan->department_id) {
                        $user->department_id = $level1->jabatan->department_id;
                    }
                }

                $user->save();
            }

            // 2. LOGIC MULTISELECT (Pivot Table)
            // Gabungkan Primary ID dengan Secondary IDs agar semua masuk ke tabel pivot
            $allPathIds = $request->input('secondary_career_path_ids', []);

            // Pastikan Primary Path juga ada di list pivot, supaya user bisa akses task-nya
            if (!in_array($primaryPathId, $allPathIds)) {
                $allPathIds[] = $primaryPathId;
            }

            // SYNC:
            // - Menambah path baru (dengan default value)
            // - Menghapus path yang tidak dipilih (detach)
            // - MEMBIARKAN path yang sudah ada (tidak mereset level/exp) -> Ini default behavior sync() jika tanpa pivot values

            // Namun, untuk path BARU, kita butuh default values.
            // Sayangnya sync() standar tidak bisa membedakan mana baru mana lama untuk default values.
            // Jadi kita pakai syncWithPivotValues tidak cocok karena akan menimpa data lama.

            // Solusi Terbaik: sync() biasa. 
            // Laravel akan insert user_id & career_path_id. 
            // Tapi kolom lain (current_level, is_active) akan NULL/Default DB.
            // Pastikan di Migration Anda kolom current_level ada default(1).

            // Atau kita lakukan manual looping untuk attach yang belum ada:
            $currentIds = $user->careerPaths->pluck('id')->toArray();

            // A. Detach yang dibuang
            $toDetach = array_diff($currentIds, $allPathIds);
            if (!empty($toDetach)) {
                $user->careerPaths()->detach($toDetach);
            }

            // B. Attach yang baru (dengan default values)
            $toAttach = array_diff($allPathIds, $currentIds);
            foreach ($toAttach as $id) {
                $user->careerPaths()->attach($id, [
                    'current_level' => 1,
                    'total_exp' => 0,
                    'is_active' => true,
                    'start_date' => now(),
                ]);
            }

            // (Path yang sudah ada di $allPathIds DAN $currentIds dibiarkan, jadi level aman)

            DB::commit();

            return redirect()->route('admin.user-career-path.index')
                ->with('success', 'Career path user berhasil diperbarui. Jabatan disesuaikan dengan Primary Path.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghapus semua career path user (Reset).
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            // Null-kan Primary
            $user->update(['career_path_id' => null]);

            // Detach Semua Pivot
            $user->careerPaths()->detach();
        });

        return back()->with('success', 'Semua career path user telah dihapus.');
    }
}
