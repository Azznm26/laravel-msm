<?php

namespace App\Livewire\User;

use App\Models\CareerGapAspect;
use App\Models\CareerLevelStandard;
use App\Models\CareerPathLevel;
use App\Models\UserCompetencyScore;
use App\Support\ProfileMatchingCalculator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Gap Analysis Kompetensi Saya')]
class GapAnalysisViewer extends Component
{
    // 'current' = bandingkan dengan standar level sekarang
    // 'next'    = bandingkan dengan standar level berikutnya (target promosi)
    public string $compareMode = 'next';

    #[Computed]
    public function activeCareerPath()
    {
        // Ambil career path aktif milik user (butuh relasi careerPaths() di model User,
        // pivot: current_level, is_active — sudah ada sebelumnya di User.php)
        return auth()->user()
            ->careerPaths()
            ->wherePivot('is_active', true)
            ->first();
    }

    #[Computed]
    public function currentLevel(): ?CareerPathLevel
    {
        $path = $this->activeCareerPath;

        if (!$path) {
            return null;
        }

        return CareerPathLevel::where('career_path_id', $path->id)
            ->where('level', $path->pivot->current_level)
            ->first();
    }

    #[Computed]
    public function nextLevel(): ?CareerPathLevel
    {
        $current = $this->currentLevel;

        if (!$current) {
            return null;
        }

        return CareerPathLevel::where('career_path_id', $current->career_path_id)
            ->where('level_order', '>', $current->level_order)
            ->orderBy('level_order')
            ->first();
    }

    #[Computed]
    public function targetLevel(): ?CareerPathLevel
    {
        if ($this->compareMode === 'current') {
            return $this->currentLevel;
        }

        // fallback ke current level kalau user sudah di level tertinggi (tidak ada next)
        return $this->nextLevel ?? $this->currentLevel;
    }

    /**
     * Hasil kalkulasi Profile Matching lengkap: per aspek + skor akhir.
     */
    #[Computed]
    public function gapResult(): ?array
    {
        $level = $this->targetLevel;

        if (!$level) {
            return null;
        }

        $standards = CareerLevelStandard::with('criteria.aspect')
            ->where('career_path_level_id', $level->id)
            ->get();

        if ($standards->isEmpty()) {
            return null;
        }

        // Ambil nilai aktual TERBARU per kriteria untuk user ini
        $criteriaIds = $standards->pluck('criteria_id')->unique();

        $latestScores = UserCompetencyScore::where('user_id', auth()->id())
            ->whereIn('criteria_id', $criteriaIds)
            ->orderByDesc('assessed_at')
            ->get()
            ->groupBy('criteria_id')
            ->map(fn($group) => $group->first());

        // Kelompokkan standar per aspek
        $byAspect = $standards->groupBy(fn($s) => $s->criteria->aspect_id);

        $aspects = [];
        $aspectScores = [];

        foreach ($byAspect as $aspectId => $rows) {
            $aspect = $rows->first()->criteria->aspect;

            $calcRows = $rows->map(function ($standard) use ($latestScores) {
                $score = $latestScores->get($standard->criteria_id);

                return [
                    'criteria' => $standard->criteria,
                    'target' => $standard->target_value,
                    'actual' => $score?->actual_value ?? 0, // belum pernah dinilai = 0
                ];
            });

            $missingAssessment = $calcRows->contains(fn($r) => $r['actual'] === 0);

            $result = ProfileMatchingCalculator::calculateAspect(
                $calcRows,
                (float) $aspect->cf_weight,
                (float) $aspect->sf_weight
            );

            $aspects[] = [
                'aspect' => $aspect,
                'ncf' => $result['ncf'],
                'nsf' => $result['nsf'],
                'aspect_score' => $result['aspect_score'],
                'details' => $result['details'],
                'has_missing_assessment' => $missingAssessment,
            ];

            $aspectScores[] = $result['aspect_score'];
        }

        $finalScore = count($aspectScores) > 0
            ? round(array_sum($aspectScores) / count($aspectScores), 2)
            : 0;

        return [
            'level' => $level,
            'aspects' => $aspects,
            'final_score' => $finalScore,
            // skala 1-5 -> persentase, memudahkan tampilan progress bar
            'final_percentage' => round(($finalScore / 5) * 100, 1),
        ];
    }

    public function render()
    {
        return view('livewire.user.gap-analysis-viewer');
    }
}
