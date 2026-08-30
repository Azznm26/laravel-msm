<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nilai STANDAR/TARGET per level career path, per kriteria.
// Ini yang dijadikan pembanding saat menghitung gap kompetensi karyawan.
// FK career_path_level_id merujuk ke tabel `career_path_levels` yang sudah
// ada di database (dikonfirmasi dari db_baru__12_.sql).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_level_standards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('career_path_level_id')
                ->constrained('career_path_levels')
                ->cascadeOnDelete();

            $table->foreignId('criteria_id')
                ->constrained('career_gap_criteria')
                ->cascadeOnDelete();

            // Skala nilai standar, umumnya 1-5
            $table->unsignedTinyInteger('target_value');

            $table->timestamps();

            // Satu level cuma boleh punya satu standar per kriteria
            $table->unique(
                ['career_path_level_id', 'criteria_id'],
                'level_criteria_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_level_standards');
    }
};
