<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\CareerPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Versi API (mobile) dari App\Livewire\Admin\ApprovalManager.
 * Logika bisnis (validasi role, cek career path, transaksi DB) dibuat
 * sama persis dengan versi Livewire supaya perilaku web & mobile konsisten.
 */
class ApprovalApiController extends Controller
{
    public function __construct()
    {
        // Hanya admin & super_admin yang boleh mengakses seluruh endpoint di controller ini.
        // Sesuaikan/ganti dengan middleware role kamu sendiri kalau sudah ada (mis. 'role:admin,super_admin').
        $this->middleware(function ($request, $next) {
            $role = optional($request->user())->role;

            if (!in_array($role, ['admin', 'super_admin'])) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return $next($request);
        });
    }

    /**
     * GET /admin/approval/pending-users
     */
    public function pendingUsers(Request $request)
    {
        $allowedSort = ['created_at', 'name', 'email'];
        $sortBy = in_array($request->get('sort_by'), $allowedSort)
            ? $request->get('sort_by')
            : 'created_at';
        $sortOrder = $request->get('sort_order') === 'desc' ? 'desc' : 'asc';
        $search = $request->get('search');

        $query = User::where('status', 0); // Hanya yang pending

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pendingUsers = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return response()->json($pendingUsers);
    }

    /**
     * GET /admin/approval/departments
     */
    public function departments()
    {
        return response()->json(
            Department::orderBy('nama_department')->get()
        );
    }

    /**
     * GET /admin/approval/jabatans?department_id=
     */
    public function jabatans(Request $request)
    {
        $request->validate(['department_id' => 'required|exists:departments,id']);

        $jabatans = Jabatan::where('department_id', $request->department_id)
            ->orderBy('nama_jabatan')
            ->get();

        return response()->json($jabatans);
    }

    /**
     * GET /admin/approval/career-paths?jabatan_id=
     */
    public function careerPaths(Request $request)
    {
        $request->validate(['jabatan_id' => 'required|exists:jabatans,id']);

        $careerPaths = CareerPath::whereHas('jabatans', function ($q) use ($request) {
            $q->where('jabatans.id', $request->jabatan_id);
        })->orderBy('name')->get();

        return response()->json($careerPaths);
    }

    /**
     * POST /admin/approval/users/{userId}/approve
     */
    public function approve(Request $request, $userId)
    {
        $allowedRoles = $request->user()->role === 'super_admin'
            ? ['user', 'admin', 'super_admin']
            : ['user'];

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'career_path_id' => 'required|exists:career_paths,id',
            'role' => ['required', 'string', Rule::in($allowedRoles)],
        ]);

        // Validasi ekstra: pastikan jabatan ada di career path yang dipilih.
        $isValidPath = CareerPath::where('id', $validated['career_path_id'])
            ->whereHas('jabatans', fn($q) => $q->where('jabatans.id', $validated['jabatan_id']))
            ->exists();

        if (!$isValidPath) {
            return response()->json([
                'message' => 'Jabatan tidak terdaftar dalam alur Career Path ini.',
                'errors' => [
                    'jabatan_id' => ['Jabatan tidak terdaftar dalam alur Career Path ini.'],
                ],
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($userId);

            // 1. Update tabel user
            $user->update([
                'department_id' => $validated['department_id'],
                'jabatan_id' => $validated['jabatan_id'],
                'career_path_id' => $validated['career_path_id'],
                'role' => $validated['role'],
                'status' => 1, // Approved
                'email_verified_at' => now(),
            ]);

            // 2. Insert ke pivot career path (bila belum ada)
            if (!$user->careerPaths()->where('career_path_id', $validated['career_path_id'])->exists()) {
                $user->careerPaths()->attach($validated['career_path_id'], [
                    'current_level' => 1,
                    'total_exp' => 0,
                    'is_active' => true,
                    'start_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => "User {$user->name} berhasil di-approve.",
                'user' => $user->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /admin/approval/users/{userId}/reject
     */
    public function reject($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => -1]); // Rejected

        return response()->json([
            'message' => "Pendaftaran {$user->name} telah ditolak.",
        ]);
    }
}
