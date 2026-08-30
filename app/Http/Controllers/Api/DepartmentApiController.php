<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Versi API (mobile) dari App\Livewire\Admin\DepartmentIndex.
 * Logika validasi & hapus-semua dengan konfirmasi password dibuat
 * sama persis dengan versi Livewire.
 */
class DepartmentApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $role = optional($request->user())->role;

            if (!in_array($role, ['admin', 'super_admin'])) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return $next($request);
        });
    }

    /**
     * GET /admin/departments
     */
    public function index()
    {
        $departments = Department::orderBy('nama_department', 'asc')->get();

        return response()->json($departments);
    }

    /**
     * POST /admin/departments
     * PUT /admin/departments/{id}
     *
     * Dipakai untuk create maupun update (mengikuti updateOrCreate di versi Livewire).
     * Kalau $id null → create baru. Kalau ada → update department tsb.
     */
    public function store(Request $request, $id = null)
    {
        $validated = $request->validate([
            'nama_department' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'nama_department')->ignore($id),
            ],
        ], [
            'nama_department.required' => 'Nama department harus diisi.',
            'nama_department.unique' => 'Nama department sudah ada. Silakan gunakan nama lain.',
            'nama_department.max' => 'Nama department maksimal 255 karakter.',
        ]);

        $department = Department::updateOrCreate(
            ['id' => $id],
            ['nama_department' => $validated['nama_department']],
        );

        return response()->json([
            'message' => $id ? 'Departemen berhasil diperbarui.' : 'Departemen berhasil ditambahkan.',
            'department' => $department,
        ]);
    }

    /**
     * DELETE /admin/departments/{id}
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json([
            'message' => 'Departemen berhasil dihapus.',
        ]);
    }

    /**
     * POST /admin/departments/delete-all
     * Body: { password }
     */
    public function destroyAll(Request $request)
    {
        if (!in_array($request->user()->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Password wajib diisi untuk keamanan.',
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return response()->json([
                'message' => 'Password salah.',
                'errors' => ['password' => ['Password salah.']],
            ], 422);
        }

        try {
            Department::query()->delete();

            return response()->json([
                'message' => 'Semua department berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus: ' . $e->getMessage(),
            ], 500);
        }
    }
}
