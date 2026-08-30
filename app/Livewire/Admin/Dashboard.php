<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Task;
use App\Models\Jabatan;
use App\Models\Department;
use App\Models\TaskResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class Dashboard extends Component
{
    // Filter pencarian untuk daftar user online (real-time, tanpa reload)
    public string $searchOnline = '';

    // Ambang batas tingkat kelulusan (%) untuk memicu alert departemen berisiko
    protected int $deptAlertThreshold = 60;

    // Berapa lama statistik non-realtime di-cache (menit)
    protected int $cacheMinutes = 5;

    public function render()
    {
        // ================================================================
        // BAGIAN REAL-TIME (tidak di-cache — memang harus selalu terbaru)
        // ================================================================

        // User Online (+ filter pencarian)
        $onlineUsersQuery = User::with('jabatan')
            ->where('last_seen', '>=', now()->subMinutes(5))
            ->orderBy('last_seen', 'desc');

        if (trim($this->searchOnline) !== '') {
            $onlineUsersQuery->where('name', 'like', '%' . $this->searchOnline . '%');
        }

        $onlineUsers = $onlineUsersQuery->limit(50)->get();
        $onlineCount = User::where('last_seen', '>=', now()->subMinutes(5))->count();

        // ================================================================
        // BAGIAN CACHED (statistik berat, tidak perlu update tiap detik)
        // ================================================================

        $stats = Cache::remember('admin.dashboard.stats', now()->addMinutes($this->cacheMinutes), function () {
            return $this->computeStats();
        });

        // Gabungkan card "User Online" (real-time) ke cards hasil cache
        $cards = $stats['cards'];
        $cards[] = ['title' => 'User Online', 'count' => $onlineCount, 'icon' => 'wifi', 'color' => 'green'];

        // Kirim semua variabel ke view
        return view('livewire.admin.dashboard', array_merge(
            $stats,
            [
                'cards' => $cards,
                'onlineUsers' => $onlineUsers,
            ]
        ))->layout('layouts.admin');
    }

    /**
     * Hitung semua statistik berat dalam satu batch.
     * Dipanggil lewat Cache::remember agar tidak dihitung ulang tiap request.
     */
    protected function computeStats(): array
    {
        // 1. Statistik Cards dasar
        $totalTask = Task::count();
        $totalJabatan = Jabatan::count();
        $totalDept = Department::count();
        $totalUser = User::count();
        $totalPengerjaan = TaskResult::count();

        $cards = [
            ['title' => 'Total Task', 'count' => $totalTask, 'icon' => 'list-checks', 'color' => 'emerald'],
            ['title' => 'Total Jabatan', 'count' => $totalJabatan, 'icon' => 'briefcase', 'color' => 'blue'],
            ['title' => 'Total Dept', 'count' => $totalDept, 'icon' => 'building-2', 'color' => 'indigo'],
            ['title' => 'Total User', 'count' => $totalUser, 'icon' => 'users', 'color' => 'amber'],
            ['title' => 'Total Submit', 'count' => $totalPengerjaan, 'icon' => 'file-text', 'color' => 'rose'],
        ];

        // 2. Rata-rata Nilai per Task
        $avgScores = DB::table('task_results')
            ->join('tasks', 'task_results.task_id', '=', 'tasks.id')
            ->select('tasks.judul', DB::raw('AVG(task_results.persentase_benar) as avg_score'))
            ->groupBy('tasks.id', 'tasks.judul')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        // 3. Tingkat Kelulusan Global — dihitung di DB, bukan ->all()
        $totalAttempts = $totalPengerjaan;
        $passedAttempts = TaskResult::where(function ($q) {
            $q->where('status', 'passed')
                ->orWhere('persentase_benar', '>=', 80);
        })
            ->count();

        $accuracy = $totalAttempts ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0;
        $failedAttempts = $totalAttempts - $passedAttempts;

        // 4. Aktivitas Terbaru
        $recentActivities = TaskResult::with(['user', 'task'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 5. Grafik: Submit per Hari (7 hari terakhir) — 1 query, bukan 7
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $dailyRaw = TaskResult::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as count')
            ->groupBy('tanggal')
            ->pluck('count', 'tanggal');

        $submissionsPerDay = collect(range(6, 0))->map(function ($daysAgo) use ($dailyRaw) {
            $date = Carbon::now()->subDays($daysAgo)->startOfDay();
            return [
                'label' => $date->translatedFormat('D, d M'),
                'count' => (int) ($dailyRaw[$date->toDateString()] ?? 0),
            ];
        });

        // 5b. Growth: submit hari ini vs kemarin
        $submitToday = (int) ($dailyRaw[Carbon::today()->toDateString()] ?? 0);
        $submitYesterday = (int) ($dailyRaw[Carbon::yesterday()->toDateString()] ?? 0);

        if ($submitYesterday > 0) {
            $growthPercent = round((($submitToday - $submitYesterday) / $submitYesterday) * 100, 1);
        } else {
            $growthPercent = $submitToday > 0 ? 100.0 : 0.0;
        }
        $growthDirection = $growthPercent > 0 ? 'up' : ($growthPercent < 0 ? 'down' : 'flat');

        // 6. Grafik: Lulus vs Tidak Lulus
        $passFailChart = [
            'labels' => ['Lulus', 'Tidak Lulus'],
            'data' => [$passedAttempts, $failedAttempts],
        ];

        // 7. Grafik: Jumlah User per Department
        $usersPerDept = Department::withCount('users')
            ->orderByDesc('users_count')
            ->get()
            ->map(function ($dept) {
                return [
                    'label' => $dept->nama_department,
                    'count' => $dept->users_count,
                ];
            });

        // 8. Grafik: Rata-rata Skor per Task (reuse avgScores)
        $avgScoreChart = [
            'labels' => $avgScores->pluck('judul'),
            'data' => $avgScores->pluck('avg_score')->map(fn($v) => round($v, 1)),
        ];

        // 9. Top Performer: 5 user dengan rata-rata skor tertinggi
        $topPerformers = DB::table('task_results')
            ->join('users', 'task_results.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('AVG(task_results.persentase_benar) as avg_score'),
                DB::raw('COUNT(task_results.id) as attempts')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        // 10. Alert Departemen Berisiko
        $deptAlerts = collect();
        if (Schema::hasColumn('users', 'department_id')) {
            if (Schema::hasColumn('departments', 'nama_department')) {
                $deptLabelColumn = 'nama_department';
            } elseif (Schema::hasColumn('departments', 'nama')) {
                $deptLabelColumn = 'nama';
            } else {
                $deptLabelColumn = 'name';
            }

            $deptAlerts = DB::table('task_results')
                ->join('users', 'task_results.user_id', '=', 'users.id')
                ->join('departments', 'users.department_id', '=', 'departments.id')
                ->select(
                    'departments.id',
                    "departments.{$deptLabelColumn} as label",
                    DB::raw('COUNT(task_results.id) as total'),
                    DB::raw("SUM(CASE WHEN task_results.status = 'passed' OR task_results.persentase_benar >= 80 THEN 1 ELSE 0 END) as passed")
                )
                ->groupBy('departments.id', "departments.{$deptLabelColumn}")
                ->havingRaw('total >= 3')
                ->get()
                ->map(function ($row) {
                    $row->pass_rate = $row->total > 0 ? round(($row->passed / $row->total) * 100, 1) : 0;
                    return $row;
                })
                ->filter(fn($row) => $row->pass_rate < $this->deptAlertThreshold)
                ->values();
        }

        return compact(
            'cards',
            'avgScores',
            'totalAttempts',
            'passedAttempts',
            'accuracy',
            'failedAttempts',
            'recentActivities',
            'submissionsPerDay',
            'passFailChart',
            'usersPerDept',
            'avgScoreChart',
            'growthPercent',
            'growthDirection',
            'topPerformers',
            'deptAlerts'
        );
    }
}
