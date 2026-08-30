<?php

namespace App\Livewire\Admin;

use App\Models\CareerGapAspect;
use App\Models\CareerGapCriteria;
use App\Models\CareerLevelStandard;
use App\Models\CareerPath;
use App\Models\CareerPathLevel;
use App\Models\User;
use App\Models\UserCompetencyScore;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kelola Gap Analysis')]
class CompetencyManager extends Component
{
    public string $activeTab = 'aspects'; // aspects | standards | scores

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // =====================================================================
    // TAB 1: ASPEK & KRITERIA
    // =====================================================================

    public bool $showAspectForm = false;
    public ?int $editingAspectId = null;
    public string $aspectName = '';
    public float $aspectCfWeight = 60;
    public float $aspectSfWeight = 40;
    public int $aspectOrder = 0;

    public ?int $addingCriteriaForAspectId = null;
    public ?int $editingCriteriaId = null;
    public string $criteriaName = '';
    public string $criteriaFactorType = 'core';
    public int $criteriaOrder = 0;

    #[Computed]
    public function aspects()
    {
        return CareerGapAspect::with('criteria')->orderBy('order')->get();
    }

    public function newAspectForm(): void
    {
        $this->reset(['editingAspectId', 'aspectName', 'aspectOrder']);
        $this->aspectCfWeight = 60;
        $this->aspectSfWeight = 40;
        $this->showAspectForm = true;
    }

    public function editAspect(int $id): void
    {
        $aspect = CareerGapAspect::findOrFail($id);
        $this->editingAspectId = $aspect->id;
        $this->aspectName = $aspect->name;
        $this->aspectCfWeight = (float) $aspect->cf_weight;
        $this->aspectSfWeight = (float) $aspect->sf_weight;
        $this->aspectOrder = $aspect->order;
        $this->showAspectForm = true;
    }

    public function saveAspect(): void
    {
        $this->validate([
            'aspectName' => 'required|string|max:255',
            'aspectCfWeight' => 'required|numeric|min:0|max:100',
            'aspectSfWeight' => 'required|numeric|min:0|max:100',
        ]);

        if (round($this->aspectCfWeight + $this->aspectSfWeight, 2) !== 100.0) {
            $this->addError('aspectSfWeight', 'Total Core Factor + Secondary Factor harus 100%.');
            return;
        }

        CareerGapAspect::updateOrCreate(
            ['id' => $this->editingAspectId],
            [
                'name' => $this->aspectName,
                'cf_weight' => $this->aspectCfWeight,
                'sf_weight' => $this->aspectSfWeight,
                'order' => $this->aspectOrder,
            ]
        );

        session()->flash('success', 'Aspek berhasil disimpan.');
        $this->showAspectForm = false;
        unset($this->aspects);
    }

    public function deleteAspect(int $id): void
    {
        CareerGapAspect::findOrFail($id)->delete();
        session()->flash('success', 'Aspek dan kriteria di dalamnya berhasil dihapus.');
        unset($this->aspects);
    }

    public function cancelAspectForm(): void
    {
        $this->showAspectForm = false;
        $this->resetErrorBag();
    }

    // --- Kriteria ---

    public function newCriteriaForm(int $aspectId): void
    {
        $this->reset(['editingCriteriaId', 'criteriaName', 'criteriaOrder']);
        $this->criteriaFactorType = 'core';
        $this->addingCriteriaForAspectId = $aspectId;
    }

    public function editCriteria(int $id): void
    {
        $criteria = CareerGapCriteria::findOrFail($id);
        $this->editingCriteriaId = $criteria->id;
        $this->addingCriteriaForAspectId = $criteria->aspect_id;
        $this->criteriaName = $criteria->name;
        $this->criteriaFactorType = $criteria->factor_type;
        $this->criteriaOrder = $criteria->order;
    }

    public function saveCriteria(): void
    {
        $this->validate([
            'criteriaName' => 'required|string|max:255',
            'criteriaFactorType' => 'required|in:core,secondary',
        ]);

        CareerGapCriteria::updateOrCreate(
            ['id' => $this->editingCriteriaId],
            [
                'aspect_id' => $this->addingCriteriaForAspectId,
                'name' => $this->criteriaName,
                'factor_type' => $this->criteriaFactorType,
                'order' => $this->criteriaOrder,
            ]
        );

        session()->flash('success', 'Kriteria berhasil disimpan.');
        $this->cancelCriteriaForm();
        unset($this->aspects);
    }

    public function deleteCriteria(int $id): void
    {
        CareerGapCriteria::findOrFail($id)->delete();
        session()->flash('success', 'Kriteria berhasil dihapus.');
        unset($this->aspects);
    }

