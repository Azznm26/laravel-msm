<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel referensi: sub-kriteria/indikator di dalam sebuah aspek
// (mis. aspek "Kompetensi Teknis" -> kriteria "Penguasaan tools kerja").
// Tiap kriteria ditandai core/secondary untuk perhitungan Gap Analysis.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_gap_criteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aspect_id')
                ->constrained('career_gap_aspects')
                ->cascadeOnDelete();

            $table->string('name');
            $table->enum('factor_type', ['core', 'secondary']);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_gap_criteria');
    }
};
