<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\CareerPath;
use Illuminate\Support\Facades\Auth;

class CareerPathViewer extends Component
{
    public $viewMode = 'index'; // 'index' untuk daftar, 'show' untuk detail
    public $selectedPathId = null;

    public function render()
    {
        $user = Auth::user();
        $data = [];

        // ==========================================
        // MODE 1: DAFTAR JALUR KARIR (INDEX)
        // ==========================================
        if ($this->viewMode === 'index') {
            $myPaths = $user->careerPaths()->with(['levels' => function ($q) {
                $q->orderBy('level', 'asc');
            }])->get();

            $myPaths->transform(function ($path) use ($user) {
                $currentLvlNum = $path->pivot->current_level;
                $currentExp    = $path->pivot->total_exp;

                $currentLevelModel = $path->levels->firstWhere('level', $currentLvlNum);
                $nextLevelModel    = $path->levels->firstWhere('level', $currentLvlNum + 1);

                $expRequired = $nextLevelModel ? $nextLevelModel->exp_required : 0;

                if (!$nextLevelModel) {
                    $percentage = 100;
                    $status = 'Level Maksimal';
                } else {
                    $percentage = ($expRequired > 0) ? min(100, round(($currentExp / $expRequired) * 100)) : 0;
                    $status = 'Sedang Berjalan';
                }

                $path->calculated_current_level = $currentLevelModel;
                $path->calculated_next_level    = $nextLevelModel;
                $path->progress_percentage      = $percentage;
                $path->status_label             = $status;
                $path->is_primary               = ($user->career_path_id == $path->id);

                return $path;
            });

            $data['myPaths'] = $myPaths;
        }

        // ==========================================
        // MODE 2: DETAIL JALUR KARIR (SHOW)
        // ==========================================
        elseif ($this->viewMode === 'show') {
            $careerPath = CareerPath::with(['levels.jabatan'])->findOrFail($this->selectedPathId);

            // Validasi Keamanan: Cek Pivot
            $pivotData = $user->careerPaths()->where('career_path_id', $this->selectedPathId)->first();

            if (!$pivotData) {
                $this->viewMode = 'index';
                session()->flash('error', 'Anda tidak terdaftar di jalur karir ini.');
                return $this->render(); // Paksa render ulang
            }

            $levels = $careerPath->levels->sortBy('level');
            $currentLvlNum = $pivotData->pivot->current_level;
            $totalExp      = $pivotData->pivot->total_exp;

            $currentLevelModel = $levels->firstWhere('level', $currentLvlNum);
            $nextLevelModel    = $levels->firstWhere('level', $currentLvlNum + 1);

            $expRequired = $nextLevelModel ? $nextLevelModel->exp_required : 0;
            $percentage  = (!$nextLevelModel) ? 100 : (($expRequired > 0) ? min(100, round(($totalExp / $expRequired) * 100)) : 0);

            $data = [
                'careerPath' => $careerPath,
                'levels' => $levels,
                'currentLevelModel' => $currentLevelModel,
                'nextLevelModel' => $nextLevelModel,
                'totalExp' => $totalExp,
                'percentage' => $percentage,
                'pivotData' => $pivotData
            ];
        }

        return view('livewire.user.career-path-viewer', $data)->layout('layouts.app');
    }

    // --- LOGIKA NAVIGASI SPA ---
    public function showDetails($pathId)
    {
        $this->selectedPathId = $pathId;
        $this->viewMode = 'show';
    }

    public function backToIndex()
    {
        $this->viewMode = 'index';
        $this->selectedPathId = null;
    }
}
