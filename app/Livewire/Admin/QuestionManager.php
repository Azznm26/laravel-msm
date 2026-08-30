<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Task;
use App\Models\Question;
use App\Services\AIGeneratorService;

class QuestionManager extends Component
{
    public Task $task;

    public $viewMode = 'list';

    public $newQuestions = [];
    public $activeIndex = 0;

    public $editId;
    public $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, $jawaban_benar, $skor;

    public $aiJabatan = 'IT Support';
    public $aiKompetensi = 'Networking & Hardware';
    public $aiJumlahSoal = 5;

    // ✅ BARU: State untuk Modal Konfirmasi Hapus
    public $showDeleteModal = false;
    public $deleteType = null;   // 'field' (wizard) atau 'question' (list tersimpan)
    public $deleteTarget = null; // index (untuk field) atau id (untuk question)

    public function mount(Task $task)
    {
        if (!auth()->user()->can('manage-tasks')) {
            abort(403, 'Akses ditolak.');
        }

        if ($task->jenis_task !== 'pilihan_ganda') {
            abort(403, 'Soal hanya bisa dibuat untuk task jenis Pilihan Ganda.');
        }

        $this->task = $task;

        if ($this->task->questions()->count() === 0) {
            $this->create();
        }
    }

    public function render()
    {
        $questions = $this->task->questions()->latest()->get();
        return view('livewire.admin.question-manager', compact('questions'))->layout('layouts.admin');
    }

    // ==========================================
    // LOGIKA GENERATE AI
    // ==========================================
    public function generateWithAI(AIGeneratorService $aiService)
    {
        $this->validate([
            'aiJabatan' => 'required|string',
            'aiKompetensi' => 'required|string',
            'aiJumlahSoal' => 'required|integer|min:1|max:10'
        ]);

        $generatedQuestions = $aiService->generateQuiz($this->aiJabatan, $this->aiKompetensi, $this->aiJumlahSoal);

        if ($generatedQuestions) {
            $this->viewMode = 'create';

            if (count($this->newQuestions) === 1 && $this->isQuestionEmpty($this->newQuestions[0])) {
                $this->newQuestions = [];
            }

            $startIndex = count($this->newQuestions);

            foreach ($generatedQuestions as $q) {
                $this->newQuestions[] = [
                    'text' => $q['pertanyaan'] ?? '',
                    'option_a' => $q['pilihan_a'] ?? '',
                    'option_b' => $q['pilihan_b'] ?? '',
                    'option_c' => $q['pilihan_c'] ?? '',
                    'option_d' => $q['pilihan_d'] ?? '',
                    'correct_option' => strtoupper($q['jawaban_benar'] ?? 'A'),
                    'score' => $q['skor'] ?? 10,
                ];
            }

            $this->activeIndex = $startIndex;

            session()->flash('success', count($generatedQuestions) . ' soal berhasil di-generate AI. Silakan review sebelum menyimpan.');
        } else {
            session()->flash('error', 'Gagal menghubungi server AI. Silakan coba lagi.');
        }
    }

    private function isQuestionEmpty($question)
    {
        return empty($question['text'])
            && empty($question['option_a'])
            && empty($question['option_b'])
            && empty($question['option_c'])
            && empty($question['option_d']);
    }

    // ==========================================
    // LOGIKA CREATE MULTIPLE QUESTIONS
    // ==========================================
    public function create()
    {
        $this->newQuestions = [];
        $this->addQuestionField();
        $this->activeIndex = 0;
        $this->viewMode = 'create';
    }

    public function addQuestionField()
    {
        $this->newQuestions[] = [
            'text' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
            'correct_option' => 'A',
            'score' => 10,
        ];

        $this->activeIndex = count($this->newQuestions) - 1;
    }

    // ✅ BARU: Buka modal konfirmasi untuk hapus field soal di wizard
    public function confirmRemoveField($index)
    {
        $this->deleteType = 'field';
        $this->deleteTarget = $index;
        $this->showDeleteModal = true;
    }

    public function removeQuestionField($index)
    {
        unset($this->newQuestions[$index]);
        $this->newQuestions = array_values($this->newQuestions);

        $lastIndex = max(count($this->newQuestions) - 1, 0);
        $this->activeIndex = min($this->activeIndex, $lastIndex);
    }

