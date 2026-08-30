<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Department;
use App\Models\Jabatan;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf; // TAMBAHKAN IMPORT INI (Asumsi menggunakan laravel-dompdf)

class CareerMonitoringManager extends Component
{
    use WithPagination;

    // Mode SPA
    public $viewMode = 'list';

    // Filters & Sorting
    public $search = '';
    public $departmentId = '';
    public $jabatanId = '';
    public $sortBy = 'total_exp';
    public $sortOrder = 'desc';

    // State Data Master
    public $departments = [];
    public $availableJabatans = [];

    // State Detail User
    public $activeUser = null;
    public $careerPathsData = [];

    public function mount($user = null)
    {
        $this->departments = Department::orderBy('nama_department')->get();

        if ($user) {
            $userId = is_object($user) ? $user->id : $user;
            $this->showDetail($userId);
        }
    }

    // Reset pagination ketika filter berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedDepartmentId()
    {
        $this->resetPage();
        $this->jabatanId = '';
        $this->availableJabatans = $this->departmentId
            ? Jabatan::where('department_id', $this->departmentId)->orderBy('nama_jabatan')->get()
            : [];
    }
    public function updatedJabatanId()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'asc';
        }
    }

    public function render()
    {
        $processedData = $this->getMonitoringData();

        // Manual Sorting untuk Collection
        if ($this->sortBy === 'name') {
            $processedData = ($this->sortOrder === 'asc')
                ? $processedData->sortBy(fn($item) => $item['name'])
                : $processedData->sortByDesc(fn($item) => $item['name']);
        } else {
            $processedData = ($this->sortOrder === 'asc')
                ? $processedData->sortBy($this->sortBy)
                : $processedData->sortByDesc($this->sortBy);
        }

        // Manual Pagination
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $processedData->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $usersWithProgress = new LengthAwarePaginator(
            $currentItems,
            $processedData->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        return view('livewire.admin.career-monitoring-manager', compact('usersWithProgress'))
            ->layout('layouts.admin');
    }

    // ==========================================
    // DETAIL VIEW LOGIC
    // ==========================================
    public function showDetail($userId)
    {
        $this->activeUser = User::with(['department', 'jabatan', 'careerPaths.levels'])->findOrFail($userId);

        $this->careerPathsData = $this->activeUser->careerPaths->map(function ($path) {
            $currentLevelNum = $path->pivot->current_level;
            $totalExp = $path->pivot->total_exp;

            $currentLevelModel = $path->levels->firstWhere('level', $currentLevelNum);
            $nextLevelModel = $path->levels->firstWhere('level', $currentLevelNum + 1);

            $targetExp = $nextLevelModel ? $nextLevelModel->exp_required : 0;

            $percentage = ($targetExp > 0)
                ? min(100, round(($totalExp / $targetExp) * 100, 1))
                : ($nextLevelModel ? 0 : 100);

            $path->monitoring_current_level = $currentLevelNum;
            $path->monitoring_current_title = $currentLevelModel ? $currentLevelModel->name : 'Level ' . $currentLevelNum;
            $path->monitoring_total_exp = $totalExp;
            $path->monitoring_next_exp = $targetExp;
            $path->monitoring_percentage = $percentage;
            $path->monitoring_status = $nextLevelModel ? 'In Progress' : 'Max Level';

            return $path;
        });

        $this->viewMode = 'detail';
    }

    public function backToList()
    {
        $this->viewMode = 'list';
        $this->activeUser = null;
        $this->careerPathsData = [];
    }

    // ==========================================
    // DATA FETCHING HELPER
    // ==========================================
    private function getMonitoringData()
    {
        $usersQuery = User::where('status', 1)
            ->where('role', 'user')
            ->with(['jabatan', 'department', 'careerPaths'])
            ->when($this->departmentId, fn($q) => $q->where('department_id', $this->departmentId))
            ->when($this->jabatanId, fn($q) => $q->where('jabatan_id', $this->jabatanId))
            ->when($this->search, function ($q) {
                $q->where(fn($sub) => $sub->where('name', 'like', "%{$this->search}%")
                    ->orWhere('id_badge', 'like', "%{$this->search}%"));
            });

        return $usersQuery->get()->map(function ($user) {
            $primaryPathPivot = $user->careerPaths->firstWhere('id', $user->career_path_id);
            $secondaryPaths = $user->careerPaths->where('id', '!=', $user->career_path_id);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'id_badge' => $user->id_badge ?? '-',
                'department_name' => $user->department->nama_department ?? 'N/A',
                'jabatan_name' => $user->jabatan->nama_jabatan ?? 'N/A',
                'primary_path_name' => $primaryPathPivot ? $primaryPathPivot->name : '-',
                'current_level' => $primaryPathPivot ? $primaryPathPivot->pivot->current_level : 1,
                'primary_exp' => $primaryPathPivot ? $primaryPathPivot->pivot->total_exp : 0,
                'total_exp' => $user->careerPaths->sum('pivot.total_exp'),
                'secondary_paths_count' => $secondaryPaths->count(),
            ];
        });
    }

    // ==========================================
    // EXPORT LOGIC
    // ==========================================
    public function exportExcel()
    {
        $data = $this->getMonitoringData();
        $filename = "career-monitoring-" . date('Y-m-d') . ".csv";

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama Karyawan', 'ID Badge', 'Departemen', 'Jabatan Utama', 'Jalur Utama', 'Level Utama', 'Total EXP Global']);

            $no = 1;
            foreach ($data as $row) {
                fputcsv($handle, [
                    $no++,
                    $row['name'],
                    $row['id_badge'],
                    $row['department_name'],
                    $row['jabatan_name'],
                    $row['primary_path_name'],
                    $row['current_level'],
                    $row['total_exp']
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    // ==========================================
    // TAMBAHAN: EXPORT PDF LOGIC
    // ==========================================
    public function exportPdf()
    {
        // 1. Ambil data yang sudah difilter/diproses
        $data = $this->getMonitoringData();

        // 2. Tentukan nama file
        $filename = "career-monitoring-" . date('Y-m-d') . ".pdf";

        // 3. Render PDF dari view (pastikan Anda membuat file view ini)
        // Disini saya mengirimkan variable $data dengan key 'monitoringData'
        $pdf = Pdf::loadView('pdf.career-monitoring', [
            'monitoringData' => $data
        ]);

        // 4. Return stream download agar bekerja dengan baik di Livewire (SPA)
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}
