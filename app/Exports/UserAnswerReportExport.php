<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UserAnswerReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('user_answers')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->select(
                'users.name as nama_user',
                DB::raw("'Task ' || user_answers.task_id as task"),
                DB::raw("'Soal ' || user_answers.question_id as soal"),
                'user_answers.answer as jawaban',
                'user_answers.score as nilai'
            )
            ->orderBy('users.name')
            ->orderBy('user_answers.task_id')
            ->orderBy('user_answers.question_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama User',
            'Task',
            'Soal',
            'Jawaban',
            'Nilai'
        ];
    }
}