    public function goToQuestion($index)
    {
        if (isset($this->newQuestions[$index])) {
            $this->activeIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->activeIndex < count($this->newQuestions) - 1) {
            $this->activeIndex++;
        }
    }

    public function prevQuestion()
    {
        if ($this->activeIndex > 0) {
            $this->activeIndex--;
        }
    }

    public function storeQuestions()
    {
        $this->validate([
            'newQuestions' => 'required|array|min:1',
            'newQuestions.*.text' => 'required|string',
            'newQuestions.*.option_a' => 'required|string',
            'newQuestions.*.option_b' => 'required|string',
            'newQuestions.*.option_c' => 'required|string',
            'newQuestions.*.option_d' => 'required|string',
            'newQuestions.*.correct_option' => 'required|in:A,B,C,D',
            'newQuestions.*.score' => 'required|integer|min:1',
        ], [
            'newQuestions.*.*.required' => 'Semua kolom pada soal wajib diisi.'
        ]);

        foreach ($this->newQuestions as $item) {
            Question::create([
                'task_id' => $this->task->id,
                'pertanyaan' => $item['text'],
                'pilihan_a' => $item['option_a'],
                'pilihan_b' => $item['option_b'],
                'pilihan_c' => $item['option_c'],
                'pilihan_d' => $item['option_d'],
                'jawaban_benar' => strtolower($item['correct_option']),
                'skor' => $item['score'],
            ]);
        }

        $this->task->refresh();
        $this->task->recalculateTotalScore();

        session()->flash('success', count($this->newQuestions) . ' soal berhasil ditambahkan.');
        $this->viewMode = 'list';
    }

    // ==========================================
    // LOGIKA EDIT & UPDATE SINGLE QUESTION
    // ==========================================
    public function edit($id)
    {
        $q = Question::findOrFail($id);
        $this->editId = $q->id;
        $this->pertanyaan = $q->pertanyaan;
        $this->pilihan_a = $q->pilihan_a;
        $this->pilihan_b = $q->pilihan_b;
        $this->pilihan_c = $q->pilihan_c;
        $this->pilihan_d = $q->pilihan_d;
        $this->jawaban_benar = strtoupper($q->jawaban_benar);
        $this->skor = $q->skor;

        $this->viewMode = 'edit';
    }

    public function updateQuestion()
    {
        $this->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'skor' => 'required|numeric|min:1'
        ]);

        $q = Question::findOrFail($this->editId);
        $q->update([
            'pertanyaan' => $this->pertanyaan,
            'pilihan_a' => $this->pilihan_a,
            'pilihan_b' => $this->pilihan_b,
            'pilihan_c' => $this->pilihan_c,
            'pilihan_d' => $this->pilihan_d,
            'jawaban_benar' => strtolower($this->jawaban_benar),
            'skor' => $this->skor,
        ]);

        $this->task->refresh();
        $this->task->recalculateTotalScore();

        session()->flash('success', 'Pertanyaan berhasil diperbarui.');
        $this->viewMode = 'list';
    }

    // ==========================================
    // LOGIKA DELETE
    // ==========================================

    // ✅ BARU: Buka modal konfirmasi untuk hapus soal tersimpan di list
    public function confirmDeleteQuestion($id)
    {
        $this->deleteType = 'question';
        $this->deleteTarget = $id;
        $this->showDeleteModal = true;
    }

    public function delete($id)
    {
        $q = Question::findOrFail($id);
        $q->delete();

        $this->task->refresh();
        $this->task->recalculateTotalScore();

        session()->flash('success', 'Pertanyaan berhasil dihapus.');
    }

    // ✅ BARU: Dipanggil saat user klik "OK" di modal
    public function executeDelete()
    {
        if ($this->deleteType === 'field') {
            $this->removeQuestionField($this->deleteTarget);
        } elseif ($this->deleteType === 'question') {
            $this->delete($this->deleteTarget);
        }

        $this->cancelDelete();
    }

    // ✅ BARU: Dipanggil saat user klik "Batal" atau tutup modal
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteType = null;
        $this->deleteTarget = null;
    }

    public function backToList()
    {
        $this->viewMode = 'list';
    }
}
