<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'deadline',
        'jenis_task',
        'total_skor',
        'jumlah_soal_awal',
        // 'target_level',         // 🗑️ Dihapus
        'jabatan_id',
        // 'career_path_id',       // 🗑️ Dihapus
        'exp_reward',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'total_skor' => 'integer',
        'jumlah_soal_awal' => 'integer',
        'exp_reward' => 'integer',
    ];

    // =======================================================
    // 🔗 RELASI YANG DIPERTAHANKAN (Task -> Soal/Jabatan/Pengguna)
    // =======================================================

    // ✅ Relasi ke Jabatan (Kunci baru untuk menargetkan Task)
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

  
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function uploadedFiles()
    {
        return $this->hasMany(UserUploadedFile::class);
    }

    // =======================================================
    // ⚙️ FUNGSI LAIN
    // =======================================================

    // 🔄 Hitung ulang total skor dari pertanyaan
    public function recalculateTotalScore()
    {
        $this->total_skor = $this->questions()->sum('skor');
        $this->save();
    }

    // 🏷️ Helper: Apakah task ini berupa kuis?
    public function isQuiz()
    {
        return $this->jenis_task === 'pilihan_ganda';
    }

    // 📎 Helper: Apakah task ini butuh upload file?
    public function isFileUpload()
    {
        return $this->jenis_task === 'upload_file';
    }
}
