<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class AdminHistoryController extends Controller
{
    /**
     * Menampilkan Riwayat User (Diterima, Pending, Ditolak)
     * Lengkap dengan Sorting, Filtering, dan Searching.
     */
    public function index(Request $request)
    {
        // 1. Base Query
        // Kita gunakan select('users.*') agar ID tidak tertimpa saat join
        $query = User::with(['department', 'jabatan'])
            ->where('role', '!=', 'super_admin') // Sembunyikan super admin
            ->select('users.*');

        // 2. Join Table (Untuk keperluan Sorting berdasarkan nama Dept/Jabatan)
        $query->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->leftJoin('jabatans', 'users.jabatan_id', '=', 'jabatans.id');

        // 3. SEARCHING (Nama, Email, ID Badge)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%")
                    ->orWhere('users.id_badge', 'like', "%$search%");
            });
        }

        // 4. FILTER STATUS
        if ($request->filled('status')) {
            $query->where('users.status', $request->status);
        }

        // 5. SORTING (Dynamic)
        $sortBy = $request->get('sort_by', 'created_at'); // Default: Waktu Daftar
        $sortOrder = $request->get('sort_order', 'desc'); // Default: Terbaru (Desc)

        switch ($sortBy) {
            case 'department':
                $query->orderBy('departments.nama_department', $sortOrder);
                break;
            case 'jabatan':
                $query->orderBy('jabatans.nama_jabatan', $sortOrder);
                break;
            case 'name':
                $query->orderBy('users.name', $sortOrder);
                break;
            default: // created_at
                $query->orderBy('users.created_at', $sortOrder);
                break;
        }

        // 6. Pagination & Data Pendukung
        $users = $query->paginate(10)->withQueryString();
        $departments = Department::all(); // Untuk dropdown filter di view

        return view('admin.history.index', compact('users', 'departments'));
    }

    // Tambahkan method ini di dalam AdminHistoryController

    public function exportExcel(Request $request)
    {
        // Ambil data menggunakan filter yang sama dengan index (copy logic filter dari index)
        $query = User::with(['department', 'jabatan'])->where('role', '!=', 'super_admin');

        // ... (masukkan logika filter search, status, dept disini sama persis dengan index) ...
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('id_badge', 'like', "%$search%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        $users = $query->get();

        // Generate CSV (Excel Compatible)
        $filename = "history-user-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        // Header Download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Header Kolom
        fputcsv($handle, ['No', 'Nama', 'Email', 'ID Badge', 'Departemen', 'Jabatan', 'Status', 'Tanggal Daftar']);

        foreach ($users as $index => $user) {
            $statusLabel = match ($user->status) {
                1 => 'Aktif',
                0 => 'Pending',
                -1 => 'Ditolak',
                default => 'Unknown'
            };

            fputcsv($handle, [
                $index + 1,
                $user->name,
                $user->email,
                $user->id_badge,
                $user->department->nama_department ?? '-',
                $user->jabatan->nama_jabatan ?? '-',
                $statusLabel,
                $user->created_at->format('d-m-Y H:i')
            ]);
        }

        fclose($handle);
        exit;
    }

    public function exportPdf(Request $request)
    {
        // Untuk PDF, cara termudah tanpa library berat adalah membuat view khusus print
        // Lalu user tinggal Ctrl+P -> Save as PDF

        // Logic filter sama
        $query = User::with(['department', 'jabatan'])->where('role', '!=', 'super_admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('id_badge', 'like', "%$search%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        $users = $query->get();

        // Buat view khusus print (misal: admin.history.print)
        // Atau return view index tapi dengan mode 'print'
        return view('admin.history.print', compact('users'));
    }
}
