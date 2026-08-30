<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function create(Task $task)
    {
        if ($task->jenis_task !== 'survey') {
            return redirect()->route('admin.task.show', $task)
                ->with('error', 'Hanya task survey yang bisa dikonfigurasi di sini.');
        }

        // Ambil pertanyaan yang sudah ada (jika edit)
        $questions = $task->surveyQuestions()->orderBy('urutan')->get();

        return view('admin.survey.create', compact('task', 'questions'));
    }

    public function store(Request $request, Task $task)
    {
        if ($task->jenis_task !== 'survey') {
            return redirect()->route('admin.task.show', $task)
                ->with('error', 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:500',
            'questions.*.required' => 'nullable|boolean',
        ]);

        // Hapus pertanyaan lama (jika ada)
        $task->surveyQuestions()->delete();

        // Simpan pertanyaan baru
        foreach ($validated['questions'] as $index => $item) {
            SurveyQuestion::create([
                'task_id' => $task->id,
                'pertanyaan' => $item['text'],
                'wajib' => $item['required'] ?? false,
                'urutan' => $index,
            ]);
        }

        // --- BAGIAN INI YANG DIUBAH ---
        // Kembali ke halaman index task, bukan career path
        $departmentId = $task->jabatan->department_id ?? null;

        return redirect()
            ->route('admin.task.index', [
                'department_id' => $departmentId,
                'jabatan_id' => $task->jabatan_id
            ])
            ->with('success', 'Pertanyaan survey berhasil disimpan!');
    }
}