    public function cancelCriteriaForm(): void
    {
        $this->addingCriteriaForAspectId = null;
        $this->editingCriteriaId = null;
        $this->resetErrorBag();
    }

    // =====================================================================
    // TAB 2: STANDAR PER LEVEL
    // =====================================================================

    public $standardsCareerPathId = '';
    public $standardsLevelId = '';
    public array $standardValues = []; // [criteria_id => target_value]

    #[Computed]
    public function careerPathsList()
    {
        return CareerPath::orderBy('name')->get();
    }

    #[Computed]
    public function levelsForStandards()
    {
        if (!$this->standardsCareerPathId) {
            return collect();
        }

        return CareerPathLevel::where('career_path_id', $this->standardsCareerPathId)
            ->orderBy('level')
            ->get();
    }

    public function updatedStandardsCareerPathId(): void
    {
        $this->standardsLevelId = '';
        $this->standardValues = [];
    }

    public function updatedStandardsLevelId(): void
    {
        $this->standardValues = [];

        if (!$this->standardsLevelId) {
            return;
        }

        $existing = CareerLevelStandard::where('career_path_level_id', $this->standardsLevelId)
            ->pluck('target_value', 'criteria_id');

        foreach ($this->aspects as $aspect) {
            foreach ($aspect->criteria as $criteria) {
                $this->standardValues[$criteria->id] = $existing[$criteria->id] ?? 3;
            }
        }
    }

    public function saveStandards(): void
    {
        $this->validate([
            'standardsLevelId' => 'required|exists:career_path_levels,id',
        ]);

        foreach ($this->standardValues as $criteriaId => $value) {
            CareerLevelStandard::updateOrCreate(
                [
                    'career_path_level_id' => $this->standardsLevelId,
                    'criteria_id' => $criteriaId,
                ],
                ['target_value' => max(1, min(5, (int) $value))]
            );
        }

        session()->flash('success', 'Standar kompetensi level berhasil disimpan.');
    }

    // =====================================================================
    // TAB 3: PENILAIAN KOMPETENSI USER
    // =====================================================================

    public string $userSearch = '';
    public ?int $selectedUserId = null;
    public array $scoreValues = []; // [criteria_id => actual_value]
    public string $scoreNotes = '';
    public string $scoreAssessedAt = '';

    public function mount(): void
    {
        $this->scoreAssessedAt = now()->format('Y-m-d');
    }

    #[Computed]
    public function userSearchResults()
    {
        if (strlen($this->userSearch) < 2) {
            return collect();
        }

        return User::where('role', '!=', 'super_admin')
            ->where('name', 'like', "%{$this->userSearch}%")
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function selectedUser()
    {
        return $this->selectedUserId ? User::find($this->selectedUserId) : null;
    }

    #[Computed]
    public function scoreHistory()
    {
        if (!$this->selectedUserId) {
            return collect();
        }

        return UserCompetencyScore::with('criteria.aspect', 'assessor')
            ->where('user_id', $this->selectedUserId)
            ->orderByDesc('assessed_at')
            ->limit(20)
            ->get();
    }

    public function selectUserForScoring(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->userSearch = '';
        $this->scoreNotes = '';
        $this->scoreValues = [];

        // Prefill dengan nilai terakhir yang pernah diinput, biar admin tinggal koreksi
        $latest = UserCompetencyScore::where('user_id', $userId)
            ->orderByDesc('assessed_at')
            ->get()
            ->groupBy('criteria_id')
            ->map(fn($g) => $g->first()->actual_value);

        foreach ($this->aspects as $aspect) {
            foreach ($aspect->criteria as $criteria) {
                $this->scoreValues[$criteria->id] = $latest[$criteria->id] ?? 3;
            }
        }
    }

    public function clearSelectedUser(): void
    {
        $this->reset(['selectedUserId', 'scoreValues', 'scoreNotes']);
    }

    public function saveScores(): void
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'scoreAssessedAt' => 'required|date',
        ]);

        foreach ($this->scoreValues as $criteriaId => $value) {
            UserCompetencyScore::create([
                'user_id' => $this->selectedUserId,
                'criteria_id' => $criteriaId,
                'actual_value' => max(1, min(5, (int) $value)),
                'assessed_at' => $this->scoreAssessedAt,
                'assessed_by' => auth()->id(),
                'notes' => $this->scoreNotes ?: null,
            ]);
        }

        session()->flash('success', "Penilaian kompetensi untuk {$this->selectedUser->name} berhasil disimpan.");
        $this->scoreNotes = '';
        unset($this->scoreHistory);
    }

    public function render()
    {
        return view('livewire.admin.competency-manager');
    }
}
