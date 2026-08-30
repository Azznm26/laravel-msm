<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel referensi: daftar ASPEK penilaian (mis. "Kompetensi Teknis",
// "Kompetensi Manajerial", "Perilaku Kerja"). Data statis, diisi lewat seeder
// / admin panel — bukan tabel transaksional.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_gap_aspects', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Pembagian bobot Core Factor & Secondary Factor untuk aspek ini.
            // Standar metode Profile Matching, umumnya 60/40 — total harus 100.
            $table->decimal('cf_weight', 5, 2)->default(60.00);
            $table->decimal('sf_weight', 5, 2)->default(40.00);

            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_gap_aspects');
    }
};
