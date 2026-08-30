<?php

namespace App\Livewire\Admin;

use App\Models\TaskResult;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Laporan Nilai')]
class LaporanNilai extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'status', history: true)]
    public string $statusFilter = 'all'; // all | lulus | gagal

    protected string $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'statusFilter');
        $this->resetPage();
    }

    /**
     * Karena export biasanya berupa file download (bukan re-render halaman),
     * kita tetap arahkan ke route export normal, tapi dibuka lewat event
     * browser supaya UX-nya tetap terasa "reactive" tanpa reload halaman.
     */
    public function exportExcel(): void
    {
        $this->dispatch('open-export-url', url: route('admin.reports.export.excel'));
    }

    public function exportPdf(): void
    {
        $this->dispatch('open-export-url', url: route('admin.reports.export.pdf'));
    }

    /**
     * Ambil daftar peserta (User) yang punya hasil task, dipaginasi per peserta.
     * Untuk tiap peserta, hasil task-nya di-load sekaligus (dengan filter status).
     */
    #[Computed]
    public function participants()
    {
        $userIdsQuery = TaskResult::query()
            ->select('user_id')
            ->distinct()
            ->when($this->search !== '', function ($q) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%"));
            });

        $userIds = $userIdsQuery->pluck('user_id');

        $paginated = User::query()
            ->whereIn('id', $userIds)
            ->orderBy('name')
            ->paginate(10);

        $paginated->getCollection()->transform(function (User $user) {
            $query = TaskResult::with('task')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at');

            if ($this->statusFilter === 'lulus') {
                $query->where(function ($q) {
                    $q->where('status', 'passed')->orWhere('persentase_benar', '>=', 80);
                });
            } elseif ($this->statusFilter === 'gagal') {
                $query->where('status', '!=', 'passed')->where('persentase_benar', '<', 80);
            }

            $user->setAttribute('taskResults', $query->get());

            return $user;
        });

        // Buang peserta yang jadi kosong akibat filter status (tanpa merusak paginasi)
        $paginated->setCollection(
            $paginated->getCollection()->filter(fn(User $u) => $u->taskResults->isNotEmpty())->values()
        );

        return $paginated;
    }

    #[Computed]
    public function stats(): array
    {
        $allResults = TaskResult::query()
            ->when($this->search !== '', function ($q) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%"));
            })
            ->get();

        $passCount = $allResults->filter(
            fn(TaskResult $r) => $r->status === 'passed' || $r->persentase_benar >= 80
        )->count();

        return [
            'total_peserta' => $allResults->pluck('user_id')->unique()->count(),
            'avg_score' => $allResults->avg('persentase_benar') ?? 0,
            'pass_count' => $passCount,
        ];
    }

    public function render()
    {
        return view('livewire.admin.laporan-nilai');
    }
}
