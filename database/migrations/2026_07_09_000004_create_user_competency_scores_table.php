<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nilai AKTUAL karyawan per kriteria, per periode penilaian.
// Tabel transaksional & historis -- satu user bisa punya banyak baris
// untuk kriteria yang sama di tanggal penilaian yang berbeda.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_competency_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('criteria_id')
                ->constrained('career_gap_criteria')
                ->cascadeOnDelete();

            // Skala nilai aktual, sama dengan skala target_value (1-5)
            $table->unsignedTinyInteger('actual_value');

            $table->date('assessed_at');

            // Siapa yang menilai (opsional -- atasan/HR yang input skor ini)
            $table->foreignId('assessed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_competency_scores');
    }
};
